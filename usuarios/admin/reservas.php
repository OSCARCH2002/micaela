<?php
include("../../database/conexion.php");

function obtenerTodasLasReservas()
{
   global $conexion;
   try {
      $consulta = "SELECT * FROM reservas";
      $stmt = $conexion->query($consulta);
      $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $reservas;
   } catch (PDOException $e) {
      echo 'Error al obtener reservas: ' . $e->getMessage();
      return array();
   }
}

function eliminarReserva($id)
{
   global $conexion;
   try {
      $consulta = "DELETE FROM reservas WHERE id = :id";
      $stmt = $conexion->prepare($consulta);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return true;
   } catch (PDOException $e) {
      echo 'Error al eliminar reserva: ' . $e->getMessage();
      return false;
   }
}

function editarReserva($datos)
{
   global $conexion;
   try {
      $consulta = "UPDATE reservas SET id_habitacion = :id_habitacion, nombre = :nombre, apellidos = :apellidos, telefono = :telefono, fecha_llegada = :fecha_llegada, fecha_salida = :fecha_salida, tipo_habitacion = :tipo_habitacion, total_adultos = :total_adultos, total_ninos = :total_ninos, total_pagar = :total_pagar WHERE id = :id";
      $stmt = $conexion->prepare($consulta);
      $stmt->bindParam(':id', $datos['id_reserva'], PDO::PARAM_INT);
      $stmt->bindParam(':id_habitacion', $datos['id_habitacion']);
      $stmt->bindParam(':nombre', $datos['nombre']);
      $stmt->bindParam(':apellidos', $datos['apellidos']);
      $stmt->bindParam(':telefono', $datos['telefono']);
      $stmt->bindParam(':fecha_llegada', $datos['fecha_llegada']);
      $stmt->bindParam(':fecha_salida', $datos['fecha_salida']);
      $stmt->bindParam(':tipo_habitacion', $datos['tipo_habitacion']);
      $stmt->bindParam(':total_adultos', $datos['total_adultos']);
      $stmt->bindParam(':total_ninos', $datos['total_ninos']);
      $stmt->bindParam(':total_pagar', $datos['total_pagar']);
      $stmt->execute();
      return true;
   } catch (PDOException $e) {
      echo 'Error al editar reserva: ' . $e->getMessage();
      return false;
   }
}

if (isset($_POST['editar_reserva'])) {
   $datosEditarReserva = array(
      'id_reserva' => $_POST['id_reserva'],
      'id_habitacion' => $_POST['id_habitacion'],
      'nombre' => $_POST['nombre'],
      'apellidos' => $_POST['apellidos'],
      'telefono' => $_POST['telefono'],
      'fecha_llegada' => $_POST['fecha_llegada'],
      'fecha_salida' => $_POST['fecha_salida'],
      'tipo_habitacion' => $_POST['tipo_habitacion'],
      'total_adultos' => $_POST['total_adultos'],
      'total_ninos' => $_POST['total_ninos'],
      'total_pagar' => $_POST['total_pagar']
   );
   editarReserva($datosEditarReserva);
}

if (isset($_POST['eliminar_reserva'])) {
   $idReserva = $_POST['id_reserva'];
   eliminarReserva($idReserva);
}

