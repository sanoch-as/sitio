<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$base_de_datos = "gestion_solicitudes";

$conn = new mysqli($host, $usuario, $clave, $base_de_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>