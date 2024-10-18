<?php
include("../../database/conexion.php");

// Habilita el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

function agregarNuevaReserva($datos)
{
    global $conexion;

    try {
        // Verifica la disponibilidad de la habitación
        if (!habitacionDisponible($datos['id_habitacion'], $datos['fecha_llegada'], $datos['fecha_salida'])) {
            echo '<div class="alert alert-danger" role="alert">Habitación ocupada en las fechas seleccionadas.</div>';
            return false;
        }

        // Asegúrate de que el cliente existe o crea uno nuevo
        $id_cliente = obtenerOCrearCliente($datos['nombre'], $datos['apellidos'], $datos['telefono']);

        // Prepara la consulta para agregar la reserva
        $consulta = "INSERT INTO reservas (id_cliente, id_habitacion, fecha_llegada, fecha_salida, total_adultos, total_ninos, total_pagar) 
                     VALUES (:id_cliente, :id_habitacion, :fecha_llegada, :fecha_salida, :total_adultos, :total_ninos, :total_pagar)";

        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_habitacion', $datos['id_habitacion']);
        $stmt->bindParam(':fecha_llegada', $datos['fecha_llegada']);
        $stmt->bindParam(':fecha_salida', $datos['fecha_salida']);
        $stmt->bindParam(':total_adultos', $datos['total_adultos']);
        $stmt->bindParam(':total_ninos', $datos['total_ninos']);
        $stmt->bindParam(':total_pagar', $datos['total_pagar']);
        $stmt->execute();

        echo '<div class="alert alert-success" role="alert">Reserva exitosa.</div>';
        return true;
    } catch (PDOException $e) {
        echo 'Error al agregar nueva reserva: ' . $e->getMessage();
        return false;
    }
}

function habitacionDisponible($idHabitacion, $fechaLlegada, $fechaSalida)
{
    global $conexion;

    try {
        $sql = "SELECT COUNT(*) AS count FROM reservas WHERE id_habitacion = :idHabitacion AND fecha_llegada < :fechaSalida AND fecha_salida > :fechaLlegada";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idHabitacion', $idHabitacion);
        $stmt->bindParam(':fechaLlegada', $fechaLlegada);
        $stmt->bindParam(':fechaSalida', $fechaSalida);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'];

        return $count == 0; // Devuelve true si la habitación está disponible
    } catch (PDOException $e) {
        echo 'Error al verificar disponibilidad de la habitación: ' . $e->getMessage();
        return false;
    }
}

function obtenerOCrearCliente($nombre, $apellidos, $telefono)
{
    global $conexion;

    // Verificar si el cliente ya existe
    $sql = "SELECT id FROM cliente WHERE nombre = :nombre AND apellidos = :apellidos AND telefono = :telefono";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        return $cliente['id']; // Retorna el id del cliente existente
    } else {
        // Crear un nuevo cliente
        $sql = "INSERT INTO cliente (nombre, apellidos, telefono) VALUES (:nombre, :apellidos, :telefono)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        return $conexion->lastInsertId(); // Retorna el id del nuevo cliente
    }
}

// Comprobar si se envió el formulario
if (isset($_POST['agregar_reserva'])) {
    $nuevaReserva = array(
        'id_habitacion' => $_POST['id_habitacion'],
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'telefono' => $_POST['telefono'],
        'fecha_llegada' => $_POST['fecha_llegada'],
        'fecha_salida' => $_POST['fecha_salida'],
        'total_adultos' => $_POST['total_adultos'],
        'total_ninos' => $_POST['total_ninos'],
        'total_pagar' => $_POST['total_pagar']
    );

    agregarNuevaReserva($nuevaReserva);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Reserva</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/addreservas.css">
</head>

<body>
    <div class="container">
        <div class="card-header">
            <h2>COMPLETA LOS DATOS PARA AÑADIR</h2>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" oninput="calcularTotalPagar()">
                <div class="form-container">
                    <div class="form-group">
                        <label for="id_habitacion">Número de Habitación:</label>
                        <select class="form-control" id="id_habitacion" name="id_habitacion" required>
                            <?php
                            $habitaciones = $conexion->query('SELECT * FROM habitacion');
                            while ($habitacion = $habitaciones->fetch()) {
                                echo '<option value="' . $habitacion["id"] . '">' . $habitacion["nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10" pattern="\d{10}" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_llegada">Fecha de Llegada:</label>
                        <input type="date" class="form-control" id="fecha_llegada" name="fecha_llegada" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_salida">Fecha de Salida:</label>
                        <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="total_adultos">Total de Adultos:</label>
                        <input type="number" class="form-control" id="total_adultos" name="total_adultos" required>
                    </div>
                    <div class="form-group">
                        <label for="total_ninos">Total de Niños:</label>
                        <input type="number" class="form-control" id="total_ninos" name="total_ninos" min="0" max="2" required>
                    </div>
                    <div class="form-group total-pagar-group">
                        <label for="total_pagar">Total a Pagar:</label>
                        <input type="number" class="form-control" id="total_pagar" name="total_pagar" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="agregar_reserva">Agregar Reserva</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function calcularTotalPagar() {
            var fechaLlegada = $('#fecha_llegada').val();
            var fechaSalida = $('#fecha_salida').val();
            var totalAdultos = parseInt($('#total_adultos').val());
            var totalNinos = parseInt($('#total_ninos').val());

            if (fechaLlegada && fechaSalida) {
                var fecha1 = new Date(fechaLlegada);
                var fecha2 = new Date(fechaSalida);
                var tiempoDif = fecha2 - fecha1;
                var dias = tiempoDif / (1000 * 3600 * 24);

                if (dias > 30) {
                    $('#total_pagar').val(1800);
                } else {
                    var costoPorNoche = 300;
                    var totalPagar = costoPorNoche * dias + (totalAdultos > 2 ? (totalAdultos - 2) * 50 : 0);
                    $('#total_pagar').val(totalPagar);
                }
            }
        }
    </script>
</body>

</html>
