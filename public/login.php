
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .logo img {
            max-width: 150px; /* Ajusta el tamaño según necesites */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo">
            <img src="./assets/images/IconoDocumento.png" alt="Logo"> 
        </div>
        <h5 class="text-center mb-4">ingresa las credenciales</h5>
        <form action="../app/controllers/AuthController.php" method="POST">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" placeholder="usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
            </div>            
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <?php if (isset($_GET['error'])) echo "<p style='color:red;'>Usuario o contraseña incorrectos</p>"; ?>
    </div>

</body>
</html>
