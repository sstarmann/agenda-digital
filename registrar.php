<?php
require_once('conexion.php');
require_once('clases/usuario.php');
$usuario = new Usuario($conexion);
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $mensaje = $usuario->registrarUsuario($nombre, $email, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/estilos_registrar.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="container">
        <div class="card w-100" style="max-width: 400px;">
            <div class="card-header">
                <h3>Registro de Usuario</h3>
            </div>
            <div class="card-body">
                <!-- Mostrar el mensaje de éxito o error -->
                <?php if ($mensaje): ?>
                    <div class="alert alert-info"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingresa tu nombre completo" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Ingresa tu correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Crea una contraseña segura" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Registrarse</button>
                </form>
            </div>
            <div class="card-footer text-center py-3 text-muted">
                ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
