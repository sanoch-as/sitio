<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$perfilUsuario = $_SESSION['rol']; // "admin", "usuario", "Supervisor"

// Obtener el ID del perfil del usuario
$sqlPerfil = "SELECT id FROM perfiles WHERE nombre = ?";
$stmtPerfil = $conn->prepare($sqlPerfil);
$stmtPerfil->bind_param("s", $perfilUsuario);
$stmtPerfil->execute();
$resultPerfil = $stmtPerfil->get_result();
$perfilId = $resultPerfil->fetch_assoc()['id'];

// Si es admin, obtiene todos los menús, sino filtra por perfil
if ($perfilUsuario === "admin") {
    $sqlMenu = "SELECT * FROM menus ORDER BY orden ASC";
    $stmtMenu = $conn->prepare($sqlMenu);
} else {
    $sqlMenu = "SELECT m.* FROM menus m 
                JOIN menu_perfil mu ON m.id = mu.menu_id
                WHERE mu.perfil_id = ? 
                ORDER BY m.orden ASC";
    $stmtMenu = $conn->prepare($sqlMenu);
    $stmtMenu->bind_param("i", $perfilId);
}

$stmtMenu->execute();
$resultMenu = $stmtMenu->get_result();
$menus = [];

while ($row = $resultMenu->fetch_assoc()) {
    $menus[] = $row;
}
echo "<pre>"; print_r($menus); echo "</pre>";
exit();
echo json_encode($menus);


?>
