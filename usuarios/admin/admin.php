<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #454D55;
        }

        .nav-link {
            color: #fff;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: #343A40;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #23272F;
            color: #fff;
            width: 250px;
        }

        .sidebar-sticky {
            padding-top: 10px;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            padding-top: 60px;
            padding: 20px;
        }

        .logout-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
        }

        .welcome-message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="sidebar">
        <div class="welcome-message">
            <?php
            session_start();
            if (isset($_SESSION['usuario'])) {
                echo "<p>Hola, " . $_SESSION['usuario'] . "</p>";
            }
            ?>
        </div>
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="?page=reservas">Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=nueva-reserva">Nueva Reserva</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./eventos.php">Ver Eventos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./agregar-evento.php">Agregar Evento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./add-recepcionista.php">Dar de Alta a Recepcionista</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?page=modificar-recepcionista">Modificar Recepcionista</a>
                </li>
                
                
            </ul>
        </div>
        <div class="logout-button">
            <form action="../../database/logout.php" method="post">
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <div class="main-content" id="main-content">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            include "$page.php";
        } else {
            include "reservas.php";
        }
        ?>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
