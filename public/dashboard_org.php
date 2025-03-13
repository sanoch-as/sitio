<?php
session_start();
include 'db.php'; // Archivo de conexión a la base de datos

// Verificar autenticación
if (!isset($_SESSION['usuario']) && basename($_SERVER['PHP_SELF']) != 'index.php') {
    header('Location: index.php');
    exit();
}
?>



<?php
include 'header.php'; // Archivo con la cabecera y menú
?>
<div class="container">
    <h2 class="text-center">Dashboard</h2>
    <div class="row">
        <div class="col-md-6">
            <canvas id="chartSolicitudes"></canvas>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Solicitudes Asignadas</div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php foreach ($solicitudes as $solicitud) { ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($solicitud['titulo']); ?> - 
                                <strong><?php echo htmlspecialchars($solicitud['estado']); ?></strong>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>