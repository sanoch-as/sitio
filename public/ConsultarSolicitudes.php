<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios
require_once __DIR__ . '/../app/models/Solicitud.php'; // Modelo de Usuarios
require_once __DIR__ . '/../config/session.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}


//Obtener Tipo Solicitud
$tipoSolicitudModel = new TipoSolicitud($conn);
$tipos_solicitud = $tipoSolicitudModel->obtenerTipos();

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

    <form>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card"> <!-- Tarjeta criterios -->
                    <div class="card-header">Listado de Solicitudes</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label for="numcaso" class="">N° Solicitud</label>
                                            <input name="numcaso" id="numcaso" placeholder="Ingrese N° del Caso" type="number" class="mb-2 form-control-sm form-control" min="1">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="selectProceso">TipoSolicitud</label>
                                            <select name="selectProceso" id="selectProceso" class="mb-2 form-control-sm form-control">
                                                <option value="all" selected>Todos</option>
                                                <?php foreach ($tipos_solicitud as $tipo) : ?>
                                                    <option value="<?= $tipo['idTipoSolicitud'] ?>"><?= $tipo['GlosaTipoSolicitud'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group"><label for="FechaDesde" class="">Fecha Desde</label><input name="FechaDesde" id="FechaDesde" type="date" class="mb-2 form-control-sm form-control"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group"><label for="FechaHasta" class="">Fecha Hasta</label><input name="FechaHasta" id="FechaHasta" type="date" class="mb-2 form-control-sm form-control"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="button" onclick="buscar()" class="btn btn-primary btn-sm" style="float:right"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card"> <!-- resultados -->
            <div class="card-header">Resultados</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style=" padding: 10px 10px 10px 10px;">
                            <table class=" display" width="100%" id="tabladetalle">
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
                                <tbody id="FilaTabla2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {

            $('#tabladetalle tbody').on('click', '.btn-detalle', function() {
                let id = $(this).data('id');
                let url = `detalleSolicitud.php?id=${id}`;

                console.log("Botón clickeado, ID:", id); // DEBUG: Verifica si el evento se está ejecutando
                console.log("URL generada:", url); // DEBUG: Verifica la URL generada

                window.open(url, '_blank', 'width=800,height=600'); // Abre en nueva ventana/pestaña
            });



        });

        function buscar() {

            var IdSolicitud = $("#numcaso").val();

            var proceso = $("#selectProceso").children("option:selected").val();
            //var proceso= 'all';
            var fechadesde = $("#FechaDesde").val();
            var fechahasta = $("#FechaHasta").val();
            var url = 'http://localhost/sitio/public/GetCasosConsulta.php?IdSolicitud=' + IdSolicitud + '&FDESDE=' + fechadesde + '&FHASTA=' + fechahasta + '&PROCESO=' + proceso;


            var table = $('#tabladetalle').DataTable({
                "destroy": true,
                "ajax": {
                    "type": "GET",
                    "url": url
                },
                "columns": [{"data": "id"},
                    {"data": "titulo"},
                    {"data": "prioridad"},
                    {"data": "tipo_solicitud"},
                    {"data": "estado"},
                    {
                        title: "Acciones",
                        data: null, // Indica que los datos no vienen del JSON directamente
                        orderable: false, // No se puede ordenar esta columna
                        searchable: false, // No se puede buscar por esta columna
                        render: function(data, type, row) {
                            return `<button class="btn btn-info btn-sm mb-2 mr-2 btn-detalle" data-id="${row.id}">Detalle</button>`;
                            //return `<input type="button" class="btn btn-danger btn-sm mb-2 mr-2 btn-detalle" data-id="${row.id}">Detalle</input>`;
                        }
                    }
                ],
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
                },
            });
        };
    </script>


</body>

</html>