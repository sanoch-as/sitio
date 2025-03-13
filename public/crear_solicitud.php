<?php
session_start();

require_once __DIR__ . '/../config/database.php'; // Conexión a la BD
require_once __DIR__ . '/../app/models/TipoSolicitud.php'; // Modelo de TipoSolicitud
require_once __DIR__ . '/../app/models/Usuario.php'; // Modelo de Usuarios

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}
//Obtener fecha
$fecha_hoy = date('Y-m-d');

//Obtener Tipo Solicitud
$tipoSolicitudModel = new TipoSolicitud($conn);
$tipos_solicitud = $tipoSolicitudModel->obtenerTipos();

//Obtener Listado Usuarios
$ListadoUsuariosModel = new Usuario($conn);
$ListadoUsuarios = $ListadoUsuariosModel->ListadoUsuariosDisponibles();


?>





<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Solicitud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!--  <link href="./assets/css/main.css" rel="stylesheet">
    <link href="./assets/css/sanoch.css" rel="stylesheet"> -->
</head>

<body>
    <div class="container">
        <h2>Crear Nueva Solicitud</h2>
        <?php if (isset($_GET['error'])) echo "<div class='alert alert-danger'>Error al registrar la solicitud.</div>"; ?>
        <form action="../app/controllers/SolicitudController.php" method="POST">

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
                                            <label for="titulo">Titulo Solicitud</label>
                                            <input type="text" class="form-control input-sm" name="titulo" id="titulo" value="" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="txtFechaSolicitud">Fecha Solicitud</label>
                                            <input type="date" class="form-control input-sm" name="txtFechaSolicitud" id="txtFechaSolicitud"  value="<?= $fecha_hoy ?>" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="txtNombreSolicitante">Nombre Solicitante</label>
                                            <input type="text" class="form-control input-sm" name="txtNombreSolicitante" id="txtNombreSolicitante" maxlength="100" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-md-4 mb-3">
                                            <label for="Prioridad">prioridad</label>
                                            <select id="SelectPrioridad" class="form-control select2" name="SelectPrioridad" style="width:100%">
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <option value="Baja">Baja</option>
                                                <option value="Media">Media</option>
                                                <option value="Alta">Alta</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="selectTipoSolicitud">Tipo Solicitud</label>
                                            <select id="selectTipoSolicitud" class="form-control select2" name="selectTipoSolicitud" style="width:100%" required>
                                                <option value="" disabled selected>Seleccione una opción</option>
                                                <?php foreach ($tipos_solicitud as $tipo) : ?>
                                                    <option value="<?= $tipo['idTipoSolicitud'] ?>"><?= $tipo['GlosaTipoSolicitud'] ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-md-12 mb-3">
                                            <label for="descripcion">Solicitud</label>
                                            <textarea class="form-control input-sm" name="descripcion" id="descripcion" rows="4" cols="50" maxlength="200" style="width:100%" required></textarea>
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
                                    <select id="SelectFuncionario" class="form-control select2" name="SelectFuncionario" style="width:100%" required>
                                        <option value="" disabled selected>Seleccione un usuario</option>
                                        <?php foreach ($ListadoUsuarios as $usuario) : ?>
                                            <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre_completo'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-md-12 mb-6">
                                            <label for="txtComentarioColaborador">Comentario</label>
                                            <textarea id="txtComentarioColaborador" class="form-control" name="txtComentarioColaborador" rows="4" style="width:100%"></textarea>
                                            <small class="form-text text-muted">Ingese comentario u observación</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
            <!--######### -->
        </form>
    </div>

</body>

</html>