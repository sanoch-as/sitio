<?php
session_start();
session_unset();  // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión actual

// Si la petición es AJAX (fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(["success" => true]);
    exit;
}

// Si la petición es una redirección (cuando expira la sesión)
header("Location: ../../login.php");
exit;



?>
