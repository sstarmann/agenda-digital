<?php
session_start();  // Iniciar la sesión
session_unset();  // Eliminar todas las variables de sesión
session_destroy();  // Destruir la sesión

// Redirigir al usuario a la página de inicio de sesión (index.php)
header("Location: index.php");
exit();
?>
