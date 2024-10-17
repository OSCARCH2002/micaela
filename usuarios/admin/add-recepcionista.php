<?php
include("../../database/conexion.php");

function agregarRecepcionista($datos)
{
    global $conexion;
    try {
        $consulta = "INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES (:nombre, :correo, :contrasena, 1)";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':correo', $datos['correo']);
        $contrasenaHashed = password_hash($datos['contrasena'], PASSWORD_BCRYPT);
        $stmt->bindParam(':contrasena', $contrasenaHashed);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al agregar recepcionista: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}

$mensajeExito = "";
$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dar_alta_recepcionista'])) {
    $nuevoRecepcionista = array(
        'nombre' => $_POST['nombre'],
        'correo' => $_POST['correo'],
        'contrasena' => $_POST['contrasena']
    );

    if (!filter_var($nuevoRecepcionista['correo'], FILTER_VALIDATE_EMAIL)) {
        $mensajeError = "El formato del correo electrónico no es válido.";
    } elseif (strlen($nuevoRecepcionista['contrasena']) < 8) {
        $mensajeError = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        if (agregarRecepcionista($nuevoRecepcionista)) {
            $mensajeExito = "Recepcionista agregado exitosamente.";
        } else {
            $mensajeError = "Error al agregar recepcionista.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dar de Alta a Recepcionista</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #191C21;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #4B94BF;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .boton-volver {
            text-align: center;
            margin-top: 60px;
        }

        .container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function mostrarMensaje(mensaje) {
            alert(mensaje);
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Dar de Alta a Recepcionista</h1>
        <?php
        if (!empty($mensajeExito)) {
            echo "<script>mostrarMensaje('$mensajeExito');</script>";
        }
        if (!empty($mensajeError)) {
            echo "<script>mostrarMensaje('$mensajeError');</script>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required minlength="8">
            </div>
            <button type="submit" name="dar_alta_recepcionista" class="btn btn-primary">Dar de Alta</button>
        </form>
    </div>
    <div class="boton-volver">
        <a href="http://localhost/hotel/usuarios/admin/admin.php" class="btn btn-primary">Volver a inicio</a>
    </div>
</body>

</html>