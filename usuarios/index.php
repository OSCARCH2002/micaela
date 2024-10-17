<?php
// Definir variables y valores predeterminados
$nombre_error = $correo_error = $password_error = $confirmar_password_error = $tipo_usuario_error = $correo_duplicado_error = "";
$nombre = $correo = "";

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "567890";
$dbname = "propuesta";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Función para limpiar entradas
function limpiar_entrada($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Verificar si se ha enviado una solicitud AJAX para verificar el correo
if (isset($_POST['check_email'])) {
    $correo = limpiar_entrada($_POST['correo']);

    if (!empty($correo)) {
        // Verificar si el correo ya existe
        $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $correo);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
        $stmt_check->close();
    } else {
        echo json_encode(['exists' => false]);
    }
    exit;
}

// Registro de usuario
if (isset($_POST['registro'])) {
    $nombre = limpiar_entrada($_POST['nombre']);
    $correo = limpiar_entrada($_POST['correo']);
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];
    $tipo_usuario = limpiar_entrada($_POST['tipo_usuario']);

    // Verificar si el correo ya existe
    $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $correo_duplicado_error = "El correo ya está registrado.";
    } elseif ($password !== $confirmar_password) {
        $confirmar_password_error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $password_error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Obtener el ID del rol
        $rol_id = ($tipo_usuario == 'administrador') ? 2 : 1;

        // Insertar el usuario
        $sql = "INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $nombre, $correo, $hashed_password, $rol_id);
            if ($stmt->execute()) {
                echo "<script>alert('¡Registro exitoso!');</script>";
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    }
    $stmt_check->close();
}

