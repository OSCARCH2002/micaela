<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /* Estilo del sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
            position: relative;
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

        /* Estilo del contenedor del contenido */
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

        /* Estilos de tarjetas */
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

        /* Estilo para los mensajes de error */
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
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
            <h3>Bienvenido al Dashboard</h3>
            <div class="user-menu">
                <span>Usuario</span>
                <div class="dropdown">
                    <div class="dropdown-item" onclick="cerrarSesion()">Cerrar Sesión</div>
                </div>
            </div>
        </div>
        <div class="card">
            <h4>Resumen de Reservas</h4>
            <p>Hay 10 reservas activas en este momento.</p>
        </div>
        <div class="card">
            <h4>Eventos Programados</h4>
            <p>Se han programado 5 eventos para este mes.</p>
        </div>
    </div>

    <script>
        // Función para cargar el contenido de las secciones
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
                    contentDiv.innerHTML = `<p class="error-message">${error.message}</p>`; // Muestra error si falla
                });
        }

        // Función para cerrar sesión (simulada)
        function cerrarSesion() {
            alert("Has cerrado sesión.");
            // Aquí podrías redirigir al usuario a una página de login o inicio.
        }
    </script>

</body>

</html>
