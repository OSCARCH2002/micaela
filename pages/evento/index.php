<?php
include("../../temp/header.php");

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "567890";
$dbname = "propuesta"; // Cambié el nombre de la base de datos a 'propuesta'

// Crear conexión
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Establecer el modo de error de PDO a excepción
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Verificar si se ha enviado el formulario
$message = '';
$message_type = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $telefono = $_POST['telefono'];
    $fecha_evento = $_POST['fecha-evento'];
    $num_personas = $_POST['num-personas'];

    // Primero, insertar o verificar al cliente
    $stmt = $conn->prepare("SELECT id FROM cliente WHERE nombre = :nombre AND apellidos = :apellidos AND telefono = :telefono");
    $stmt->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'telefono' => $telefono]);
    $id_cliente = $stmt->fetchColumn();

    if (!$id_cliente) {
        // Si el cliente no existe, insertarlo
        $stmt = $conn->prepare("INSERT INTO cliente (nombre, apellidos, telefono) VALUES (:nombre, :apellidos, :telefono)");
        $stmt->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'telefono' => $telefono]);
        $id_cliente = $conn->lastInsertId(); // Obtener el ID del nuevo cliente
    }

    // Comprobar si ya existe un evento en la misma fecha
    $stmt = $conn->prepare("SELECT COUNT(*) FROM evento WHERE fecha_evento = :fecha_evento");
    $stmt->execute(['fecha_evento' => $fecha_evento]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $message = "Lo siento, ya hay un evento programado para esta fecha.";
        $message_type = 'danger';
    } else {
        // Preparar la consulta SQL para insertar los datos en la tabla evento
        $stmt = $conn->prepare("INSERT INTO evento (id_cliente, fecha_evento, num_personas) VALUES (:id_cliente, :fecha_evento, :num_personas)");

        // Ejecutar la consulta
        try {
            $stmt->execute([
                'id_cliente' => $id_cliente,
                'fecha_evento' => $fecha_evento,
                'num_personas' => $num_personas
            ]);
            $message = "La reserva se ha realizado con éxito.";
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = "Error al realizar la reserva: " . $e->getMessage();
            $message_type = 'danger';
        }
    }

    // Cerrar la conexión
    $conn = null; // Cerrar la conexión PDO
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Hotel Micaela</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap">
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Establecer la fecha mínima del calendario como hoy
            const fechaEventoInput = document.getElementById('fecha-evento');
            const today = new Date().toISOString().split('T')[0];
            fechaEventoInput.setAttribute('min', today);

            // Mostrar alerta si hay un mensaje
            const message = "<?php echo $message; ?>";
            const messageType = "<?php echo $message_type; ?>";
            if (message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${messageType} mt-3`;
                alertDiv.role = 'alert';
                alertDiv.innerText = message;
                document.querySelector('.contenido').prepend(alertDiv);
            }
        });
    </script>
</head>
<script>
window.addEventListener('mouseover', initLandbot, { once: true });
window.addEventListener('touchstart', initLandbot, { once: true });
var myLandbot;
function initLandbot() {
  if (!myLandbot) {
    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;
    s.addEventListener('load', function() {
      var myLandbot = new Landbot.Livechat({
        configUrl: 'https://storage.googleapis.com/landbot.site/v3/H-2630584-OVALXNSVXJFVBSYT/index.json',
      });
    });
    s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  }
}
</script>
<body>
    <div class="container">
        <br>
        <h1 class="section-title">Reservar evento</h1>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="contenido">
                    <form method="post" class="formulario">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su Nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Ingrese sus Apellidos" required>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ingrese su número de Teléfono" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha-evento">Fecha de evento:</label>
                            <input type="date" class="form-control" id="fecha-evento" name="fecha-evento" placeholder="Seleccione la Fecha" required>
                        </div>

                        <div class="form-group">
                            <label for="num-personas">Número de personas:</label>
                            <input type="number" class="form-control" id="num-personas" name="num-personas" placeholder="Ingrese el Número de Personas" min="1" max="200" required>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <!-- Sección de Fotos -->
        <div class="photo-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="gallery">
                        <img src="../../images/evento1.jpeg" alt="Foto 1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery">
                        <img src="../../images/evento2.jpeg" alt="Foto 2">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery">
                        <img src="../../images/evento3.jpeg" alt="Foto 3">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Informativa -->
        <div class="info-section">
            <h2 class="section-title">¿Por qué elegirnos?</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="info-card">
                        <h4>Servicio de alta calidad</h4>
                        <p>Nuestro personal altamente capacitado se encargará de que su evento sea todo un éxito.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h4>Ubicación conveniente</h4>
                        <p>Estamos ubicados en el corazón de la ciudad, cerca de los principales lugares de interés.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h4>Instalaciones</h4>
                        <p>Nuestra sala de eventos está ampliamente cómoda para satisfacer todas sus necesidades.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h4>Precios competitivos</h4>
                        <p>Ofrecemos tarifas competitivas para que pueda disfrutar de un evento excepcional sin comprometer su presupuesto.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Footer -->
<?php include "../../temp/footer.php"; ?>

</html>
