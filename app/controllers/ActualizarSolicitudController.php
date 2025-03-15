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

    /* echo "<pre>";
    print_r($_POST);
    echo "</pre>"; 
    exit(); */
    
    $usuario_idActual = $_SESSION['usuario_id'];
    $idSolicitud=$_POST['numSolicitud'];
    $titulo = trim($_POST['titulo']);
    $fecha_solicitud = $_POST['txtFechaSolicitud'];
    $solicitante= $_POST['txtNombreSolicitante'];
    $prioridad = $_POST['SelectPrioridad'];
    $tipo_solicitud = $_POST['selectTipoSolicitud'];
    $descripcion = trim($_POST['descripcion']);
    $comentarioColaborador= $_POST['txtComentarioColaborador'];
    $IdUsuarioDerivar= $_POST['SelectFuncionarioModal'];
    $comentarioUsuarioDerivar= $_POST['txtDistribucionModal'];
    
    

    if (!empty($idSolicitud)) {
      
    
        $solicitudModel = new Solicitud($conn);
        if ($solicitudModel->DerivarSolicitud($idSolicitud, $usuario_idActual,$prioridad, $IdUsuarioDerivar, $comentarioUsuarioDerivar)) {
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