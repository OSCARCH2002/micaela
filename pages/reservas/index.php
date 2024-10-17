<?php
include("../../temp/header.php");

// Habilita el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configura la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "567890";
$dbname = "propuesta";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesa el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén y sanitiza los datos del formulario
    $roomNumber = intval($_POST['roomNumber']);
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $apellidos = $conn->real_escape_string(trim($_POST['apellidos']));
    $telefono = $conn->real_escape_string(trim($_POST['telefono']));
    $fechaLlegada = $conn->real_escape_string(trim($_POST['fechaLlegada']));
    $fechaSalida = $conn->real_escape_string(trim($_POST['fechaSalida']));
    $totalAdultos = intval($_POST['totalAdultos']);
    $totalNinos = intval($_POST['totalNinos']);
    $totalPagar = $conn->real_escape_string(trim($_POST['totalPagar']));

    // Calcula el número de noches
    $fecha1 = new DateTime($fechaLlegada);
    $fecha2 = new DateTime($fechaSalida);
    $diferencia = $fecha2->diff($fecha1);
    $noches = $diferencia->days;

    // Verifica la disponibilidad de la habitación
    $stmt = $conn->prepare("
        SELECT id FROM reservas
        WHERE id_habitacion = ? AND (fecha_llegada <= ? AND fecha_salida >= ?)
    ");
    $stmt->bind_param("iss", $roomNumber, $fechaSalida, $fechaLlegada);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('La habitación está ocupada en las fechas seleccionadas.');</script>";
    } else {
        // Inserta el cliente en la tabla `cliente`
        $stmt = $conn->prepare("INSERT INTO cliente (nombre, apellidos, telefono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $apellidos, $telefono);

        if ($stmt->execute()) {
            $clienteId = $stmt->insert_id; // Obtén el ID del cliente insertado

            // Calcula el total a pagar
            $total = $noches * 300; // Se multiplica el número de noches por 300

            if ($noches > 30) {
                $total = 1800; // Cobro automático por estancia mayor a 30 días
            }

            if ($totalAdultos > 2) {
                $adultosExtras = $totalAdultos - 2;
                $total += $adultosExtras * 50;

            }


            $stmt = $conn->prepare("INSERT INTO reservas (id_cliente, id_habitacion, fecha_llegada, fecha_salida, total_adultos, total_ninos, total_pagar)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissddd", $clienteId, $roomNumber, $fechaLlegada, $fechaSalida, $totalAdultos, $totalNinos, $total);

            if ($stmt->execute()) {
                $reservaId = $stmt->insert_id; // Obtén el ID de la reserva insertada
                echo "<script>
                    alert('Reserva exitosa');
                    // Redirigir a la generación del PDF
                    window.open('../../libs/generate_pdf.php?reservaId=$reservaId', '_blank');
                    // Redirigir a la página de reservas
                    window.location.href = '../../pages/reservas';
                </script>";
            } else {
                // Muestra el error de la consulta de reservas
                echo "<div class='alert alert-danger' role='alert'>Error al realizar la reserva: " . $stmt->error . "</div>";
            }
        } else {
            // Muestra el error de la consulta de cliente
            echo "<div class='alert alert-danger' role='alert'>Error al registrar el cliente: " . $stmt->error . "</div>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas Quinta Micaela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./stylehuesped.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body>
    <script>
        window.addEventListener('mouseover', initLandbot, { once: true });
        window.addEventListener('touchstart', initLandbot, { once: true });
        var myLandbot;
        function initLandbot() {
            if (!myLandbot) {
                var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
                s.addEventListener('load', function () {
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
    <div class="container mt-5">
        <div class="text-center">
            <h2>HABITACIONES</h2>
            <h3 class="text-muted">HOTEL QUINTA MICAELA</h3>
            <p>Disfruta de la mejor experiencia en el hotel Quinta Micaela durante tu estancia en
                San Luis Acatlán. Somos tu mejor opción, ya que ofrecemos precios accesibles
                y competitivos. Tú decides cómo deseas descansar.</p>
        </div>

        <div class="container my-5">
            <div class="row">

                <div class="col-md-6">

                    <div class="position-relative" id="imageContainer">
                        <div class="image-stack">
                            <img src="../../images/renta.jpeg" alt="Cama" class="img-fluid rounded image-stack-item">
                            <img src="../../images/lavado.jpg" alt="Habitación"
                                class="img-fluid rounded image-stack-item">
                            <img src="../../images/Cuarto_Camas_Individual.jpeg" alt="Servicios"
                                class="img-fluid rounded image-stack-item">
                            <img src="../../images/Interio_Cuarto.jpeg" alt="Amenidades"
                                class="img-fluid rounded image-stack-item">
                        </div>
                        <!-- Botón Ver más sobre la imagen -->
                        <button class="ver-mas-btn" id="verMasBtn" data-bs-toggle="modal"
                            data-bs-target="#imageGalleryModal">Ver más fotos</button>
                    </div>

                    <!-- CARRUSEL DE ICONOS DE AMENIDADES -->
                    <div id="amenitiesCarousel" class="carousel slide mt-5" data-bs-ride="carousel">
                        <div class="carousel-inner text-center">
                            <div class="carousel-item active">
                                <i class="bi bi-tv amenities-icon"></i>
                                <p>TV</p>
                            </div>
                            <div class="carousel-item">
                                <i class="bi bi-wifi amenities-icon"></i>
                                <p>Wifi gratis</p>
                            </div>
                            <div class="carousel-item">
                                <i class="bi bi-building amenities-icon"></i>
                                <p>Hermosa vista</p>
                            </div>
                            <div class="carousel-item">
                                <i class="bi bi-cup amenities-icon"></i>
                                <p>Baño privado</p>
                            </div>
                            <div class="carousel-item">
                                <i class="bi bi-water amenities-icon"></i>
                                <p>Colchones Matrimoniales</p>
                            </div>
                        </div>
                        <!-- Botones de control a los lados -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#amenitiesCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#amenitiesCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="room-title">Estamos ubicados en el corazón de San Luis Acatlán</h4>
                    <p class="room-description">Contempla la hermosa naturaleza y pasa una noche agradable.</p>

                    <p class="room-price">Precio de nuestras habitaciones:</p>
                    <p class="room-description">$300 la noche</p>
                    <p class="room-description">$1,800 la renta</p>

                    <button class="room-button" data-bs-toggle="modal" data-bs-target="#reservationModal">Reservar
                        ahora</button>

                    <div class="text-center mt-5">
                        <h4>OFRECEMOS LOS SIGUIENTES SERVICIOS</h4>
                        <ul class="amenities-list mt-3">
                            <li><i class="bi bi-droplet"></i>Agua caliente</li>
                            <li><i class="bi bi-wifi"></i>Wifi gratis</li>
                            <li><i class="fas fa-car"></i>Estacionamiento gratis</li>
                            <li><i class="bi bi-tv"></i>TV por cable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE GALERÍA DE IMÁGENES -->
        <div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="../../images/renta.jpeg" class="d-block w-100" alt="Foto 1">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../images/Cuarto_Camas_Individual.jpeg" class="d-block w-100" alt="Foto 2">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../images/huesped.jpeg" class="d-block w-100" alt="Foto 3">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../images/Servicios_Cuarto.jpeg" class="d-block w-100" alt="Foto 4">
                                </div>
                                <div class="carousel-item">
                                    <img src="../../images/Interio_Cuarto.jpeg" class="d-block w-100" alt="Foto 5">
                                </div>
                            </div>



                            <!-- Controles del carrusel -->
                            <div class="carousel-controls">
                                <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- MODAL DE RESERVA -->
        <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationModalLabel">¡Completa tus datos!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reservationForm" method="POST">
                            <div class="mb-3">
                                <label for="roomNumber" class="form-label">Número de Habitación</label>
                                <select id="roomNumber" name="roomNumber" class="form-select" required>
                                    <option value="" disabled selected>Seleccione una habitación</option>
                                    <?php for ($i = 1; $i <= 7; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    pattern="[A-Za-zÀ-ÿ\s]+" title="Solo se permiten letras y espacios" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    pattern="[A-Za-zÀ-ÿ\s]+" title="Solo se permiten letras y espacios" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="10"
                                    pattern="\d{10}" title="Debe contener exactamente 10 dígitos"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                            </div>

                            <div class="mb-3">
                                <label for="fechaLlegada" class="form-label">Fecha de Llegada</label>
                                <input type="date" class="form-control" id="fechaLlegada" name="fechaLlegada" required>
                            </div>
                            <div class="mb-3">
                                <label for="fechaSalida" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control" id="fechaSalida" name="fechaSalida" required>
                            </div>


                            <div class="mb-3">
                                <label for="totalAdultos" class="form-label">Número de Adultos</label>
                                <input type="number" class="form-control" id="totalAdultos" name="totalAdultos" min="1"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="totalNinos" class="form-label">Número de Niños</label>
                                <input type="number" class="form-control" id="totalNinos" name="totalNinos" min="0"
                                    max="2" required>
                            </div> <!-- Cierre correcto del div -->
                            <div class="mb-3">
                                <label for="totalPagar" class="form-label">Total a Pagar</label>
                                <input type="text" class="form-control" id="totalPagar" name="totalPagar" readonly>
                            </div> <!-- Cierre correcto del div -->
                            <button type="submit" class="btn btn-primary">Finalizar reserva</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const totalPagarInput = document.getElementById('totalPagar');
                const totalAdultosInput = document.getElementById('totalAdultos');
                const fechaLlegadaInput = document.getElementById('fechaLlegada');
                const fechaSalidaInput = document.getElementById('fechaSalida');

                const precioPorNoche = 300;
                const precioRenta = 1800;
                const comisionPorAdultoExtra = 50;

                // Establece la fecha mínima para los campos de fecha
                const today = new Date().toISOString().split('T')[0];
                fechaLlegadaInput.setAttribute('min', today);
                fechaSalidaInput.setAttribute('min', today);

                function calcularTotal() {
                    const totalAdultos = parseInt(totalAdultosInput.value) || 0;
                    const totalNinos = parseInt(document.getElementById('totalNinos').value) || 0;
                    const fechaLlegada = new Date(fechaLlegadaInput.value);
                    const fechaSalida = new Date(fechaSalidaInput.value);

                    if (fechaLlegada && fechaSalida && fechaSalida > fechaLlegada) {
                        const noches = Math.ceil((fechaSalida - fechaLlegada) / (1000 * 60 * 60 * 24));
                        let total = noches * precioPorNoche;

                        if (noches > 30) {
                            total = precioRenta;
                        }

                        if (totalAdultos > 2) {
                            const adultosExtras = totalAdultos - 2;
                            const comision = adultosExtras * comisionPorAdultoExtra;
                            total += comision;
                            alert(`Se cobrará una comisión de ${comision} pesos por ${adultosExtras} adulto adicional.`);
                        }

                        totalPagarInput.value = total.toFixed(2);
                    }
                }

                totalAdultosInput.addEventListener('input', calcularTotal);
                fechaLlegadaInput.addEventListener('change', calcularTotal);
                fechaSalidaInput.addEventListener('change', calcularTotal);

                // Validar que la fecha de llegada no sea anterior a hoy
                fechaLlegadaInput.addEventListener('change', function () {
                    if (this.value < today) {
                        alert('La fecha de llegada no puede ser una fecha pasada.');
                        this.value = '';
                    }
                    fechaSalidaInput.setAttribute('min', this.value);  // Establece la fecha mínima de salida basada en la llegada
                });

                // Validar que la fecha de salida sea después de la llegada
                fechaSalidaInput.addEventListener('change', function () {
                    if (this.value <= fechaLlegadaInput.value) {
                        alert('La fecha de salida debe ser después de la fecha de llegada.');
                        this.value = '';
                    }
                });
            });
        </script>

</body>

</html>
<?php include("../../temp/footer.php"); ?>