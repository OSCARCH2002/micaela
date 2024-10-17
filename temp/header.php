<?php
$host = $_SERVER['HTTP_HOST'];
$url = "http://$host/hotel/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Micaela</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    .navbar-nav {
      margin: auto;
      padding-top: 20px;
    }

    .nav-link {
      border: none;
      border-radius: 5px;
      padding: 12px 20px;
      transition: background-color 0.3s;
      margin: 5px;
      color: #833C32;
      font-size: 20px;
    }

    .nav-link:hover {
      background-color: #833C32;
      color: #fff;
    }

    .nav-link:focus,
    .nav-link:active {
      outline: none;
    }

    .btn-primary {
      border: none;
      border-radius: 5px;
      padding: 12px 20px;
      transition: background-color 0.3s;
      margin: 5px;
      background-color: #dd5cd9ef;
      color: white;
    }

    .btn-primary:hover {
      background-color: #59a3dc;
    }

    .btn-primary:focus,
    .btn-primary:active {
      outline: none;
    }

    .navbar-brand img {
      border-radius: 50%;
      margin-right: 10px;
      height: 80px;
    }

    body {
      padding-top: 100px; /* Espacio para el navbar fijo */
    }

    @media (max-width: 768px) {
      .navbar-collapse {
        position: fixed;
        top: 0;
        right: -100%;
        height: 100%;
        width: 100%;
        background-color: rgba(52, 58, 64, 0.9);
        transition: transform 0.3s ease-in-out;
        transform: translateX(100%);
        padding-top: 20px;
        z-index: 9999;
      }

      .navbar-collapse.show {
        transform: translateX(0);
        right: 0;
      }

      .nav-link {
        font-size: 25px;
        color: white;
        text-align: center;
        display: block;
      }

      .navbar-toggler {
        display: block;
      }

      .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        color: white;
        font-size: 36px;
        cursor: pointer;
        z-index: 10000;
      }
    }

    @media (min-width: 769px) {
      .navbar-toggler {
        display: none;
      }

      .navbar-collapse {
        display: flex !important;
      }

      .navbar-nav {
        display: flex;
        justify-content: flex-end;
      }

      .close-btn {
        display: none; /* Ocultar la X en pantallas de computadora */
      }
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="<?php echo $url; ?>">
      <img src="<?php echo $url; ?>log/logo.png" alt="Logo de Micaela Confort" height="40">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <span class="close-btn" data-toggle="collapse" data-target="#navbarSupportedContent">&times;</span>
      <ul class="navbar-nav">
        <?php
        $paginas = array(
          "Principal" => "principal",
          "Reservar" => "reservas",
          "Evento" => "evento",
          "Galería" => "galeria"
        );

        foreach ($paginas as $nombre => $ruta) {
          echo '<li class="nav-item">';
          echo '<a class="nav-link" href="' . $url . 'pages/' . $ruta . '/">' . $nombre . '</a>';
          echo '</li>';
        }
        ?>
      </ul>
    </div>
  </nav>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
