<?php
session_start();
require_once('conexion.php');
require_once('funciones.php');

// Inicializar la variable $error para evitar el warning
$error = "";

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuarios']) || !isset($_SESSION['autenticado'])) {
    header('Location: login.php'); // Si no está autenticado, redirigir a login
    exit();
}

// Obtener datos del usuario
$usuarioId = $_SESSION['usuario_id']; // ID del usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitarizar y obtener los datos del formulario
    $titulo = htmlspecialchars($_POST['titulo']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $fecha = htmlspecialchars($_POST['fecha']);
    $hora = htmlspecialchars($_POST['hora']);
    
    // Verificar que todos los campos estén completos
    if (!empty($titulo) && !empty($fecha) && !empty($hora)) {
        try {
            // Llamar a la función para agregar el evento
            agregarEvento($usuarioId, $titulo, $descripcion, $fecha, $hora);
            header("Location: panel.php"); // Redirige al panel después de agregar el evento
            exit;
        } catch (Exception $e) {
            $error = "Error al agregar el evento: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, complete todos los campos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Evento</title>
    <link rel="stylesheet" href="css/estilos_registar.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Agregar Nuevo Evento</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-evento">
            <div class="form-group">
                <label for="titulo">Título del evento</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ingrese el título" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción del evento</label>
                <textarea id="descripcion" name="descripcion" placeholder="Escriba una descripción"></textarea>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>

            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="time" id="hora" name="hora" required>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Evento</button>
        </form>

        <div class="back-link">
            <a href="panel.php" class="btn btn-secondary">Volver al Panel</a>
        </div>
    </div>
</body>
</html>
