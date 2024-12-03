<?php
class Usuario {
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para registrar un usuario
    public function registrarUsuario($nombre, $email, $password)
    {
        // Validar y sanitizar el email
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El email no es válido.";
        }

        // Sanitizar el nombre
        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

        // Verificar si el correo ya está registrado
        $sqlCheck = "SELECT id FROM usuarios WHERE email = ?";
        if ($stmtCheck = $this->conexion->prepare($sqlCheck)) {
            $stmtCheck->bind_param("s", $email);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                return "El email ya está registrado.";
            }
            $stmtCheck->close();
        } else {
            return "Error en la preparación de la consulta de verificación: " . $this->conexion->error;
        }

        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL para registrar al usuario
        $sqlInsert = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        if ($stmt = $this->conexion->prepare($sqlInsert)) {
            $stmt->bind_param("sss", $nombre, $email, $hashed_password);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return "Usuario registrado correctamente.";
            } else {
                return "Error al registrar al usuario: " . $stmt->error;
            }
        } else {
            return "Error en la preparación de la consulta: " . $this->conexion->error;
        }
    }
}
?>
