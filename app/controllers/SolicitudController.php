<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';


// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: /sitio/public/index.php');
    exit();
}



//#### Recibir campos por POST

// Verificar si la solicitud es por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = trim($_POST['titulo']);
    $fecha_solicitud = $_POST['txtFechaSolicitud'];
    $solicitante= $_POST['txtNombreSolicitante'];
    $prioridad = $_POST['SelectPrioridad'];
    $tipo_solicitud = $_POST['selectTipoSolicitud'];
    //$tipo_solicitud = '0';
    $descripcion = trim($_POST['descripcion']);
    $colaborador= $_POST['SelectFuncionario'];
    //$colaborador= '0';
    $comentarioColaborador= $_POST['txtComentarioColaborador'];
    
     
    

    if (!empty($titulo) && !empty($fecha_solicitud) && !empty($descripcion)) {
       
        $solicitudModel = new Solicitud($conn);
        if ($solicitudModel->crearSolicitud($usuario_id, $titulo, $fecha_solicitud, $solicitante, $prioridad, $tipo_solicitud, $descripcion, $colaborador, $comentarioColaborador)) {
            header("Location: /sitio/public/resultado.php?result=OK&msg=OK");
            exit();
        } else {
            $error_msg = urlencode($conn->error);
            error_log("Error en la inserción de solicitud: " . $conn->error);
            header("Location: /sitio/public/resultado.php?result=NOK&msg=$error_msg");
            exit();
        }
    } else {
        $error_msg = urlencode($conn->error);
        header("Location: /sitio/public/resultado.php?result=NOK&msg=$error_msg"); // Error por campos vacíos
        exit();
    }
}
?>