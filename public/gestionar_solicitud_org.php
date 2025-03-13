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
include 'header.php';
$query = "SELECT * FROM solicitudes";
$result = $conn->query($query);
?>
<div class="container">
    <h2>Gestionar Solicitudes</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($solicitud = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $solicitud['id']; ?></td>
            <td><?php echo $solicitud['titulo']; ?></td>
            <td><?php echo $solicitud['estado']; ?></td>
            <td><a href="editar_solicitud.php?id=<?php echo $solicitud['id']; ?>" class="btn btn-warning">Editar</a></td>
        </tr>
        <?php } ?>
    </table>
</div>