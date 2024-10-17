<?php
// Configuración de la conexión
$dsn = 'mysql:host=localhost;dbname=propuesta;charset=utf8';
$usuario = 'root';
$contraseña = '567890';

// Intentar establecer la conexión
try {
    $conexion = new PDO($dsn, $usuario, $contraseña);
    // Establecer el modo de error a excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si hay un error en la conexión, mostrarlo
    echo 'Error de conexión: ' . $e->getMessage();
    exit;
}

// Función para insertar una reserva en la base de datos
function insertarReserva($nombre, $apellidos, $telefono, $fechaLlegada, $fechaSalida, $tipoHabitacion, $totalAdultos, $totalNinos, $totalPagar, $habitacionId)
{
    global $conexion;

    $stmt = $conexion->prepare("INSERT INTO reservas (nombre, apellidos, telefono, fecha_llegada, fecha_salida, tipo_habitacion, total_adultos, total_ninos, total_pagar, id_habitacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$nombre, $apellidos, $telefono, $fechaLlegada, $fechaSalida, $tipoHabitacion, $totalAdultos, $totalNinos, $totalPagar, $habitacionId]);
        return true;
    } catch (PDOException $e) {
        // Si hay un error al insertar la reserva, mostrarlo
        echo 'Error al insertar reserva: ' . $e->getMessage();
        return false;
    }
}
