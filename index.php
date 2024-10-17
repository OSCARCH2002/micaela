<?php include("./temp/header.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Micaela</title>
    <link rel="stylesheet" href="./css/style.css">
   
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
</head>

<body>
    <script>
        window.addEventListener('mouseover', initLandbot, {
            once: true
        });
        window.addEventListener('touchstart', initLandbot, {
            once: true
        });
        var myLandbot;

        function initLandbot() {
            if (!myLandbot) {
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
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
    <section class="banner">
        <div class="content-banner">
            <h2>Hotel <br> Quinta Micaela</h2>
            <a href="./pages/reservas/index.php" class="btn btn-primary btn-lg">Reservar Ahora</a>
        </div>
    </section>


    <section class="container container-features">
        <div class="card-feature">
            <i class="fas fa-wifi"></i>
            <div class="feature-content">
                <span>WIFI</span>
                <p>Para que tu estadía sea más placentera</p>
            </div>
        </div>
        <div class="card-feature">
            <i class="fas fa-tv"></i>
            <div class="feature-content">
                <span>TV</span>
                <p>Contamos con TV por cable</p>
            </div>
        </div>
        <div class="card-feature">
            <i class="fas fa-car"></i>
            <div class="feature-content">
                <span>Estacionamiento</span>
                <p>Amplio y seguro dentro del hotel</p>
            </div>
        </div>
        <div class="card-feature">
            <i class="fas fa-tint"></i>
            <div class="feature-content">
                <span>Agua Caliente</span>
                <p>Para relajarte</p>
            </div>
        </div>
    </section>


    <section class="krystal-beach-section">
        <img src="./images/alberca - copia.jpeg" alt="Alberca">
        <div class="krystal-beach-content">
            <h2>HOTEL QUINTA MICAELA</h2>
            <p>¡UN SERVICIO DE CALIDAD!</p>
            <p>Descubre el confort y la hospitalidad de primera en el Hotel Quinta Micaela. Ubicado en el corazón de San Luis Acatlán, nuestras elegantes habitaciones
                y nuestro salón de eventos nos hace tu mejor opción. Nuestro dedicado equipo está listo para hacer de tu
                estancia una experiencia inolvidable. ¡Ven y disfruta de lo mejor de San Luis Acatlán con nosotros!
            </p>
        </div>
    </section>

    <div class="info-container">
        <div class="info">
            <h1>NUESTRA UBICACIÓN</h1>
            <p>¿Buscas una escapada inolvidable en San Luis Acatlán? El Hotel Quinta Micaela
                te espera para ofrecerte una experiencia única. Ubicado estratégicamente en el
                corazón de la ciudad, estamos a solo unos pasos de las principales atracciones
                locales, como las aguas termales, la capilla y la gastronomia local.
                Desde nuestro hotel, podrás explorar fácilmente los encantos de San
                Luis Acatlán y sus alrededores, con todo lo que necesitas a tu alcance. </p>
            <ul>
                <li>Plaza Principal de San Luis Acatlán</li>
                <li>Iglesia de San Luis Rey</li>
                <li>Mercado Municipal</li>
            </ul>
            <p>Playa Larga, 41600 San Luis Acatlán, Gro.</p>
            <p>Email: <a href="mailto:QMicaela01@gmail.com">QMicaela01@gmail.com</a></p>
            <p>Teléfono: <a href="tel:+527411136523">+ 52 741-113-6523</a></p>
            <p><a href="https://www.google.com/maps/place/Hotel+Quinta+Micaela/@16.8121465,-98.7350555,17z/data=!4m6!3m5!1s0x85c9b9ab0955ae1b:0xb70f095c77b1917b!8m2!3d16.8121414!4d-98.7324752!16s%2Fg%2F11g197z7s_?entry=ttu" target="_blank">VER UBICACIÓN</a></p>
            <input type="text" id="userLocation" placeholder="Ingresa tu ubicacion">
            <button onclick="findRoute()">Cómo llegar</button>
            <div id="suggestions"></div>
        </div>
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>

    <main class="main-content">
        <section class="gallery">
            <img src="./images/agua.jpeg" alt="Gallery Img1" class="gallery-img-1" />
            <img src="./images/alberca.jpeg" alt="Gallery Img2" class="gallery-img-2" />
            <img src="./images/capilla.jpeg" alt="Gallery Img3" class="gallery-img-3" />
            <img src="./images/fondo1.jpeg" alt="Gallery Img4" class="gallery-img-4" />
            <img src="./images/rio.jpeg" alt="Gallery Img5" class="gallery-img-5" />
        </section>

        <section class="container blogs my-5">
            <h1 class="heading-1 text-center mb-4">OFRECEMOS LOS SIGUIENTES SERVICIOS</h1>
            <div class="row justify-content-center g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-blog">
                        <a href="./pages/reservas/index.php" style="text-decoration: none;">
                            <img src="./images/huesped.jpeg" class="card-img-top" alt="Imagen Blog 1">
                            <div class="card-body">
                                <h2 class="card-title">HOSPEDAJE</h2>
                                <p class="card-text">
                                    Ofrecemos cuartos con excelente comodidad
                                </p>
                            </div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h2>HOSPEDAJE</h2>
                                    <p>Ofrecemos cuartos con excelente comodidad</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-blog">
                        <a href="./pages/evento/index.php" style="text-decoration: none;">
                            <img src="./images/EventoXD.jpeg" class="card-img-top" alt="Imagen Blog 3">
                            <div class="card-body">
                                <h3 class="card-title">EVENTOS FAMILIARES</h3>
                                <p class="card-text">
                                    ¿Quieres festejar tu cumpleaños?, y... ¿no sabes dónde?, en Quinta Micaela contamos con un módulo de eventos.
                                </p>
                            </div>
                            <div class="overlay">
                                <div class="overlay-content">
                                    <h3>EVENTOS FAMILIARES</h3>
                                    <p>¿Quieres festejar tu cumpleaños?, y... ¿no sabes dónde?, en Quinta Micaela contamos con un módulo de eventos.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </section>



    </main>


    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([16.8121414, -98.7324752], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var routeLayer;

        var hotelMarker = L.marker([16.8121414, -98.7324752]).addTo(map)
            .bindPopup('Hotel Quinta Micaela.')
            .openPopup();

        function findRoute() {
            var userLocation = document.getElementById('userLocation').value;
            if (userLocation.trim() === "") {
                alert("Por favor ingresa tu ubicación.");
                return;
            }

            if (map.hasLayer(routeLayer)) {
                map.removeLayer(routeLayer);
            }

            fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + userLocation)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var userLat = parseFloat(data[0].lat);
                        var userLon = parseFloat(data[0].lon);

                        fetch("http://router.project-osrm.org/route/v1/driving/" + userLon + "," + userLat + ";" + "-98.7324752" + "," + "16.8121414" + "?overview=full&geometries=geojson")
                            .then(response => response.json())
                            .then(data => {
                                var route = L.geoJSON(data.routes[0].geometry, {
                                    color: 'red',
                                    weight: 5,
                                    opacity: 0.7
                                }).addTo(map);
                                map.fitBounds(route.getBounds());
                                routeLayer = route;
                            });
                    } else {
                        alert("No se encontraron resultados para la ubicación ingresada.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('userLocation').addEventListener('input', function() {
            var inputValue = this.value;
            if (inputValue.trim() !== "") {
                fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + inputValue)
                    .then(response => response.json())
                    .then(data => {
                        var suggestions = document.getElementById('suggestions');
                        suggestions.innerHTML = "";
                        data.forEach(function(item) {
                            var suggestionItem = document.createElement('div');
                            suggestionItem.textContent = item.display_name;
                            suggestionItem.classList.add('suggestion-item');
                            suggestionItem.addEventListener('click', function() {
                                document.getElementById('userLocation').value = item.display_name;
                                suggestions.innerHTML = "";
                            });
                            suggestions.appendChild(suggestionItem);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                document.getElementById('suggestions').innerHTML = "";
            }
        });
        // Deshabilitar scroll en el mapa al cargar la página
        document.getElementById('map').classList.add('map-scroll-disable');

        // Habilitar el scroll cuando el usuario hace clic en el mapa
        document.getElementById('map').addEventListener('click', function() {
            this.classList.remove('map-scroll-disable');
        });

        // Deshabilitar el scroll nuevamente cuando el usuario haga scroll en cualquier otra parte de la página
        document.addEventListener('scroll', function() {
            document.getElementById('map').classList.add('map-scroll-disable');
        });
    </script>
</body>

</html>
<?php include("./temp/footer.php"); ?>