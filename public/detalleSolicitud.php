<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Solicitud.php'; // Modelo de Solicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios
require_once __DIR__ . '/../config/session.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header('Location: login.php');
    exit();
}


// Validar si el ID está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /sitio/public/resultado.php?result=NOK&msg='Error al obtener detalle de solicitud'");
    exit();
}
$id = $_GET['id'];

if ($id) {

    //Obtener Detalle Solicitud
    $detalleSolicitudModel = new Solicitud($conn);
    $detalleSolicitud = $detalleSolicitudModel->DetalleSolicitud($id);

    /* echo "<pre>";
    var_dump($detalleSolicitud);
    echo "</pre>"; */

    if (!$detalleSolicitud || empty($detalleSolicitud)) {
        header("Location: /sitio/public/resultado.php?result=NOK&msg='Error al obtener detalle de solicitud: $id'");
        exit();
    }


    //Obtener Listado Usuarios
    $ListadoUsuariosModel = new Usuario($conn);
    $ListadoUsuarios = $ListadoUsuariosModel->ListadoUsuariosDisponibles();


    //Obtener Notas
    $notasSolicitudModel = new Solicitud($conn);
    $notas = $notasSolicitudModel->ObtenerNotasSolicitud($id);

    //Obtener Seguimiento
    $SeguimientosSolicitudModel = new Solicitud($conn);
    $Seguimientos = $SeguimientosSolicitudModel->ObtenerSeguimiento($id);
} else {
    header("Location: /sitio/public/resultado.php?result=Obtener Registro&msg='Error al obtener detalle de solicitud'");
    exit();
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consultar Solicitud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- <link href="http://localhost/sitio/public/assets/css/sanoch.css" rel="stylesheet"> -->
    <link href="http://localhost/sitio/public/assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap4.css">
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap4.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<body>
    <div class="container" style="padding: 10px 10px 10px 10px;">
        <div id='titulo' class="panel panel-default">
            <div class="panel-heading"><img src="./assets/images/IconoDocumento.png" width="70" height="70" class="d-inline-block align-top" alt="" style="padding: 1px 1px 1px 1px"> <label style="font-size: 22px; font-weight: bold;">Gestión de Solicitudes</label></div>
        </div>
        <?php if (isset($_GET['error'])) echo "<div class='alert alert-danger'>Error al registrar la solicitud.</div>"; ?>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#Solicitud">Solicitud
                </a></li>
            <li><a data-toggle="tab" href="#Notas">Comentarios</a></li>
            <li><a data-toggle="tab" href="#Seguimiento">Seguimiento</a></li> 
        </ul>

        <div class="tab-content">
            <div id="Solicitud" class="tab-pane fade in active">
                <form action="../app/controllers/ActualizarSolicitudController.php" method="POST" id="FormSolicitud" name="FormSolicitud">
                    <!--######### -->
                    <div style="padding-top:10px">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="panel panel-primary" id="PanelSolicitud">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Solicitud</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-row align-items-center">
                                            <div class="col-md-4 mb-3">
                                                <label for="numSolicitud">Número Solicitud</label>
                                                <input type="text" class="form-control-sm form-control" name="numSolicitud" id="numSolicitud" value="<?php echo $id; ?>" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="titulo">Titulo Solicitud</label>
                                                <input type="text" class="form-control-sm form-control" name="titulo" id="titulo" value="<?php echo htmlspecialchars($detalleSolicitud[0]['titulo']); ?>" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="txtFechaSolicitud">Fecha Solicitud</label>
                                                <input type="date" class="form-control-sm form-control" name="txtFechaSolicitud" id="txtFechaSolicitud" value="<?php echo htmlspecialchars($detalleSolicitud[0]['fecha_solicitud']); ?>" readonly>
                                            </div>
                                        </div>


                                        <div class="form-row align-items-center">
                                            <div class="col-md-4 mb-3">
                                                <label for="txtNombreSolicitante">Nombre Solicitante</label>
                                                <input type="text" class="form-control-sm form-control" name="txtNombreSolicitante" id="txtNombreSolicitante" maxlength="100" value="<?php echo htmlspecialchars($detalleSolicitud[0]['Nombre_solicitante']); ?>" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="Prioridad">prioridad</label>
                                                <input type="text" class="form-control-sm form-control" name="SelectPrioridad" id="SelectPrioridad" value="<?php echo $detalleSolicitud[0]['prioridad']; ?>" readonly>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="selectTipoSolicitud">Tipo Solicitud</label>
                                                <input type="text" class="form-control-sm form-control" name="selectTipoSolicitud" id="selectTipoSolicitud" value="<?php echo $detalleSolicitud[0]['GlosaTipoSolicitud']; ?>" readonly>

                                            </div>
                                        </div>


                                        <div class="form-row align-items-center">
                                            <div class="col-md-12 mb-3">
                                                <label for="descripcion">Solicitud</label>
                                                <textarea class="form-control-sm form-control" name="descripcion" id="descripcion" rows="4" maxlength="200" style="width:100%" readonly><?php echo htmlspecialchars($detalleSolicitud[0]['descripcion']); ?></textarea>
                                                <small class="form-text text-muted">Descripción de la solicitud</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-primary" id="PanelDistribucion">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Distribución</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-12">
                                                    <label for="SelectFuncionario">Colaborador</label>
                                                    <select id="SelectFuncionario" class="form-control-sm form-control select2" name="SelectFuncionario" style="width:100%" disabled>
                                                        <?php echo '<option value="' . $detalleSolicitud[0]['colaborador'] . '" selected>' . $detalleSolicitud[0]['nombre_completo'] . '</option>'; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-12">
                                                    <label for="txtComentarioColaborador">Comentario</label>
                                                    <textarea class="mb-2 form-control-sm form-control" id="txtComentarioColaborador" name="txtComentarioColaborador" rows="4" style="width:100%" readonly><?php echo htmlspecialchars($detalleSolicitud[0]['Observacion_Colaborador']); ?></textarea>
                                                    <small class="form-text text-muted">Ingese comentario u observación</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- card-body -->
                        </div> <!-- main-card mb-3 card -->
                    </div>
                </form>
            </div>
            <div id="Notas" class="tab-pane fade">
                <div style="padding-top:10px">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"> Comentarios</h3>
                                </div>
                                <div class="panel-body">
                                    <form style="padding: 10px 10px 10px 10px">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="txtcomentario" name="txtcomentario" placeholder="Comentario">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" id="btnAgregarComentario"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp; Comentario</button>
                                            </span>
                                        </div><!-- /input-group -->

                                        <div style="padding-top: 15px;">
                                            <div class="table-responsive">
                                                <table class="table table-sm" id="tablacomentarios" name="tablacomentarios">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:5%" hidden>id</th>
                                                            <th style="width:45%">Comentario</th>
                                                            <th style="width:25%">Fecha</th>
                                                            <th style="width:25%">Usuario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="BodyNotas">
                                                        <?php if (!empty($notas)) {
                                                            foreach ($notas as $nota) {
                                                                echo '<tr><td>' . $nota['id'] . '</td><td>' . htmlspecialchars($nota['Comentario']) . '</td><td>' . $nota['FechaComentario'] . '</td><td>' . $nota['NombreUsuarioComentario'] . '</td></tr>';
                                                            }
                                                        } else {
                                                            echo '<tr><td colspan="3">No hay comentarios</td></tr>';
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="Seguimiento" class="tab-pane fade">
                <div style="padding-top:10px">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Seguimiento</h3>
                        </div>
                        <div class="panel-body">
                            <form style="padding: 10px 10px 10px 10px">
                                <div style="padding-top: 15px;"></div>
                                <table class="table table-striped display" id="tablaSeguimiento" name="tablaSeguimiento"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">Actividad</th>
                                            <th style="width:20%">Fecha Derivación</th>
                                            <th style="width:20%">Fecha Apertura</th>
                                            <th style="width:20%">Fecha Término Trabajo</th>
                                            <th style="width:20%">Usuario Derivado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="BodySeguimiento">
                                        <?php if (!empty($Seguimientos)) {
                                            foreach ($Seguimientos as $Seguimiento) {
                                                echo '<tr>
                                                <td>' . $Seguimiento['Actividad'] . '</td>
                                                <td>' . $Seguimiento['FechaDerivacionTarea'] . '</td>
                                                <td>' . $Seguimiento['FechaInicioTrabajo'] . '</td>
                                                <td>' . $Seguimiento['FechaFinTrabajo'] . '</td>
                                                <td>' . $Seguimiento['UsuarioDerivado'] . '</td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="3">No hay seguimiento</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                language: "es"
            });

            $('#tablacomentarios').DataTable({
                "destroy": true, // Permite reinicializar la tabla sin errores
                "responsive": true,
                "paging": true, // Habilitar paginación
                "searching": true, // Habilitar búsqueda
                "ordering": true, // Habilitar ordenamiento de columnas
                "order": [
                    [0, 'asc']
                ],
                "info": true, // Mostrar información de registros
                "lengthMenu": [10, 25, 50, 100], // Opciones de registros por página
                "columns": [{
                        title: "ID",
                        data: "id"
                    },
                    {
                        title: "Comentario",
                        data: "Comentario"
                    },
                    {
                        title: "Fecha",
                        data: "FechaComentario"
                    },
                    {
                        title: "Usuario",
                        data: "NombreUsuarioComentario"
                    }
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ comentarios",
                    "emptyTable": 'Sin comentarios encontrados',
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ comentarios",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(filtrado de _MAX_ comentarios en total)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "visible": false,
                    "searchable": false
                }]


            });

            $("#btnAgregarComentario").click(function() {

                var comentario = $("#txtcomentario").val().trim();
                var idSolicitud = $("#numSolicitud").val();


                if (comentario === "") {
                    bootbox.alert("Por favor, ingrese un comentario.");
                    return;
                }
                url = "ActualizarComentarioSolicitudController.php?comentario=" + comentario + "&idSolicitud=" + idSolicitud;
                $.ajax({
                    url: url,
                    type: "GET",
                    async: "false",
                    dataType: "json",
                    charset: "utf-8",
                    success: function(response) {
                        let tbody = $("#BodyNotas");
                        tbody.empty(); // Limpia el tbody antes de agregar los datos
                        response.forEach(item => {
                            let fila = `<tr>
                            <td hidden>${item.id}</td>
                            <td>${item.Comentario}</td>
                            <td>${item.FechaComentario}</td>
                            <td>${item.NombreUsuarioComentario}</td>                    
                            </tr> `;
                            tbody.append(fila);
                        });
                        $("#txtcomentario").val("");
                        bootbox.alert("Comentario agregado correctamente.");
                    },
                    error: function() {
                        bootbox.alert("Hubo un error en la solicitud.");
                    }
                });
            });
        });

        document.getElementById("btnEnviarDerivar").addEventListener("click", function() {
            $("#idderivacion").val(1);
            document.getElementById("FormSolicitud").submit();
        });

        document.getElementById("btnFinalizar").addEventListener("click", function() {
            $("#idderivacion").val(2);
            document.getElementById("FormSolicitud").submit();
        });

        document.getElementById("btnCancelar").addEventListener("click", function() {
            window.location.href = "gestionar_solicitud.php";
        });
    </script>

</body>

</html>