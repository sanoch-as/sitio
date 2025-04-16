<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios
require_once __DIR__ . '/../app/models/Solicitud.php'; // Modelo de Usuarios
require_once __DIR__ . '/../app/models/Dashboard.php'; // Modelo Dashboard
require_once __DIR__ . '/../config/session.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}

//Obtener Listado de Solicitudes Dashboard Tarjetas
$ListadoSolicitudesTarjetasDashboardModel = new Dashboard($conn);
$ListadoSolicitudesTarjetasDB = $ListadoSolicitudesTarjetasDashboardModel->ObtenerTarjetas($_SESSION['usuario_id']);



//Obtener Listado de Solicitudes Dashboard prioridad
$ListadoSolicitudesDashboardModel = new Dashboard($conn);
$ListadoSolicitudesDB = $ListadoSolicitudesDashboardModel->ObtenerCantidadPrioridadSolicitudesDB($_SESSION['usuario_id']);
$datosSolicitudesJSON = json_encode($ListadoSolicitudesDB, JSON_UNESCAPED_UNICODE);

//Obtener Listado de Solicitudes Dashboard Tipo Solicitud
$ListadoTipoSolicitudesDashboardModel = new Dashboard($conn);
$ListadoTipoSolicitudesDB = $ListadoTipoSolicitudesDashboardModel->ObtenerCantidadTipoSolicitudesDB($_SESSION['usuario_id']);
$datosTipoSolicitudesJSON = json_encode($ListadoTipoSolicitudesDB, JSON_UNESCAPED_UNICODE);




   /* echo "<pre>";
print_r($ListadoSolicitudesTarjetasDB);
echo "</pre>";
exit();      */

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dashboard</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Huge selection of charts created with the React ChartJS Plugin">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="./assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap4.css" rel="stylesheet">

</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-main" style="padding-top:15px">
            <div class="app-main__outer" style="padding-left: 15px; padding-right:15px; ">
                <div class="tab-content">

                    <div class="row">
                        <div class="col-lg-6 col-xl-4">
                            <div class="card mb-3 widget-content bg-night-sky text-white">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading" style="opacity:100; ">Solicitudes Asignadas</div>
                                        <div class="widget-subheading">Pendientes de gestionar</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers "><span> <?php echo $ListadoSolicitudesTarjetasDB[0]['cantidad'] ?> </span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card mb-3 widget-content bg-grow-early text-white">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading" style="opacity:100;">Recepcionadas Hoy</div>
                                        <div class="widget-subheading">Derivadas para gestionar</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers "><span><?php echo $ListadoSolicitudesTarjetasDB[0]['solicitudesHoy'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card mb-3 widget-content bg-sunny-morning text-white">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading" style="opacity:100;">Solicitudes creadas</div>
                                        <div class="widget-subheading">Total creadas por ti</div>
                                    </div>
                                    <div class="widget-content-right">
                                        <div class="widget-numbers "><span><?php echo $ListadoSolicitudesTarjetasDB[0]['solicitudesUsuarioCreador'] ?></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title">Prioridad</h5>
                                    <canvas id="chartEstados"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title">Tipo Solicitud</h5>
                                    <canvas id="chartTipoSolicitud"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title">Solicitudes</h5>
                                    <div class="table-responsive">
                                        <table id="TablaSolicitudes" class="display table">
                                            <thead>
                                                <tr>
                                                    <th>ID solicitud</th>
                                                    <th>Título</th>
                                                    <th>Fecha Solicitud</th>
                                                    <th>Prioridad</th>
                                                    <th>Tipo Solicitud</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript" src="./assets/scripts/main.js"></script>

        <script>
            // Obtener los datos de PHP convertidos a JSON
            const datosSolicitudes = <?php echo $datosSolicitudesJSON; ?>;

            // Extraer los nombres de las tareas y las cantidades
            const etiquetas = datosSolicitudes.map(item => item.prioridad);
            const cantidades = datosSolicitudes.map(item => item.cantidad);

            const detallesSolicitudes = {};

            if (Array.isArray(datosSolicitudes)) {

                datosSolicitudes.forEach(item => {
                    if (item && item.detalle) { // Verifica que 'detalle' existe                        
                        detallesSolicitudes[item.prioridad] = item.detalle;
                    }
                });

            } else {
                console.error("Error: datosSolicitudes no es un array", datosSolicitudes);
            }


            // Gráfico 1: Casos por prioridad
            const ctx1 = document.getElementById('chartEstados').getContext('2d');
            const myChart1 = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: etiquetas,
                    datasets: [{
                        label: 'Prioridad',
                        data: cantidades,                        
                        backgroundColor: '#2a5298', 
                        borderColor: '#1e3c72',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0]._index; // Índice de la barra seleccionada                       
                            const prioridadSeleccionada = etiquetas[index]; // Nombre de la categoría                           
                            // Obtener los detalles de la barra seleccionada
                            const detalle = detallesSolicitudes[prioridadSeleccionada];
                            // Actualizar la tabla con los detalles
                            actualizarTabla(detalle);
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                }
                            }
                        }]
                    }
                }
            });



            // Gráfico 2: Casos por Tipo Solicitud

            const datosTipoSolicitudes = <?php echo $datosTipoSolicitudesJSON; ?>;
            const etiquetasG2 = datosTipoSolicitudes.map(item => item.GlosaTipoSolicitud);
            const cantidadesG2 = datosTipoSolicitudes.map(item => item.cantidad);

            const detallesSolicitudesG2 = {};

            if (Array.isArray(datosTipoSolicitudes)) {

                datosTipoSolicitudes.forEach(item => {
                    if (item && item.detalle) { // Verifica que 'detalle' existe                        
                        detallesSolicitudesG2[item.GlosaTipoSolicitud] = item.detalle;
                    }
                });

            } else {
                console.error("Error: datosTipoSolicitudes no es un array", datosTipoSolicitudes);
            }



            const ctx2 = document.getElementById('chartTipoSolicitud').getContext('2d');
            const myChart2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: etiquetasG2,
                    datasets: [{
                        label: 'Tipo de Solicitudes',
                        data: cantidadesG2,
                        backgroundColor: '#2a5298', 
                        borderColor: '#1e3c72',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    onClick: (event, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0]._index; // Índice de la barra seleccionada
                            const prioridadSeleccionadaG2 = etiquetasG2[index]; // Nombre de la categoría
                            // Obtener los detalles de la barra seleccionada
                            const detalle = detallesSolicitudesG2[prioridadSeleccionadaG2];
                            // Actualizar la tabla con los detalles
                            actualizarTabla(detalle);
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                }
                            }
                        }]
                    }
                }
            });


            // Función para actualizar la tabla con los detalles
            function actualizarTabla(detalle) {
                const tbody = document.querySelector("#TablaSolicitudes tbody");
                tbody.innerHTML = ""; // Limpiar tabla
                console.log(detalle);
                detalle.forEach(caso => {
                    let row = `<tr>
                        <td>${caso.id}</td>
                        <td>${caso.NombreSolicitud}</td>
                        <td>${caso.FechaSolicitud}</td>
                        <td>${caso.Prioridad}</td>
                        <td>${caso.GlosaTipoSolicitud}</td>
                        <td>${caso.EstadoSolicitud}</td>
                        <td> <a href="trabajo_solicitud.php?id=${caso.id}" class="btn btn-sm btn-info" role="button">Editar</a> </td>   
                        
                    </tr>`;
                    tbody.innerHTML += row;
                });
            }

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
        </script>
</body>

</html>