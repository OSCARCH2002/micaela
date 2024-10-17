<?php
include("../../database/conexion.php");

function obtenerRecepcionistas()
{
    global $conexion;
    try {
        $consulta = "SELECT * FROM usuarios WHERE id_rol = 1";
        $stmt = $conexion->query($consulta);
        $recepcionistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $recepcionistas;
    } catch (PDOException $e) {
        echo 'Error al obtener recepcionistas: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return array();
    }
}

function eliminarRecepcionista($id)
{
    global $conexion;
    try {
        $consulta = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al eliminar recepcionista: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}


function actualizarRecepcionista($id, $nombre, $correo, $contrasena)
{
    global $conexion;
    try {
        $consulta = "UPDATE usuarios SET nombre = :nombre, correo = :correo, contrasena = :contrasena WHERE id = :id";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo 'Error al actualizar recepcionista: ' . $e->getMessage() . ' en la línea ' . $e->getLine();
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_recepcionista"])) {
    $id = $_POST["id"];
    if (eliminarRecepcionista($id)) {
        echo "<script>alert('Recepcionista eliminado correctamente');</script>";

        $recepcionistas = obtenerRecepcionistas();
    } else {
        echo "<script>alert('Error al eliminar recepcionista');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar_recepcionista"])) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    if (actualizarRecepcionista($id, $nombre, $correo, $contrasena)) {
        echo "<script>alert('Recepcionista actualizado correctamente');</script>";
        $recepcionistas = obtenerRecepcionistas();
    } else {
        echo "<script>alert('Error al actualizar recepcionista');</script>";
    }
}

$recepcionistas = obtenerRecepcionistas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Recepcionistas</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }

        body {
            background-color: #191C21;
        }

        h2 {
            margin-bottom: 30px;
            color: #fff;

        }

        .modal-content {
            padding: 100px;
            background-color: #0C0E10;

            color: #fff;

        }

        .modal-dialog {
            max-width: 80%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-group {
            margin-right: 10px;
        }

        .btn {
            color: #fff !important;
        }

        table td,
        table th {
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Recepcionistas Registrados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Contraseña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recepcionistas as $recepcionista) : ?>
                    <tr>
                        <td><?php echo $recepcionista['id']; ?></td>
                        <td><?php echo $recepcionista['nombre']; ?></td>
                        <td><?php echo $recepcionista['correo']; ?></td>
                        <td><?php echo $recepcionista['contrasena']; ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $recepcionista['id']; ?>">
                                <button type="submit" name="eliminar_recepcionista" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este recepcionista?')">Eliminar</button>
                            </form>
                            <button type="button" class="btn btn-primary editar-recepcionista" data-toggle="modal" data-target="#editarRecepcionistaModal" data-id="<?php echo $recepcionista['id']; ?>" data-nombre="<?php echo $recepcionista['nombre']; ?>" data-correo="<?php echo $recepcionista['correo']; ?>" data-contrasena="<?php echo $recepcionista['contrasena']; ?>">Editar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editarRecepcionistaModal" tabindex="-1" role="dialog" aria-labelledby="editarRecepcionistaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarRecepcionistaModalLabel">Editar Recepcionista</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarRecepcionistaForm" method="post">
                        <input type="hidden" name="id" id="editId">
                        <div class="form-group">
                            <label for="editNombre">Nombre:</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre">
                        </div>
                        <div class="form-group">
                            <label for="editCorreo">Correo:</label>
                            <input type="email" class="form-control" id="editCorreo" name="correo">
                        </div>
                        <div class="form-group">
                            <label for="editContrasena">Contraseña:</label>
                            <input type="text" class="form-control" id="editContrasena" name="contrasena">
                        </div>
                        <button type="submit" class="btn btn-primary" name="editar_recepcionista">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.editar-recepcionista').click(function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var correo = $(this).data('correo');
                var contrasena = $(this).data('contrasena');

                $('#editId').val(id);
                $('#editNombre').val(nombre);
                $('#editCorreo').val(correo);
                $('#editContrasena').val(contrasena);
            });
        });
    </script>
</body>

</html>