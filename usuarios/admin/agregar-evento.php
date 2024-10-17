<?php
include("../../database/conexion.php");

function obtenerTodosLosEventos()
{
    global $conexion;
    try {
        $consulta = "SELECT * FROM evento";
        $stmt = $conexion->query($consulta);
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $eventos;
    } catch (PDOException $e) {
        echo 'Error al obtener eventos: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return array();
    }
}

function agregarNuevoEvento($datos)
{
    global $conexion;
    try {
        $consulta = "INSERT INTO evento (nombre, telefono, fecha_evento, num_personas) VALUES (:nombre, :telefono, :fecha_evento, :num_personas)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':fecha_evento', $datos['fecha_evento']);
        $stmt->bindParam(':num_personas', $datos['num_personas']);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al agregar nuevo evento: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}

function fechaOcupada($fecha_evento)
{
    global $conexion;
    $consulta = "SELECT COUNT(*) FROM evento WHERE fecha_evento = :fecha_evento";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':fecha_evento', $fecha_evento);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_evento'])) {
    $nuevoEvento = array(
        'nombre' => $_POST['nombre'],
        'telefono' => $_POST['telefono'],
        'fecha_evento' => $_POST['fecha_evento'],
        'num_personas' => $_POST['num_personas']
    );

    if (!preg_match("/^\d{10}$/", $nuevoEvento['telefono'])) {
        echo "<script>alert('El número de teléfono debe tener 10 dígitos.');</script>";
    } elseif (fechaOcupada($nuevoEvento['fecha_evento'])) {
        echo "<script>alert('Fecha ocupada');</script>";
    } else {
        if (agregarNuevoEvento($nuevoEvento)) {
            echo "<script>alert('Evento agregado exitosamente');</script>";
            header("Location: eventos.php");
            exit;
        } else {
            echo "<script>alert('Error al agregar el evento');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Evento</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #191C21;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #4B94BF;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            font-size: 24px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label {
            font-weight: bold;
            color: white;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .boton-volver {
            text-align: center;
            margin-top: 60px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Agregar Nuevo Evento</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required pattern="\d{10}">
            </div>
            <div class="form-group">
                <label for="fecha_evento">Fecha del Evento:</label>
                <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="num_personas">Número de Personas:</label>
                <input type="number" class="form-control" id="num_personas" name="num_personas" min="1" max="200" required>
            </div>
            <button type="submit" name="agregar_evento" class="btn btn-primary">Agregar Evento</button>
        </form>
    </div>
    <div class="boton-volver">
        <a href="http://localhost/hotel/usuarios/admin/admin.php" class="btn btn-primary">Volver a inicio</a>
    </div>
</body>

</html>