// Paginación
$registrosPorPagina = 10;
$totalRegistros = count(obtenerTodasLasReservas());
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$indiceInicial = ($paginaActual - 1) * $registrosPorPagina;
$reservas = obtenerTodasLasReservas();
$reservasPaginadas = array_slice($reservas, $indiceInicial, $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Recepcionista</title>
   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <style>
      .table-spacing tbody tr {
         margin-bottom: 10px;
      }

      .btn-group .btn {
         margin-right: 15px;
      }

      .titulo {
         color: darkblue;
         font-size: 25px;
         font-weight: bold;
         margin-top: 10px;
         margin-left: 31px;
      }

      #inputBuscar {
         width: 150px;
         float: right;
         margin-top: 10px;
      }

      .fondo {
         background-image: url('http://localhost/hotel/images/fondo.jpeg');
         background-size: cover;
         background-position: center;
         background-repeat: no-repeat;
         height: 100vh;
         width: 100%;
         position: relative;
      }
      .boton-volver {
            text-align: center;
            margin-top: 60px;
        }

      .fondo::after {
         content: "";
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(255, 255, 255, 0.8);
         opacity: 0.4;
         z-index: -1;
      }

      .table-background {
         position: relative;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .table-background::before {
         content: "";
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: white;
         opacity: 0.8;
         z-index: -1;
      }
   </style>
</head>

<body class="fondo">
   <div class="container">
      <div class="container">
         <br>
         <h1 class="titulo">Mis Reservas</h1>
         <div class="mb-2">
            <input type="text" id="inputBuscar" class="form-control form-control-sm" placeholder="Buscar" style="width: 150px;" autocomplete="off">
            <div id="sinResultados" class="alert alert-info" role="alert" style="display: none;">
               Sin resultados.
            </div>
         </div>

         <div class="table-responsive table-background" id="tablaReservas">
            <table class="table table-striped table-sm table-spacing">
               <thead>
                  <tr>
                     <th>ID</th>
                     <th>ID Habitación</th>
                     <th>Nombre</th>
                     <th>Apellidos</th>
                     <th>Teléfono</th>
                     <th>Fecha Llegada</th>
                     <th>Fecha Salida</th>
                     <th>Tipo Habitación</th>
                     <th>Adultos</th>
                     <th>Niños</th>
                     <th>Total a Pagar</th>
                     <th>Acciones</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($reservasPaginadas as $reserva) { ?>
                     <tr>
                        <td><?php echo $reserva['id']; ?></td>
                        <td><?php echo $reserva['id_habitacion']; ?></td>
                        <td><?php echo $reserva['nombre']; ?></td>
                        <td><?php echo $reserva['apellidos']; ?></td>
                        <td><?php echo $reserva['telefono']; ?></td>
                        <td><?php echo $reserva['fecha_llegada']; ?></td>
                        <td><?php echo $reserva['fecha_salida']; ?></td>
                        <td><?php echo $reserva['tipo_habitacion']; ?></td>
                        <td><?php echo $reserva['total_adultos']; ?></td>
                        <td><?php echo $reserva['total_ninos']; ?></td>
                        <td><?php echo $reserva['total_pagar']; ?></td>
                        <td>
                           <div class="btn-group" role="group">
                              <form method="POST" style="display: inline;">
                                 <input type="hidden" name="id_reserva" value="<?php echo $reserva['id']; ?>">
                                 <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEditarReserva<?php echo $reserva['id']; ?>">Editar</button>
                              </form>
                              <form method="POST" style="display: inline;">
                                 <input type="hidden" name="id_reserva" value="<?php echo $reserva['id']; ?>">
                                 <button type="submit" name="eliminar_reserva" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar</button>
                              </form>
                           </div>
                        </td>
                     </tr>

                     <!-- Modal para editar reserva -->
                     <div class="modal fade" id="modalEditarReserva<?php echo $reserva['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditarReservaLabel<?php echo $reserva['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="modalEditarReservaLabel<?php echo $reserva['id']; ?>">Editar Reserva</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <form method="POST" action="reservas.php">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id']; ?>">
                                    <div class="form-group">
                                       <label for="id_habitacion">ID Habitación</label>
                                       <input type="text" class="form-control" name="id_habitacion" value="<?php echo $reserva['id_habitacion']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="nombre">Nombre</label>
                                       <input type="text" class="form-control" name="nombre" value="<?php echo $reserva['nombre']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="apellidos">Apellidos</label>
                                       <input type="text" class="form-control" name="apellidos" value="<?php echo $reserva['apellidos']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="telefono">Teléfono</label>
                                       <input type="tel" class="form-control" name="telefono" maxlength="10" minlength="10" pattern="\d{10}" value="<?php echo $reserva['telefono']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="fecha_llegada">Fecha Llegada</label>
                                       <input type="date" class="form-control" name="fecha_llegada" id="fecha_llegada_<?php echo $reserva['id']; ?>" value="<?php echo $reserva['fecha_llegada']; ?>" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                       <label for="fecha_salida">Fecha Salida</label>
                                       <input type="date" class="form-control" name="fecha_salida" id="fecha_salida_<?php echo $reserva['id']; ?>" value="<?php echo $reserva['fecha_salida']; ?>" required min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                       <label for="tipo_habitacion">Tipo de Habitación</label>
                                       <input type="text" class="form-control" name="tipo_habitacion" id="tipo_habitacion_<?php echo $reserva['id']; ?>" value="<?php echo $reserva['tipo_habitacion']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                       <label for="total_adultos">Total de Adultos</label>
                                       <input type="number" class="form-control" name="total_adultos" id="total_adultos_<?php echo $reserva['id']; ?>" value="<?php echo $reserva['total_adultos']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="total_ninos">Total de Niños</label>
                                       <input type="number" class="form-control" name="total_ninos" min="1" max="2" value="<?php echo $reserva['total_ninos']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                       <label for="total_noches">Número de Noches</label>
                                       <input type="number" class="form-control" name="total_noches" id="total_noches_<?php echo $reserva['id']; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                    <label for="total_pagar">Total a Pagar</label>
                                       <input type="text" class="form-control" name="total_pagar" id="total_pagar_<?php echo $reserva['id']; ?>" value="<?php echo $reserva['total_pagar']; ?>" readonly>
                                    </div>
                                    <button type="submit" name="editar_reserva" class="btn btn-primary">Guardar Cambios</button>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  <?php } ?>
               </tbody>
            </table>
         </div>
         <nav>
            <ul class="pagination justify-content-center">
               <?php if ($paginaActual > 1) : ?>
                  <li class="page-item">
                     <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                     </a>
                  </li>
               <?php endif; ?>
               <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                  <li class="page-item <?php if ($i == $paginaActual) echo 'active'; ?>">
                     <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                  </li>
               <?php endfor; ?>
               <?php if ($paginaActual < $totalPaginas) : ?>
                  <li class="page-item">
                     <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                     </a>
                  </li>
               <?php endif; ?>
            </ul>
         </nav>
      </div>
      <div class="boton-volver">
            <a href="http://localhost/hotel/usuarios/admin/admin.php" class="btn btn-primary">Volver a inicio</a>
        </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script>
      $(document).ready(function() {
         $('#inputBuscar').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablaReservas tbody tr').filter(function() {
               $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

            if ($('#tablaReservas tbody tr:visible').length === 0) {
               $('#sinResultados').show();
            } else {
               $('#sinResultados').hide();
            }
         });

         $('input[type="date"]').change(function() {
            var id = $(this).closest('tr').find('input[type="hidden"]').val();
            calculateDates(id);
         });

         $('input[name="total_adultos"]').change(function() {
            var id = $(this).closest('tr').find('input[type="hidden"]').val();
            calculateTotal(id);
         });

         function calculateDates(id) {
            var fechaLlegada = new Date($('#fecha_llegada_' + id).val());
            var fechaSalida = new Date($('#fecha_salida_' + id).val());
            var timeDiff = Math.abs(fechaSalida.getTime() - fechaLlegada.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            $('#total_noches_' + id).val(diffDays);

            if (diffDays > 30) {
               $('#tipo_habitacion_' + id).val('renta');
            } else {
               $('#tipo_habitacion_' + id).val('noches');
            }

            calculateTotal(id);
         }

         function calculateTotal(id) {
            var noches = parseInt($('#total_noches_' + id).val());
            var adultos = parseInt($('#total_adultos_' + id).val());
            var comision = adultos > 2 ? (adultos - 2) * 50 : 0;
            var total;

            if ($('#tipo_habitacion_' + id).val() === 'renta') {
               total = noches * 1800 + comision;
            } else {
               total = noches * 300 + comision;
            }

            $('#total_pagar_' + id).val(total.toFixed(2));
         }

         // Inicializar las fechas y totales al cargar el modal
         $('.modal').on('shown.bs.modal', function() {
            var id = $(this).find('input[type="hidden"]').val();
            calculateDates(id);
         });
      });
   </script>
</body>

</html>

