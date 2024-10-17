<?php
include("../../database/conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $consulta = "SELECT * FROM reservas WHERE id = $id";
        $stmt = $conexion->query($consulta);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <!-- Formulario para modificar reserva -->
        <form>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $reserva['cliente_nombre']; ?>"><br><br>
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo $reserva['cliente_apellidos']; ?>"><br><br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $reserva['cliente_telefono']; ?>"><br><br>
            <label for="fecha_llegada">Fecha de Llegada:</label>
            <input type="date" id="fecha_llegada" name="fecha_llegada" value="<?php echo $reserva['fecha_llegada']; ?>"><br><br>
            <label for="fecha_salida">Fecha de Salida:</label>
            <input type="date" id="fecha_salida" name="fecha_salida" value="<?php echo $reserva['fecha_salida']; ?>"><br><br>
            <label for="total_adultos">Total de Adultos:</label>
            <input type="number" id="total_adultos" name="total_adultos" value="<?php echo $reserva['total_adultos']; ?>"><br><br>
            <label for="total_ninos">Total de Niños:</label>
            < input type="number" id="total_ninos" name="total_ninos" value="<?php echo $reserva['total_ninos']; ?>"><br><br>
            <label for="total_pagar">Total a Pagar:</label>
            <input type="number" id="total_pagar" name="total_pagar" value="<?php echo $reserva['total_pagar']; ?>"><br><br>
            <button type="submit">Modificar</button>
        </form>
        <?php
    } catch (PDOException $e) {
        echo 'Error al cargar la reserva: ' . $e->getMessage();
    }
}
?>