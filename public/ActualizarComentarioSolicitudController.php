<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/Solicitud.php';

header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header('Location: /sitio/public/login.php');
    exit();
}


$solicitud_id = $_GET['idSolicitud'] ?? null;
$cometario = $_GET['comentario'] ?? null;
$usuario_idActual = $_SESSION['usuario_id'];



if (!$solicitud_id) {
    echo json_encode(["success" => false, "codrespuesta" => 'NOK', "msg" => "ID no recepcionado"]);
    exit;
}


if (!$cometario || empty($cometario)) {
    echo json_encode(["success" => false, "codrespuesta" => 'NOK', "msg" => "Comentario no recepcionado"]); 
}else{
    $query = "CALL AgregarComentario($solicitud_id, $usuario_idActual, '$cometario')";
    $stmt = $conn->prepare($query);
    
    

    if ($stmt->execute()) {
        try {
            $notasSolicitudModel = new Solicitud($conn);
            $notas = $notasSolicitudModel->ObtenerNotasSolicitud($solicitud_id);
            echo json_encode($notas);
        } catch (Exception $e) {            
            echo json_encode(["success" => false, "codrespuesta" => 'NOK', "msg" => "Error al obtener notas: " . $e->getMessage()]);
        }
               
    } else {
        echo json_encode(["success" => false, "codrespuesta" => 'NOK', "msg" => "Error al agregar comentario"]);
    }

    $stmt->close();
    
}
    


    

