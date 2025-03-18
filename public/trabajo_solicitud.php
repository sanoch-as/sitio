<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Solicitud.php'; // Modelo de Solicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios
require_once __DIR__ . '/../config/session.php'; 

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}


if ($_GET['id']) {

    //Obtener Tipo Solicitud
    $tipoSolicitudModel = new TipoSolicitud($conn);
    $tipos_solicitud = $tipoSolicitudModel->obtenerTipos();

    //Obtener Listado Usuarios
    $ListadoUsuariosModel = new Usuario($conn);
    $ListadoUsuarios = $ListadoUsuariosModel->ListadoUsuariosDisponibles();

    $id = $_GET['id'];
    //Obtener Detalle Solicitud
    $detalleSolicitudModel = new Solicitud($conn);
    $detalleSolicitud = $detalleSolicitudModel->DetalleSolicitud($id);
} else {
    header("Location: /sitio/public/resultado.php?result=Obtener Registro&msg='Error al obtener detalle de solicitud'");
    exit();
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Solicitud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="./assets/css/sanoch.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>
    


    <!-- https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.3/bootbox.js,
    https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css, 
    https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js, 
    https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json, 
    https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css, 
    https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js,
    https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js,
     -->




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
            <!-- <li><a data-toggle="tab" href="#Seguimiento">Seguimiento</a></li> -->
        </ul>

        <div class="tab-content">
            <div id="Solicitud" class="tab-pane fade in active">
                <form action="../app/controllers/ActualizarSolicitudController.php" method="POST" id="FormSolicitud" name="FormSolicitud">
                    <!--######### -->
                    <div style="padding-top:10px">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Solicitud</h3>
                            </div>
                            <div class="panel-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="form-row align-items-center">
                                                <div class="col-md-4 mb-3">
                                                    <label for="numSolicitud">Número Solicitud</label>
                                                    <input type="text" class="form-control input-sm" name="numSolicitud" id="numSolicitud" value="<?php echo $id; ?>" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="titulo">Titulo Solicitud</label>
                                                    <input type="text" class="form-control input-sm" name="titulo" id="titulo" value="<?php echo htmlspecialchars($detalleSolicitud[0]['titulo']); ?>" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="txtFechaSolicitud">Fecha Solicitud</label>
                                                    <input type="date" class="form-control input-sm" name="txtFechaSolicitud" id="txtFechaSolicitud" value="<?php echo htmlspecialchars($detalleSolicitud[0]['fecha_solicitud']); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-row align-items-center">
                                                <div class="col-md-4 mb-3">
                                                    <label for="txtNombreSolicitante">Nombre Solicitante</label>
                                                    <input type="text" class="form-control input-sm" name="txtNombreSolicitante" id="txtNombreSolicitante" maxlength="100" value="<?php echo htmlspecialchars($detalleSolicitud[0]['Nombre_solicitante']); ?>" readonly>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="Prioridad">prioridad</label>
                                                    <select id="SelectPrioridad" class="form-control select2" name="SelectPrioridad" style="width:100%">
                                                        <option value="" disabled>Seleccione una opción</option>
                                                        <option value="Baja" <?php if ($detalleSolicitud[0]['prioridad'] == 'Baja') echo 'selected'; ?>>Baja</option>
                                                        <option value="Media" <?php if ($detalleSolicitud[0]['prioridad'] == 'Media') echo 'selected'; ?>>Media</option>
                                                        <option value="Alta" <?php if ($detalleSolicitud[0]['prioridad'] == 'Alta') echo 'selected'; ?>>Alta</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="selectTipoSolicitud">Tipo Solicitud</label>
                                                    <select id="selectTipoSolicitud" class="form-control select2" name="selectTipoSolicitud" style="width:100%" required disabled>
                                                        <?php echo '<option value="' . $tipo["idTipoSolicitud"] . '" selected>' . $tipo["GlosaTipoSolicitud"] . '</option>';?>
                                                        <option value="" disabled selected>Seleccione una opción</option>
                                                        <?php foreach ($tipos_solicitud as $tipo) :
                                                            if ($detalleSolicitud[0]['tipo_solicitud'] == $tipo['idTipoSolicitud']) {
                                                                echo '<option value="' . $tipo["idTipoSolicitud"] . '" selected>' . $tipo["GlosaTipoSolicitud"] . '</option>';
                                                            } 
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-row align-items-center">
                                                <div class="col-md-12 mb-3">
                                                    <label for="descripcion">Solicitud</label>
                                                    <textarea class="form-control input-sm" name="descripcion" id="descripcion" rows="4" cols="50" maxlength="200" style="width:100%" readonly><?php echo htmlspecialchars($detalleSolicitud[0]['descripcion']); ?></textarea>
                                                    <small class="form-text text-muted">Descripción de la solicitud</small>
                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="form-row align-items-center">
                                        <div class="col-md-12 mb-12">
                                            <label for="SelectFuncionario">Colaborador</label>
                                            <select id="SelectFuncionario" class="form-control select2" name="SelectFuncionario" style="width:100%" disabled>
                                                <?php echo '<option value="' . $detalleSolicitud[0]['colaborador'] . '" selected>' . $detalleSolicitud[0]['nombre_completo'] . '</option>'; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-row align-items-center">
                                                <div class="col-md-12 mb-6">
                                                    <label for="txtComentarioColaborador">Comentario</label>
                                                    <textarea id="txtComentarioColaborador" class="form-control" name="txtComentarioColaborador" rows="4" style="width:100%" readonly><?php echo htmlspecialchars($detalleSolicitud[0]['Observacion_Colaborador']); ?></textarea>
                                                    <small class="form-text text-muted">Ingese comentario u observación</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--######### Modal Derivación-->

                    <div class="modal fade" id="ModalDistribucion" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body" style="padding-bottom:10px">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Agregar Distribución</h3>
                                        </div>
                                        <div class="panel-body">
                                            <form style="padding: 10px 10px 10px 10px">
                                                <div class="form-group">
                                                    <div class="form-row align-items-center">
                                                        <div class="col-md-12 mb-12"> <label for="SelectFuncionarioModal">Funcionario</label>
                                                            <select id="SelectFuncionarioModal" class="form-control select2" name="SelectFuncionarioModal" style="width:100%">
                                                                <option value="" disabled selected>Seleccione un usuario</option>
                                                                <?php foreach ($ListadoUsuarios as $usuario) : ?>
                                                                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre_completo'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-row align-items-center">
                                                                <div class="col-md-12 mb-6"> <label for="txtDistribucionModal">Comentario</label>
                                                                    <textarea id="txtDistribucionModal" class="form-control" name="txtDistribucionModal" rows="4" style="width:100%"></textarea>
                                                                    <small class="form-text text-muted">Ingese comentario u observación</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-sm btn-success" id="btnEnviarDerivar" name="btnEnviarDerivar">Derivar</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div id="Notas" class="tab-pane fade">
                <div style="padding-top:10px">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Comentarios</h3>
                        </div>
                        <div class="panel-body">
                            <form style="padding: 10px 10px 10px 10px">

                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button">Button</button>
                                    </div>
                                </div>


                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputPassword2" class="sr-only">Password</label>
                                        <input type="text" class="form-control" id="inputPassword2" placeholder="Password">
                                    </div>
                                    <button type="submit" class="btn btn-primary mb-2">Confirm identity</button>
                                </form>

                                <button type="button" class="btn btn-sm btn-info" id="btnNotas">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp; Comentario</button>
                                <div style="padding-top: 15px;">
                                    <table class="table table-striped display" id="tablacomentarios" name="tablacomentarios" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width:50%">Nota</th>
                                                <th style="width:25%">Fecha</th>
                                                <th style="width:25%">Usuario</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
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
                                <table class="table table-striped display" id="tablaDocumentos" name="tablaDocumentos"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width:35%">Clasificación</th>
                                            <th style="width:35%">Nombre Archivo</th>
                                            <th style="width:10%">Fecha</th>
                                            <th style="width:10%">Usuario</th>
                                            <th style="width:10%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="BodyDocumentos"> </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-success" id="btnModalDistribucion" data-toggle="modal" data-target="#ModalDistribucion">Derivar Solicitid</button>
            <button type="button" class="btn btn-sm btn-danger" id="btnFinalizar">Finalizar Solicitud</button>
            <button type="reset" class="btn btn-sm btn-default" id="btnCancelar">Cancelar</button>
        </div>
    </div>
    
    <script>
        document.getElementById("btnEnviarDerivar").addEventListener("click", function() {
            document.getElementById("FormSolicitud").submit();
        });
    </script>

</body>

</html>