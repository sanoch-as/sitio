<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /sitio/public/login.php');
    exit();
}

//require_once __DIR__ . '/../../config/database.php'; // Conexión a la BD
echo __DIR__ . '/../../config/database.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/sitio/public/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/sitio/public/assets/style.css">
    <script src="/sitio/public/assets/jquery.min.js"></script>
    <script src="/sitio/public/assets/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Menú vertical -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="vertical-nav-menu">
                    <h4 class="text-center py-3">Menú</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/sitio/public/dashboard.php">🏠 Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/sitio/public/crear_solicitud.php">📝 Crear Solicitud</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/sitio/public/gestionar_solicitud.php">📋 Gestionar Solicitud</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="/sitio/public/logout.php">🚪 Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <h2 class="mt-4">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>
                
                <!-- Tarjeta con listado de solicitudes -->
                <div class="card mt-4">
                    <div class="card-header">
                        📌 Tus Solicitudes Pendientes
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT id, asunto, estado, fecha_creacion FROM solicitudes WHERE usuario_id = ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param('i', $_SESSION['usuario_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['asunto']}</td>
                                            <td>{$row['estado']}</td>
                                            <td>{$row['fecha_creacion']}</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>
