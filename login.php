<?php
session_start(); // Inicia la sesión
require_once('conexion.php'); // Incluye la conexión a la base de datos

$error = ""; // Variable para los mensajes de error

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $password = $_POST['password'];

    // Consulta SQL para obtener el usuario con el correo proporcionado
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conexion, $query);

    // Verifica si el correo existe en la base de datos
    if (mysqli_num_rows($result) > 0) {
        // Si existe, obtiene la información del usuario
        $user = mysqli_fetch_assoc($result);

        // Verifica la contraseña
        if (password_verify($password, $user['password'])) {
            // Si la contraseña es correcta, inicia la sesión
            $_SESSION['usuario_id'] = $user['id']; // Guarda el ID del usuario
            $_SESSION['usuarios'] = $user['nombre']; // Guarda el nombre del usuario
            $_SESSION['autenticado'] = true; // Marca la sesión como autenticada
            header("Location: panel.php"); // Redirige al panel
            exit;
        } else {
            // Si la contraseña es incorrecta
            $error = "Contraseña incorrecta.";
        }
    } else {
        // Si el correo no existe en la base de datos
        $error = "El usuario no existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/estilos_login.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">
    <div class="card-header bg-primary text-white text-center">
                <img src="imagenes/2.png" alt="logo" class="mb-3">
                <h3 class ="titulo" >Iniciar Secion </h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
