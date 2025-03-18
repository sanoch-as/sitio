<?php
session_start();

// Definir tiempo de expiración en segundos (15 minutos = 900 segundos)
$inactividad_maxima = 900; 

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactividad_maxima)) {
    // Destruir la sesión y redirigir al login
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

// Actualizar el tiempo de última actividad
$_SESSION['LAST_ACTIVITY'] = time();
?>