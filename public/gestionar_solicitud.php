<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios
require_once __DIR__ . '/../app/models/Solicitud.php'; // Modelo de Usuarios
require_once __DIR__ . '/../config/session.php'; 


// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}


//Obtener Listado de Solicitudes
$ListadoSolicitudesUsuarioModel = new Solicitud($conn);
$ListadoSolicitudes = $ListadoSolicitudesUsuarioModel->ObtenerListadoSolicitudesUsuarios($_SESSION['usuario_id']);

/* echo "<pre>";
print_r($ListadoSolicitudes);
echo "</pre>";
exit(); */
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestionar Solicitudes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">  
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>    
    <link href="./assets/css/main.css" rel="stylesheet">
    <link href="./assets/css/sanoch.css" rel="stylesheet">
     <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap4.css" rel="stylesheet"> 
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap4.js"></script>


</head>

<body>
<div class="app-container  app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <form>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">Listado de Solicitudes
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                        <table id="TablaSolicitudes" class="display">
                            <thead>
                                <tr>
                                    <th>ID solicitud</th>
                                    <th>Título</th>
                                    <th>Prioridad</th>
                                    <th>Tipo Solicitud</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ListadoSolicitudes as $solicitud) : ?>
                                    <tr>
                                        <td><?= $solicitud['id'] ?></td>
                                        <td><?= $solicitud['titulo'] ?></td>
                                        <td><?= $solicitud['prioridad'] ?></td>
                                        <td><?= $solicitud['GlosaTipoSolicitud'] ?></td>
                                        <td><?= $solicitud['estado'] ?></td>
                                        <td>                                        
                                         <a href="trabajo_solicitud.php?id=<?= $solicitud['id'] ?>" class="btn btn-sm btn-info" role="button">Editar</a>    
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
</div>
    <script>
        $(document).ready(function() {
            $('#TablaSolicitudes').DataTable({
                "paging": true, // Habilitar paginación
                "searching": true, // Habilitar búsqueda
                "ordering": true, // Habilitar ordenamiento de columnas
                "info": true, // Mostrar información de registros
                "lengthMenu": [10, 25, 50, 100], // Opciones de registros por página
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "emptyTable": 'Sin registros encontrados',
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ registros en total)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
</body>

</html>