<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Finalmente, destruir la sesión
session_destroy();

// Redireccionar al usuario a la página de inicio de sesión o a donde desees
header("location: http://localhost/hotel/usuarios/index.php");
exit;
?>
