<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Usuario.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $userModel = new Usuario($conn);
    $user = $userModel->obtenerUsuarioPorCredenciales($usuario, $password);

    if ($user) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['usuario_id'] = $user['id']; // Guardar el ID del usuario
        $_SESSION['nombre_completo'] = $user['nombre_completo'];
        $_SESSION['rol'] = $user['rol'];
        header('Location: /sitio/public/dashboard.php');
        exit();
    } else {
        header('Location: /sitio/public/login.php?error=1');
        exit();
    }
}
?>