<?php


session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/Solicitud.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: /sitio/public/index.php');
    exit();
}


// Activar el reporte de errores (para depuración)
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); // Asegura que el output sea JSON


// Inicializar la conexión a la base de datos

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Validar y obtener parámetros
$IdSolicitud = isset($_GET['IdSolicitud']) ? intval($_GET['IdSolicitud']) : null;
$proceso = isset($_GET['PROCESO']) ? $conn->real_escape_string($_GET['PROCESO']) : null;
$fdesde = isset($_GET['FDESDE']) ? $_GET['FDESDE'] : null;
$fhasta = isset($_GET['FHASTA']) ? $_GET['FHASTA'] : null;

// Construcción de la consulta
$query = "SELECT * FROM gestion_solicitudes.solicitudes WHERE 1=1";

if ($IdSolicitud) {
    $query .= " AND id = $IdSolicitud";
}

if ($proceso && $proceso !== 'all') {
    $query .= " AND tipo_solicitud = '$proceso'";
}

if ($fdesde) {
    $nuevadesde = date("Y-m-d 00:00:00", strtotime($fdesde));
    $query .= " AND fecha_solicitud >= '$nuevadesde'";
}

if ($fhasta) {
    $nuevahasta = date("Y-m-d 23:59:59", strtotime($fhasta));
    $query .= " AND fecha_solicitud <= '$nuevahasta'";
}

$query .= " ORDER BY id DESC";

// Ejecutar la consulta
$result = $conn->query($query);

$Solicitudes = ["data" => []];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $Solicitudes["data"][] = $row;
    }
    $result->free();
} else {
    $Solicitudes["error"] = "Error en la consulta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();

// Retornar JSON
echo json_encode($Solicitudes, JSON_UNESCAPED_UNICODE);
?>
