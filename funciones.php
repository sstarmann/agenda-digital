<?php
// funciones.php

// Función para agregar un evento a la base de datos
function agregarEvento($usuarioId, $titulo, $descripcion, $fecha, $hora) {
    global $conexion;

    // Consulta SQL para insertar el evento
    $sql = "INSERT INTO eventos (usuario_id, titulo, descripcion, fecha, hora) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("issss", $usuarioId, $titulo, $descripcion, $fecha, $hora);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al agregar el evento: " . $conexion->error);
    }
}

// Función para obtener todos los eventos de un usuario
function obtenerEventos($usuarioId) {
    global $conexion;

    // Consulta SQL para obtener los eventos del usuario
    $sql = "SELECT * FROM eventos WHERE usuario_id = ? ORDER BY fecha, hora";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $eventos = [];
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        $stmt->close();
        
        return $eventos;
    } else {
        throw new Exception("Error al obtener los eventos: " . $conexion->error);
    }
}

// Función para eliminar un evento de la base de datos
function eliminarEvento($eventoId, $usuarioId) {
    global $conexion;

    // Consulta SQL para eliminar un evento
    $sql = "DELETE FROM eventos WHERE id = ? AND usuario_id = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ii", $eventoId, $usuarioId);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al eliminar el evento: " . $conexion->error);
    }
}

// Función para obtener un solo evento por ID
function obtenerEventoPorId($eventoId, $usuarioId) {
    global $conexion;

    // Consulta SQL para obtener un evento específico
    $sql = "SELECT * FROM eventos WHERE id = ? AND usuario_id = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ii", $eventoId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return null; // Si no se encuentra el evento
        }
        $stmt->close();
    } else {
        throw new Exception("Error al obtener el evento: " . $conexion->error);
    }
}

// Función para actualizar los detalles de un evento
function actualizarEvento($eventoId, $usuarioId, $titulo, $descripcion, $fecha, $hora) {
    global $conexion;

    // Consulta SQL para actualizar un evento
    $sql = "UPDATE eventos SET titulo = ?, descripcion = ?, fecha = ?, hora = ? WHERE id = ? AND usuario_id = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssii", $titulo, $descripcion, $fecha, $hora, $eventoId, $usuarioId);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al actualizar el evento: " . $conexion->error);
    }
}

// Función para validar y limpiar datos
function limpiarDatos($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Función para comprobar si el usuario tiene permiso para editar un evento
function validarEventoPropietario($eventoId, $usuarioId) {
    global $conexion;
    
    // Consulta SQL para verificar que el evento pertenece al usuario
    $sql = "SELECT * FROM eventos WHERE id = ? AND usuario_id = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ii", $eventoId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            throw new Exception("No tienes permiso para editar este evento.");
        }
        $stmt->close();
    } else {
        throw new Exception("Error al verificar el permiso del evento: " . $conexion->error);
    }
}
function cancelarEvento($eventoId, $usuarioId) {
    global $conexion;
    
    // Asegúrate de que el evento pertenece al usuario
    $stmt = $conexion->prepare("SELECT * FROM eventos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $eventoId, $usuarioId);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 0) {
        throw new Exception("Este evento no existe o no te pertenece.");
    }

    // Actualizar el estado del evento a "Cancelado"
    $stmt = $conexion->prepare("UPDATE eventos SET estado = 'Cancelado' WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $eventoId, $usuarioId);
    $stmt->execute();

    if ($stmt->affected_rows == 0) {
        throw new Exception("No se pudo cancelar el evento.");
    }
}
function marcarComoRealizado($eventoId, $usuarioId) {
    global $conexion;

    // Verifica que el evento pertenece al usuario y no está cancelado
    $query = "SELECT * FROM eventos WHERE id = ? AND usuario_id = ? AND estado != 'Cancelado'";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $eventoId, $usuarioId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Actualiza el estado a "realizado"
        $updateQuery = "UPDATE eventos SET realizado = 1 WHERE id = ? AND usuario_id = ?";
        $updateStmt = $conexion->prepare($updateQuery);
        $updateStmt->bind_param("ii", $eventoId, $usuarioId);

        if (!$updateStmt->execute()) {
            throw new Exception("Error al marcar el evento como realizado: " . $conexion->error);
        }
    } else {
        throw new Exception("El evento no existe, no te pertenece o ya está cancelado.");
    }
}

?>
