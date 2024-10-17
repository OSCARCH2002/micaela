<?php
// Incluir archivo de conexión
include('../../database/conexion.php');


// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar variables
$reservas_activas = 0;
$eventos_programados = 0;

// Nombre del usuario
$nombre_usuario = "Usuario"; // Cambia "Usuario" por el nombre real

try {
    // Consulta para obtener el número de reservas activas basadas en las fechas
    $query_reservas = "SELECT COUNT(*) AS total_reservas FROM reservas WHERE CURDATE() <= fecha_salida";
    $resultado_reservas = $conexion->query($query_reservas);
    $fila_reservas = $resultado_reservas->fetch(PDO::FETCH_ASSOC);
    $reservas_activas = $fila_reservas['total_reservas'];

    // Consulta para obtener el número de eventos programados para este mes
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #ecf0f1;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            margin-bottom: 20px;
        }

        .sidebar ul li button {
            width: 100%;
            padding: 12px;
            background-color: #34495e;
            color: #ecf0f1;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: left;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
            font-weight: 500;
        }

        .sidebar ul li button:hover {
            background-color: #1abc9c;
            transform: scale(1.02);
        }

        .content {
            flex-grow: 1;
            padding: 40px;
            overflow-y: auto;
            background-color: #ecf0f1;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 10px 20px;
            color: #ecf0f1;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .navbar h3 {
            font-size: 22px;
        }

        .user-menu {
            position: relative;
            cursor: pointer;
        }

        .user-menu .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: #34495e;
            color: #ecf0f1;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .user-menu:hover .dropdown {
            display: block;
        }

        .dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dropdown-item:hover {
            background-color: #1abc9c;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h4 {
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: 600;
        }

        .card p {
            color: #555;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Estilo para el gráfico */
        .chart-container {
            position: relative;
            height: 40vh;
            width: 100%; /* Cambiado a 100% para centrar las barras */
            max-width: 800px; /* Ancho máximo para el gráfico */
            margin: 0 auto; /* Centramos el contenedor */
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><button onclick="loadContent('./reservas.php')"><i class="fas fa-concierge-bell"></i> Reservas</button></li>
            <li><button onclick="loadContent('./addreservas.php')"><i class="fas fa-plus-circle"></i> Agregar Reservas</button></li>
            <li><button onclick="loadContent('eventos.php')"><i class="fas fa-calendar-alt"></i> Eventos</button></li>
            <li><button onclick="loadContent('./addevento.php')"><i class="fas fa-plus-square"></i> Agregar Eventos</button></li>
        </ul>
    </div>

    <div class="content" id="content">
        <div class="navbar">
            <h3>Bienvenido, <?php echo $nombre_usuario; ?> al Dashboard</h3>
            <div class="user-menu">
                <span>Usuario</span>
                <div class="dropdown">
                    <div class="dropdown-item" onclick="cerrarSesion()">Cerrar Sesión</div>
                </div>
            </div>
        </div>
        <div class="card">
            <h4>Resumen de Reservas</h4>
            <p>Hay <?php echo $reservas_activas; ?> reservas activas en este momento.</p>
        </div>
        <div class="card">
            <h4>Eventos Programados</h4>
            <p>Se han programado <?php echo $eventos_programados; ?> eventos para este mes.</p>
        </div>

        <!-- Gráfico de reservas y eventos -->
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

        function cerrarSesion() {
            alert("Has cerrado sesión.");
        }

        // Gráfico de reservas y eventos
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
