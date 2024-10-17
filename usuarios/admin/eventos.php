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

function eliminarEvento($id)
{
    global $conexion;
    try {
        $consulta = "DELETE FROM evento WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al eliminar evento: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}

function actualizarEvento($datos)
{
    global $conexion;
    try {
        $consulta = "UPDATE evento SET nombre = :nombre, telefono = :telefono, fecha_evento = :fecha_evento, num_personas = :num_personas WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':fecha_evento', $datos['fecha_evento']);
        $stmt->bindParam(':num_personas', $datos['num_personas']);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al actualizar el evento: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}

if (isset($_POST['eliminar_evento'])) {
    $idEvento = $_POST['id_evento'];
    eliminarEvento($idEvento);
}

if (isset($_POST['editar_evento'])) {
    $eventoActualizado = array(
        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'telefono' => $_POST['telefono'],
        'fecha_evento' => $_POST['fecha_evento'],
        'num_personas' => $_POST['num_personas']
    );
    actualizarEvento($eventoActualizado);
}

$registrosPorPagina = 10;
$eventos = obtenerTodosLosEventos();
$totalRegistros = count($eventos);
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaActual - 1) * $registrosPorPagina;
$eventosPaginados = array_slice($eventos, $indiceInicial, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepcionista - Eventos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-spacing tbody tr {
            margin-bottom: 10px;
        }

        .btn-group .btn {
            margin-right: 15px;
        }

        .welcome-message {
            text-align: center;
            top: 1px;
            left: 260px;
            color: blue;
            font-size: 20px;
            font-weight: bold;
        }

        .titulo {
            color: darkblue;
            font-size: 25px;
            font-weight: bold;
            margin-top: 10px;
            margin-left: 31px;
        }

        .btn-agregar-evento {
            margin-top: 10px;
        }

        #inputBuscar {
            width: 150px;
            float: right;
            margin-top: 10px;
        }

        .table-background {
            position: relative;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            opacity: 0.8;
            z-index: -1;
        }

        .fondo {
            background-image: url('http://localhost/hotel/images/fondo.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
            position: relative;
        }

        .fondo::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            opacity: 0.4;
            z-index: -1;
        }

        .boton-volver {
            text-align: center;
            margin-top: 60px;
        }
    </style>
</head>

<body class="fondo">
    <div class="container">
        <div class="container">
            <br>
            <h1 class="titulo">Mis Eventos</h1>
            <div class="mb-2">
                <input type="text" id="inputBuscar" class="form-control form-control-sm" placeholder="Buscar" style="width: 150px;" autocomplete="off">
                <div id="sinResultados" class="alert alert-info" role="alert" style="display: none;">
                    Sin resultados.
                </div>
            </div>

            <div class="table-responsive table-background" id="tablaEventos">
                <table class="table table-striped table-sm table-spacing">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Fecha del Evento</th>
                            <th>Número de Personas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventosPaginados as $evento) : ?>
                            <tr>
                                <td><?php echo $evento['id']; ?></td>
                                <td><?php echo $evento['nombre']; ?></td>
                                <td><?php echo $evento['telefono']; ?></td>
                                <td><?php echo $evento['fecha_evento']; ?></td>
                                <td><?php echo $evento['num_personas']; ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones">
                                        <button class="btn btn-sm btn-primary btn-editar" data-toggle="modal" data-target="#modalEditarEvento" data-id="<?php echo $evento['id']; ?>" data-nombre="<?php echo $evento['nombre']; ?>" data-telefono="<?php echo $evento['telefono']; ?>" data-fecha_evento="<?php echo $evento['fecha_evento']; ?>" data-num_personas="<?php echo $evento['num_personas']; ?>">Editar</button>
                                        <form method="post" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                            <input type="hidden" name="id_evento" value="<?php echo $evento['id']; ?>">
                                            <button type="submit" name="eliminar_evento" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if ($totalPaginas > 1) : ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?pagina=<?php echo ($paginaActual - 1); ?>" tabindex="-1" aria-disabled="true">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                                <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?pagina=<?php echo ($paginaActual + 1); ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal fade" id="modalEditarEvento" tabindex="-1" role="dialog" aria-labelledby="modalEditarEventoTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarEventoTitle">Editar Evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" id="editar_id" name="id"> <!-- Campo oculto para el ID -->
                            <div class="form-group">
                                <label for="editar_nombre">Nombre:</label>
                                <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="editar_telefono">Teléfono:</label>
                                <input type="text" class="form-control" id="editar_telefono" name="telefono" required pattern="\d{10}">
                            </div>
                            <div class="form-group">
                                <label for="editar_fecha_evento">Fecha del Evento:</label>
                                <input type="date" class="form-control" id="editar_fecha_evento" name="fecha_evento" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editar_num_personas">Número de Personas:</label>
                                <input type="number" class="form-control" id="editar_num_personas" name="num_personas" min="1" max="200" required>
                            </div>
                            <button type="submit" name="editar_evento" class="btn btn-primary">Guardar Cambios</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
        <div class="boton-volver">
            <a href="http://localhost/hotel/usuarios/admin/admin.php" class="btn btn-primary">Volver a inicio</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-editar').click(function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var telefono = $(this).data('telefono');
                var fecha_evento = $(this).data('fecha_evento');
                var num_personas = $(this).data('num_personas');

                $('#editar_id').val(id);
                $('#editar_nombre').val(nombre);
                $('#editar_telefono').val(telefono);
                $('#editar_fecha_evento').val(fecha_evento);
                $('#editar_num_personas').val(num_personas);
            });

            $('#inputBuscar').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaEventos tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
                var visibleRows = $('#tablaEventos tbody tr:visible').length;
                $('#sinResultados').toggle(visibleRows === 0);
            });
        });
    </script>
</body>

</html>
