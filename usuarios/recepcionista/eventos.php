<?php
include("../../database/conexion.php");

function obtenerTodosLosEventos()
{
    global $conexion;
    try {
        // Cambié la consulta para obtener los datos necesarios de las tablas cliente y evento
        $consulta = "
            SELECT 
                e.id, 
                c.nombre, 
                c.apellidos, 
                c.telefono, 
                e.fecha_evento, 
                e.num_personas 
            FROM 
                evento e
            JOIN 
                cliente c ON e.id_cliente = c.id
        ";
        $stmt = $conexion->query($consulta);
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $eventos;
    } catch (PDOException $e) {
        echo 'Error al obtener eventos: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return array();
    }
}

// Paginación
$registrosPorPagina = 10;
$totalRegistros = count(obtenerTodosLosEventos());
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaActual - 1) * $registrosPorPagina;
$eventos = obtenerTodosLosEventos();
$eventosPaginados = array_slice($eventos, $indiceInicial, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepcionista - Eventos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="fondo">
    <div class="container">
        <div class="welcome-message text-center" style="color: darkblue; font-size:25px;">
            <?php
            session_start();
            if (isset($_SESSION['usuario'])) {
                echo 'Hola, ' . $_SESSION['usuario'] . '!';
            }
            ?>
        </div>
        <h1 class="text-center text-dark font-weight-bold my-4">Recepcionista - Eventos</h1>
        <div id="eventos-container" class="table-responsive table-background">
            <table class="table table-striped table-sm table-spacing">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Fecha del Evento</th>
                        <th>Número de Personas</th>
                    </tr>
                </thead>
                <tbody id="eventos-body">
                    <?php foreach ($eventosPaginados as $evento) : ?>
                        <tr>
                            <td><?php echo $evento['id']; ?></td>
                            <td><?php echo $evento['nombre']; ?></td>
                            <td><?php echo $evento['apellidos']; ?></td>
                            <td><?php echo $evento['telefono']; ?></td>
                            <td><?php echo $evento['fecha_evento']; ?></td>
                            <td><?php echo $evento['num_personas']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($totalPaginas > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="#" onclick="loadContent('./eventos.php?pagina=<?php echo ($paginaActual - 1); ?>')">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="#" onclick="loadContent('./eventos.php?pagina=<?php echo $i; ?>')"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="#" onclick="loadContent('./eventos.php?pagina=<?php echo ($paginaActual + 1); ?>')">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>