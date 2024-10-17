<?php
include("../../database/conexion.php");

function obtenerTodasLasReservas() {
    global $conexion;
    try {
        // Realiza una consulta que une las tablas reservas y cliente
        $consulta = "
            SELECT 
                r.id AS reserva_id,
                c.nombre AS cliente_nombre,
                c.apellidos AS cliente_apellidos,
                c.telefono AS cliente_telefono,
                r.fecha_llegada,
                r.fecha_salida,
                r.total_adultos,
                r.total_ninos,
                r.total_pagar
            FROM 
                reservas r
            JOIN 
                cliente c ON r.id_cliente = c.id
        ";
        $stmt = $conexion->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener reservas: ' . $e->getMessage();
        return array();
    }
}

// Paginación
$registrosPorPagina = 10;
$totalRegistros = count(obtenerTodasLasReservas());
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaActual - 1) * $registrosPorPagina;
$reservas = obtenerTodasLasReservas();
$reservasPaginadas = array_slice($reservas, $indiceInicial, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Reservas</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Fecha de Llegada</th>
                        <th>Fecha de Salida</th>
                        <th>Total de Adultos</th>
                        <th>Total de Niños</th>
                        <th>Total a Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservasPaginadas as $reserva): ?>
                        <tr>
                            <td><?php echo $reserva['reserva_id']; ?></td>
                            <td><?php echo $reserva['cliente_nombre']; ?></td>
                            <td><?php echo $reserva['cliente_apellidos']; ?></td>
                            <td><?php echo $reserva['cliente_telefono']; ?></td>
                            <td><?php echo $reserva['fecha_llegada']; ?></td>
                            <td><?php echo $reserva['fecha_salida']; ?></td>
                            <td><?php echo $reserva['total_adultos']; ?></td>
                            <td><?php echo $reserva['total_ninos']; ?></td>
                            <td><?php echo $reserva['total_pagar']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($totalPaginas > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="#" onclick="loadContent('./reservas.php?pagina=<?php echo ($paginaActual - 1); ?>')">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="#" onclick="loadContent('./reservas.php?pagina=<?php echo $i; ?>')"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="#" onclick="loadContent('./reservas.php?pagina=<?php echo ($paginaActual + 1); ?>')">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function loadContent(page) {
            const contentDiv = document.getElementById('content');

            // Realiza la solicitud AJAX
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar el contenido');
                    }
                    return response.text();
                })
                .then(data => {
                    contentDiv.innerHTML = data; // Inserta el contenido cargado
                })
                .catch(error => {
                    contentDiv.innerHTML = `<p>${error.message}</p>`; // Muestra error si falla
                });
        }
    </script>
</body>
</html>