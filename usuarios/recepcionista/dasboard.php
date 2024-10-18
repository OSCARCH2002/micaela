<?php
include('../../database/conexion.php');
session_start(); 
$reservas_activas = 0;
$eventos_programados = 0;

try {
    $query_reservas = "SELECT COUNT(*) AS total_reservas FROM reservas WHERE CURDATE() <= fecha_salida";
    $resultado_reservas = $conexion->query($query_reservas);
    $fila_reservas = $resultado_reservas->fetch(PDO::FETCH_ASSOC);
    $reservas_activas = $fila_reservas['total_reservas'];

    $query_eventos = "SELECT COUNT(*) AS total_eventos FROM evento WHERE MONTH(fecha_evento) = MONTH(CURRENT_DATE())";
    $resultado_eventos = $conexion->query($query_eventos);
    $fila_eventos = $resultado_eventos->fetch(PDO::FETCH_ASSOC);
    $eventos_programados = $fila_eventos['total_eventos'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../css/style_dasboard.css">
</head>

<body>

    <div class="sidebar">
        <h2><a href="./dasboard.php" style="text-decoration: none; color: inherit;">Dashboard</a></h2>
        <ul>
            <li><button onclick="loadContent('./reservas.php')"><i class="fas fa-concierge-bell"></i> Reservas</button></li>
            <li><button onclick="loadContent('./addreservas.php')"><i class="fas fa-plus-circle"></i> Agregar Reservas</button></li>
            <li><button onclick="loadContent('eventos.php')"><i class="fas fa-calendar-alt"></i> Eventos</button></li>
            <li><button onclick="loadContent('./addevento.php')"><i class="fas fa-plus-square"></i> Agregar Eventos</button></li>
        </ul>
    </div>

    <div class="content" id="content">
        <div class="navbar">
            <h3>Bienvenido <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?></h3>
            <button onclick="window.location.href='../index.php'" class="btn-logout">Cerrar Sesión</button>
        </div>
        <div class="card">
            <h4>Resumen de Reservas</h4>
            <p>Hay <?php echo $reservas_activas; ?> reservas activas en este momento.</p>
        </div>
        <div class="card">
            <h4>Eventos Programados</h4>
            <p>Se han programado <?php echo $eventos_programados; ?> eventos para este mes.</p>
        </div>

        <div class="chart-container">
            <canvas id="reservasEventosChart"></canvas>
        </div>
    </div>

    <script>
        function loadContent(page) {
            const contentDiv = document.getElementById('content');

            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar el contenido');
                    }
                    return response.text();
                })
                .then(data => {
                    contentDiv.innerHTML = data;
                })
                .catch(error => {
                    contentDiv.innerHTML = `<p class="error-message">${error.message}</p>`;
                });
        }

        const ctx = document.getElementById('reservasEventosChart').getContext('2d');
        const reservasEventosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Reservas Activas', 'Eventos Programados'],
                datasets: [{
                    label: 'Total',
                    data: [<?php echo $reservas_activas; ?>, <?php echo $eventos_programados; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>
