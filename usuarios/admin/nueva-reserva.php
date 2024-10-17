<?php
include("../../database/conexion.php");

function calcularDias($fecha_llegada, $fecha_salida)
{
    $fechaInicio = new DateTime($fecha_llegada);
    $fechaFin = new DateTime($fecha_salida);
    return $fechaInicio->diff($fechaFin)->days;
}

function calcularTotalPagar($dias, $total_adultos, $tipo_habitacion)
{
    $precioNoche = 300;
    $precioRenta = 1800;
    $total = ($tipo_habitacion === "noches") ? $dias * $precioNoche : ceil($dias / 30) * $precioRenta;
    $comisionAdicional = ($total_adultos > 2) ? ($total_adultos - 2) * 50 : 0;
    return $total + $comisionAdicional;
}

function habitacionDisponible($idHabitacion, $fechaLlegada, $fechaSalida)
{
    global $conexion;
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM reservas WHERE id_habitacion = ? AND NOT (fecha_salida <= ? OR fecha_llegada >= ?)");
    $stmt->execute([$idHabitacion, $fechaLlegada, $fechaSalida]);
    return $stmt->fetchColumn() == 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_reserva"])) {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $telefono = $_POST["telefono"];
    $fecha_llegada = $_POST["fecha_llegada"];
    $fecha_salida = $_POST["fecha_salida"];
    $id_habitacion = $_POST["id_habitacion"];
    $total_adultos = $_POST["total_adultos"];
    $total_ninos = $_POST["total_ninos"];

    $dias = calcularDias($fecha_llegada, $fecha_salida);
    $tipo_habitacion = ($dias < 30) ? "noches" : "renta";
    $total_pagar = calcularTotalPagar($dias, $total_adultos, $tipo_habitacion);

    if (!preg_match("/^\d{10}$/", $telefono)) {
        echo "<script>alert('El número de teléfono debe tener 10 dígitos.');</script>";
    } elseif (!habitacionDisponible($id_habitacion, $fecha_llegada, $fecha_salida)) {
        echo "<script>alert('Habitación ocupada');</script>";
    } else {
        $sql = "INSERT INTO reservas (nombre, apellidos, telefono, fecha_llegada, fecha_salida, tipo_habitacion, total_adultos, total_ninos, total_pagar, id_habitacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        if ($stmt->execute([$nombre, $apellidos, $telefono, $fecha_llegada, $fecha_salida, $tipo_habitacion, $total_adultos, $total_ninos, $total_pagar, $id_habitacion])) {
            echo "<script>alert('Reserva exitosa');</script>";
        } else {
            echo "<script>alert('Error al agregar la reserva');</script>";
        }
    }
}

$habitaciones = $conexion->query("SELECT id, nombre FROM habitacion WHERE estado = 'disponible'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Reserva</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #191C21;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: #4B94BF;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        .titulo {
            color: #343a40;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }

        .btn-primary,
        .btn-secondary {
            border: none;
            font-size: 16px;
            padding: 12px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="titulo">Agregar Nueva Reserva</h1>
        <form method="POST" action="" oninput="calcularTotal()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" name="telefono" required pattern="\d{10}">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_llegada">Fecha Llegada</label>
                        <input type="date" class="form-control" name="fecha_llegada" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fecha_salida">Fecha Salida</label>
                        <input type="date" class="form-control" name="fecha_salida" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                        <label for="editarIdHabitacion">Número de Habitación:</label>
                        <select class="form-control" id="editarIdHabitacion" name="id_habitacion" required>
                           <?php
                           $habitaciones = $conexion->query('SELECT * FROM habitacion');
                           while ($habitacion = $habitaciones->fetch()) {
                              echo '<option value="' . $habitacion["id"] . '">' . $habitacion["nombre"] . '</option>';
                           }
                           ?>
                        </select>
                     </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_adultos">Total de Adultos</label>
                        <input type="number" class="form-control" name="total_adultos" min="1" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="total_ninos">Total de Niños</label>
                        <input type="number" class="form-control" name="total_ninos" min="1" max="2" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="numero_noches">Número de Noches</label>
                <input type="text" class="form-control" name="numero_noches" readonly>
            </div>
            <div class="form-group">
                <label for="tipo_habitacion">Tipo de habitación</label>
                <input type="text" class="form-control" name="tipo_habitacion" readonly>
            </div>
            <div class="form-group">
                <label for="total_pagar">Total a Pagar</label>
                <input type="text" class="form-control" name="total_pagar" readonly>
            </div>
            <div class="btn-container">
                <button type="submit" name="agregar_reserva" class="btn btn-primary">Guardar Reserva</button>
                <a href="http://localhost/hotel/usuarios/admin/admin.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    <script>
        function calcularTotal() {
            const fechaLlegada = document.querySelector('[name="fecha_llegada"]').value;
            const fechaSalida = document.querySelector('[name="fecha_salida"]').value;
            const totalAdultos = parseInt(document.querySelector('[name="total_adultos"]').value, 10);
            if (fechaLlegada && fechaSalida && totalAdultos) {
                const dias = calcularDias(fechaLlegada, fechaSalida);
                document.querySelector('[name="numero_noches"]').value = dias;
                const tipoHabitacion = dias < 30 ? 'noches' : 'renta';
                document.querySelector('[name="tipo_habitacion"]').value = tipoHabitacion;
                const totalPagar = calcularTotalPagar(dias, totalAdultos, tipoHabitacion);
                document.querySelector('[name="total_pagar"]').value = totalPagar.toFixed(2);
            }
        }

        function calcularDias(fechaLlegada, fechaSalida) {
            const inicio = new Date(fechaLlegada);
            const fin = new Date(fechaSalida);
            return (fin - inicio) / (1000 * 60 * 60 * 24);
        }

        function calcularTotalPagar(dias, totalAdultos, tipoHabitacion) {
            const precioNoche = 300;
            const precioRenta = 1800;
            let total = tipoHabitacion === 'noches' ? dias * precioNoche : Math.ceil(dias / 30) * precioRenta;
            if (totalAdultos > 2) {
                total += (totalAdultos - 2) * 50;
            }
            return total;
        }

        function validarFormulario() {
            const fechaLlegada = document.querySelector('[name="fecha_llegada"]').value;
            const fechaSalida = document.querySelector('[name="fecha_salida"]').value;
            if (fechaLlegada >= fechaSalida) {
                alert('La fecha de salida debe ser mayor que la fecha de llegada.');
                return false;
            }
            return true;
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>