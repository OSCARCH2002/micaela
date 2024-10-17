<?php
include("../../database/conexion.php");

function verificarFechaOcupada($fecha_evento)
{
    global $conexion;
    $consulta = "SELECT COUNT(*) FROM evento WHERE fecha_evento = :fecha_evento";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':fecha_evento', $fecha_evento);
    $stmt->execute();
    return $stmt->fetchColumn() > 0; // Devuelve true si hay eventos en esa fecha
}

function agregarNuevoEvento($datos)
{
    global $conexion;
    try {
        // Verificar si la fecha ya está ocupada
        if (verificarFechaOcupada($datos['fecha_evento'])) {
            return json_encode(['success' => false, 'message' => 'Evento ocupado en la fecha seleccionada.']);
        }

        // Iniciar una transacción
        $conexion->beginTransaction();

        // Insertar datos en la tabla cliente
        $insertarCliente = "INSERT INTO cliente (nombre, apellidos, telefono) VALUES (:nombre, :apellido, :telefono)";
        $stmtCliente = $conexion->prepare($insertarCliente);
        $stmtCliente->bindParam(':nombre', $datos['nombre']);
        $stmtCliente->bindParam(':apellido', $datos['apellido']);
        $stmtCliente->bindParam(':telefono', $datos['telefono']);
        
        if (!$stmtCliente->execute()) {
            throw new Exception('Error al insertar cliente: ' . implode(", ", $stmtCliente->errorInfo()));
        }

        // Obtener el id_cliente recién insertado
        $id_cliente = $conexion->lastInsertId();
        
        if (!$id_cliente) {
            throw new Exception('Error al obtener el ID del cliente.');
        }

        // Insertar datos en la tabla evento
        $insertarEvento = "INSERT INTO evento (fecha_evento, num_personas, id_cliente) 
                           VALUES (:fecha_evento, :num_personas, :id_cliente)";
        $stmtEvento = $conexion->prepare($insertarEvento);
        $stmtEvento->bindParam(':fecha_evento', $datos['fecha_evento']);
        $stmtEvento->bindParam(':num_personas', $datos['num_personas']);
        $stmtEvento->bindParam(':id_cliente', $id_cliente);
        
        if (!$stmtEvento->execute()) {
            throw new Exception('Error al insertar evento: ' . implode(", ", $stmtEvento->errorInfo()));
        }

        // Confirmar la transacción
        $conexion->commit();
        return json_encode(['success' => true, 'message' => 'Evento reservado exitosamente.']);

    } catch (PDOException $e) {
        // Si algo falla, revertir la transacción
        $conexion->rollBack();
        return json_encode(['success' => false, 'message' => 'Error al agregar nuevo evento: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Captura de otros errores
        $conexion->rollBack();
        return json_encode(['success' => false, 'message' => 'Error general: ' . $e->getMessage()]);
    }
}

// Verificar si se está haciendo una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoEvento = array(
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'telefono' => $_POST['telefono'],
        'fecha_evento' => $_POST['fecha_evento'],
        'num_personas' => $_POST['num_personas']
    );
    echo agregarNuevoEvento($nuevoEvento);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Evento</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="fondo">
    <div class="container">
        <h1 class="titulo">Agregar Nuevo Evento</h1>
        <form id="eventoForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="fecha_evento">Fecha del Evento</label>
                <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" required>
            </div>
            <div class="form-group">
                <label for="num_personas">Número de Personas</label>
                <input type="number" class="form-control" id="num_personas" name="num_personas" required>
            </div>
            <button type="submit" class="btn btn-primary" id="agregar_evento">Agregar Evento</button>
            <div id="mensaje"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#eventoForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#mensaje').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                            $('#eventoForm')[0].reset(); // Limpiar los campos del formulario
                        } else {
                            $('#mensaje').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>