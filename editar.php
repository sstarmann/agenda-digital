<?php
session_start();
require_once('conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuarios']) || !isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se recibió el formulario por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si los datos están llegando correctamente
    if (isset($_POST['id'], $_POST['titulo'], $_POST['descripcion'], $_POST['fecha'], $_POST['hora'])) {
        // Obtener los datos del formulario
        $eventoId = $_POST['id'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];

        // Actualizar el evento en la base de datos
        $query = "UPDATE eventos SET titulo = ?, descripcion = ?, fecha = ?, hora = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ssssii', $titulo, $descripcion, $fecha, $hora, $eventoId, $_SESSION['usuario_id']);

        // Ejecutar la consulta y verificar si la actualización fue exitosa
        if ($stmt->execute()) {
            // Redirigir al panel de eventos después de la actualización
            header('Location: panel.php');  // O la página donde quieras mostrar los eventos actualizados
            exit();
        } else {
            echo "Hubo un error al actualizar el evento.";
        }
    } else {
        echo "Datos faltantes.";
    }
} else {
    // Verificar si el ID del evento está presente en la URL
    if (isset($_GET['id'])) {
        $eventoId = $_GET['id'];

        // Obtener los datos del evento
        $query = "SELECT * FROM eventos WHERE id = ? AND usuario_id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ii', $eventoId, $_SESSION['usuario_id']);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $evento = $resultado->fetch_assoc();
        } else {
            echo "Evento no encontrado.";
            exit();
        }
    } else {
        echo "ID del evento no proporcionado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <!-- Cargar Bootstrap desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos_editar.css">
</head>
<body>

    <div class="container">
        <h1 class="text-center mb-4">Editar Evento</h1>

        <form action="editar.php" method="POST">
            <!-- Campo oculto para enviar el ID del evento -->
            <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo htmlspecialchars($evento['fecha']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="hora" class="form-label">Hora:</label>
                <input type="time" id="hora" name="hora" class="form-control" value="<?php echo htmlspecialchars($evento['hora']); ?>" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Actualizar Evento</button>
            </div>
        </form>
    </div>

    <!-- Incluir JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
