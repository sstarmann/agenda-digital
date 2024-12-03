<?php
// Credenciales de la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'agenda_db';

// Conexión a la base de datos
$conexion = mysqli_connect($host, $username, $password, $database);


// Verificar la conexión
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

// Función para obtener la conexión
function conectarBaseDatos() {
    global $conexion;
    return $conexion;
}
?>
