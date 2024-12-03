<?php
session_start();
require_once('conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuarios']) || !isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit();
}

// Obtener datos del usuario
$nombreUsuario = $_SESSION['usuarios']; 
$usuarioId = $_SESSION['usuario_id']; 

// Función para validar la propiedad del evento
function validarPropiedadEvento($conexion, $eventoId, $usuarioId) {
    $query = "SELECT COUNT(*) as count FROM eventos WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $eventoId, $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] > 0;
}

// Función para manejar errores de base de datos
function manejarErrorBaseDatos($stmt, $conexion, $mensajeError = "Ocurrió un error en la base de datos") {
    error_log("Error en la consulta: " . $stmt->error);
    $_SESSION['error'] = $mensajeError;
}

function obtenerEventos($usuarioId) {
    global $conexion;
    $query = "SELECT * FROM eventos WHERE usuario_id = ? AND realizado = 0 ORDER BY fecha_creacion DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $usuarioId);
    
    if (!$stmt->execute()) {
        manejarErrorBaseDatos($stmt, $conexion, "No se pudieron cargar los eventos");
        return [];
    }
    
    $result = $stmt->get_result();
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $eventos;
}

function obtenerEventosRealizados($usuarioId) {
    global $conexion;
    $query = "SELECT * FROM eventos WHERE usuario_id = ? AND realizado = 1 ORDER BY fecha_creacion DESC";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $usuarioId);
    
    if (!$stmt->execute()) {
        manejarErrorBaseDatos($stmt, $conexion, "No se pudieron cargar los eventos realizados");
        return [];
    }
    
    $result = $stmt->get_result();
    $eventos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $eventos;
}

// Función para marcar evento como realizado
if (isset($_GET['realizado'])) {
    $eventoId = filter_input(INPUT_GET, 'realizado', FILTER_VALIDATE_INT);
    
    if ($eventoId === false || $eventoId <= 0) {
        $_SESSION['error'] = "ID de evento inválido";
        header("Location: panel.php");
        exit();
    }

    // Validar que el evento pertenezca al usuario
    if (!validarPropiedadEvento($conexion, $eventoId, $usuarioId)) {
        $_SESSION['error'] = "No tienes permiso para modificar este evento";
        header("Location: panel.php");
        exit();
    }

    // Actualizar el evento a "realizado"
    $query = "UPDATE eventos SET realizado = 1 WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $eventoId, $usuarioId);
    
    if (!$stmt->execute()) {
        manejarErrorBaseDatos($stmt, $conexion, "No se pudo marcar el evento como realizado");
    }
    
    $stmt->close();
    header("Location: panel.php");
    exit();
}

// Función para cancelar evento
if (isset($_GET['cancelar'])) {
    $eventoId = filter_input(INPUT_GET, 'cancelar', FILTER_VALIDATE_INT);
    
    if ($eventoId === false || $eventoId <= 0) {
        $_SESSION['error'] = "ID de evento inválido";
        header("Location: panel.php");
        exit();
    }

    // Validar que el evento pertenezca al usuario
    if (!validarPropiedadEvento($conexion, $eventoId, $usuarioId)) {
        $_SESSION['error'] = "No tienes permiso para modificar este evento";
        header("Location: panel.php");
        exit();
    }
    
    // Preparar la consulta para actualizar el estado a 'Cancelado'
    $query = "UPDATE eventos SET estado = 'Cancelado' WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $eventoId, $usuarioId);
    
    if (!$stmt->execute()) {
        manejarErrorBaseDatos($stmt, $conexion, "No se pudo cancelar el evento");
    }
    
    $stmt->close();
    header("Location: panel.php");
    exit();
}

// Función para eliminar evento
if (isset($_GET['eliminar'])) {
    $eventoId = filter_input(INPUT_GET, 'eliminar', FILTER_VALIDATE_INT);
    
    if ($eventoId === false || $eventoId <= 0) {
        $_SESSION['error'] = "ID de evento inválido";
        header("Location: panel.php");
        exit();
    }

    // Validar que el evento pertenezca al usuario
    if (!validarPropiedadEvento($conexion, $eventoId, $usuarioId)) {
        $_SESSION['error'] = "No tienes permiso para eliminar este evento";
        header("Location: panel.php");
        exit();
    }

    // Eliminar el evento de la base de datos
    $query = "DELETE FROM eventos WHERE id = ? AND usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('ii', $eventoId, $usuarioId);
    
    if (!$stmt->execute()) {
        manejarErrorBaseDatos($stmt, $conexion, "No se pudo eliminar el evento");
    }
    
    $stmt->close();
    header("Location: panel.php");
    exit();
}

// Obtener los eventos activos (no realizados)
$eventos = obtenerEventos($usuarioId);

// Obtener los eventos realizados (historial)
$eventosRealizados = obtenerEventosRealizados($usuarioId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container p-4">
        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="text-center mb-4">
            <h1>Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?></h1>
        </div>

        <!-- Mostrar formulario para agregar un evento -->
        <div class="text-center mb-4">
            <a href="eventos.php" class="btn btn-primary">Agregar Evento</a>
        </div>

        <!-- Mostrar eventos no realizados -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Eventos Pendientes</h2>
            </div>
            <div class="card-body">
                <?php if (isset($eventos) && is_array($eventos) && count($eventos) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($eventos as $evento): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($evento['titulo']); ?></strong>
                                <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($evento['fecha']); ?> 
                                    <strong>Hora:</strong> <?php echo htmlspecialchars($evento['hora']); ?></p>
                                <p><strong>Creado el:</strong> <?php echo htmlspecialchars($evento['fecha_creacion']); ?></p>
                                
                                <p><strong>Estado:</strong> 
                                    <?php
                                    if ($evento['estado'] == 'Cancelado') {
                                        echo '<span class="text-danger">Cancelado</span>';
                                    } else {
                                        echo htmlspecialchars($evento['estado']);
                                    }
                                    ?>
                                </p>
                                
                                <!-- Botón de Edición Agregado -->
                                <a href="editar.php?id=<?php echo $evento['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                
                                <a href="panel.php?realizado=<?php echo $evento['id']; ?>" class="btn btn-success btn-sm">Marcar como Realizado</a>
                                <a href="panel.php?eliminar=<?php echo $evento['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este evento?')">Eliminar</a>
                                <a href="panel.php?cancelar=<?php echo $evento['id']; ?>" class="btn btn-secondary btn-sm" onclick="return confirm('¿Estás seguro de cancelar este evento?')">Cancelar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes eventos pendientes.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mostrar eventos realizados (historial) -->
        <div class="card mt-4">
            <div class="card-header bg-secondary text-white">
                <h2>Historial de Eventos</h2>
            </div>
            <div class="card-body">
                <?php if (isset($eventosRealizados) && is_array($eventosRealizados) && count($eventosRealizados) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($eventosRealizados as $evento): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($evento['titulo']); ?></strong>
                                <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($evento['fecha']); ?> 
                                    <strong>Hora:</strong> <?php echo htmlspecialchars($evento['hora']); ?></p>
                                <p><strong>Realizado el:</strong> <?php echo htmlspecialchars($evento['fecha_creacion']); ?></p>
                                <span class="text-muted">Tarea Realizada</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes eventos realizados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Botón de salir -->
<div class="text-center mt-4">
    <a href="logout.php" class="btn btn-danger btn-lg">Salir</a>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>