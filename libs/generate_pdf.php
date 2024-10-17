<?php
require('../libs/fpdf.php');
include("../database/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $reservaId = $_GET['reservaId'];

    $sql = "SELECT r.id, r.id_habitacion, c.nombre, c.apellidos, c.telefono, r.fecha_llegada, r.fecha_salida, r.total_adultos, r.total_ninos, r.total_pagar
            FROM reservas r
            JOIN cliente c ON r.id_cliente = c.id
            WHERE r.id = :reservaId";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':reservaId', $reservaId);
    $stmt->execute();
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reserva) {
        class PDF extends FPDF
        {
            // Cabecera del PDF
            function Header()
            {
                $this->Image('./logo.png', 10, 10, 30); // Logo
                $this->SetFont('Arial', 'B', 20);
                $this->Cell(0, 10, 'Quinta Micaela', 0, 1, 'C');
                
                $this->SetFont('Arial', '', 10);
                $this->Cell(0, 10, 'Playa Larga, 41600 San Luis Acatlan, Gro.', 0, 1, 'R');
                
                $this->Ln(10);
                $this->SetFont('Arial', 'B', 15);
                $this->Cell(0, 10, 'Detalles de la Reserva', 0, 1, 'C');
                $this->Ln(10);
            }

            // Pie de página
            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }

            // Función para mostrar los detalles en forma de lista
            function DetallesReserva($reserva)
            {
                $this->SetFont('Arial', '', 12);
                $this->Cell(50, 10, utf8_decode('Número habitación: '), 0, 0, 'L');
                $this->Cell(0, 10, $reserva["id_habitacion"], 0, 1, 'L');

                $this->Cell(50, 10, 'Nombre:', 0, 0, 'L');
                $this->Cell(0, 10, utf8_decode($reserva["nombre"]), 0, 1, 'L');

                $this->Cell(50, 10, 'Apellidos:', 0, 0, 'L');
                $this->Cell(0, 10, utf8_decode($reserva["apellidos"]), 0, 1, 'L');

                $this->Cell(50, 10, utf8_decode('Fecha de Llegada:'), 0, 0, 'L');
                $this->Cell(0, 10, $reserva["fecha_llegada"], 0, 1, 'L');

                $this->Cell(50, 10, utf8_decode('Fecha de Salida:'), 0, 0, 'L');
                $this->Cell(0, 10, $reserva["fecha_salida"], 0, 1, 'L');

                $this->Cell(50, 10, utf8_decode('Total Adultos:'), 0, 0, 'L');
                $this->Cell(0, 10, $reserva["total_adultos"], 0, 1, 'L');

                $this->Cell(50, 10, utf8_decode('Total Niños:'), 0, 0, 'L');
                $this->Cell(0, 10, $reserva["total_ninos"], 0, 1, 'L');

                $this->Cell(50, 10, 'Total a Pagar:', 0, 0, 'L');
                $this->Cell(0, 10, '$' . number_format($reserva["total_pagar"], 2) . ' MXN', 0, 1, 'L');
            }
        }

        // Crea el PDF en orientación vertical
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Muestra los detalles de la reserva en forma de lista
        $pdf->DetallesReserva($reserva);

        // Limpia el buffer y envía el archivo PDF
        ob_clean();
        $pdf->Output('D', 'Reserva_' . $reserva["id"] . '.pdf');
    } else {
        echo "Reserva no encontrada.";
    }
}