// Inicio de sesión
if (isset($_POST['login'])) {
    $correo = limpiar_entrada($_POST['email']);
    $password = $_POST['password'];
    $tipo_usuario = limpiar_entrada($_POST['user_type']);

    // Obtener el ID del rol
    $rol_id = ($tipo_usuario == 'administrador') ? 2 : 1;

    // Consultar la base de datos para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND id_rol = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $correo, $rol_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $hashed_password = $fila['contrasena'];

            // Verificar la contraseña
            if (password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['usuario'] = $fila['nombre'];
                $_SESSION['tipo_usuario'] = $tipo_usuario;
                if ($tipo_usuario == 'administrador') {
                    header("Location: ./admin/admin.php");
                } else {
                    header("Location: ./recepcionista/dasboard.php");
                }
                exit;
            } else {
                echo "<script>alert('Contraseña incorrecta.');</script>";
            }
        } else {
            echo "<script>alert('Usuario no encontrado.');</script>";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro e Inicio de Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .password-container {
            position: relative;
            margin-bottom: 10px; /* Espacio entre campos de contraseña */
        }

        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            font-size: 16px;
        }

        #password-strength {
            font-size: 14px;
            margin-top: 4px;
        }

        .user-type {
            margin-top: 16px;
            margin-bottom: 20px;
        }

        .user-type label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .user-type select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .flip-card__btn {
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #007bff;
            color: #fff;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .flip-card__btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card-switch">
            <label class="switch">
                <input type="checkbox" class="toggle">
                <span class="slider"></span>
                <span class="card-side"></span>
                <div class="flip-card__inner">
                    <div class="flip-card__front">
                        <div class="title">Iniciar Sesión</div>
                        <form class="flip-card__form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <input class="flip-card__input" name="email" placeholder="Email" type="email" minlength="5" maxlength="50" required>
                            <input class="flip-card__input" id="login-password" name="password" placeholder="Contraseña" type="password" minlength="8" maxlength="8" required>
                            <button type="button" class="eye-icon" id="toggle-login-password"><i class="fa fa-eye"></i></button>
                            <div class="user-type">
                                <label for="user-type-login">Selecciona el tipo de usuario:</label>
                                <select id="user-type-login" name="user_type">
                                    <option value="administrador">Administrador</option>
                                    <option value="recepcionista">Recepcionista</option>
                                </select>
                            </div>
                            <button class="flip-card__btn" type="submit" name="login">Entrar</button>
                        </form>
                    </div>
                    <div class="flip-card__back">
                        <div class="title">Registrarse</div>
                        <form class="flip-card__form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return validarFormulario();">
                            <input class="flip-card__input" id="nombre" name="nombre" placeholder="Nombre" type="text" minlength="5" maxlength="15" value="<?php echo htmlspecialchars($nombre); ?>" required>
                            <span class="error-message"><?php echo $nombre_error; ?></span>

                            <input class="flip-card__input" id="correo" name="correo" placeholder="Correo" type="email" minlength="5" maxlength="50" value="<?php echo htmlspecialchars($correo); ?>" required>
                            <span class="error-message" id="correo-error-message">
                                <?php echo !empty($correo_duplicado_error) ? $correo_duplicado_error : ''; ?>
                            </span>

                            <div class="password-container">
                                <input class="flip-card__input" id="password" name="password" placeholder="Contraseña" type="password" minlength="8" maxlength="8" onkeyup="verificarFortalezaContrasena();" required>
                                <button type="button" class="eye-icon" id="toggle-password"><i class="fa fa-eye"></i></button>
                                <span id="password-strength"></span>
                            </div>

                            <div class="password-container">
                                <input class="flip-card__input" id="confirmar_password" name="confirmar_password" placeholder="Confirmar Contraseña" type="password" minlength="8" maxlength="8" required>
                                <button type="button" class="eye-icon" id="toggle-confirm-password"><i class="fa fa-eye"></i></button>
                                <span class="error-message confirmar-password-error"><?php echo $confirmar_password_error; ?></span>
                            </div>

                            <div class="user-type">
                                <select id="tipo_usuario" name="tipo_usuario">
                                    <option value="recepcionista">Recepcionista</option>
                                </select>
                            </div>

                            <button class="flip-card__btn" type="submit" name="registro">Registrar</button>
                        </form>
                    </div>
                </div>
            </label>
        </div>
    </div>

    <script>
        function checkEmailAvailability() {
            var correo = document.getElementById('correo').value;
            var errorMessage = document.getElementById('correo-error-message');

            if (correo) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.exists) {
                            errorMessage.textContent = "El correo ya está registrado.";
                        } else {
                            errorMessage.textContent = "";
                        }
                    }
                };
                xhr.send('check_email=true&correo=' + encodeURIComponent(correo));
            } else {
                errorMessage.textContent = "";
            }
        }

        document.getElementById('correo').addEventListener('input', checkEmailAvailability);

        function togglePasswordVisibility(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId).querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('toggle-password').addEventListener('click', function(event) {
            event.preventDefault(); 
            togglePasswordVisibility('password', 'toggle-password');
        });

        document.getElementById('toggle-confirm-password').addEventListener('click', function(event) {
            event.preventDefault(); 
            togglePasswordVisibility('confirmar_password', 'toggle-confirm-password');
        });

        document.getElementById('toggle-login-password').addEventListener('click', function(event) {
            event.preventDefault(); 
            togglePasswordVisibility('login-password', 'toggle-login-password');
        });

        function verificarFortalezaContrasena() {
            var password = document.getElementById('password').value;
            var strength = document.getElementById('password-strength');
            var strengthMessage = "";

            if (password.length >= 8) {
                strengthMessage = "Contraseña fuerte";
                strength.style.color = "green";  
            }
            else if(password.length > 5){
                strengthMessage = "Contraseña Media ";
                strength.style.color = "orange";
            }
            else if (password.length > 0) {
                strengthMessage = "Contraseña débil";
                strength.style.color = "red";
            } else {
                strengthMessage = ""; // Mensaje vacío si no hay texto
            }
            strength.textContent = strengthMessage;
        }

        function validarFormulario() {
            var password = document.getElementById('password').value;
            var confirmar_password = document.getElementById('confirmar_password').value;
            var confirmar_password_error = document.querySelector('.confirmar-password-error');

            if (password !== confirmar_password) {
                confirmar_password_error.textContent = "Las contraseñas no coinciden.";
                return false;
            }

            verificarFortalezaContrasena();

            return true;
        }
    </script>
</body>
</html>
