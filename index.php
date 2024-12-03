<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>

    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="css/estilos_index.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Incluir Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <div class="container">
        <div class="card p-4">
            <div class="card-header bg-primary text-white text-center">
                <img src="imagenes/1.JPG" alt="logo" class="mb-3">
                <h3 class ="titulo" >Bienvenido a la Agenda</h3>
            </div>
            <div class="card-body text-center">
                <p class ="texto-opcion"> Selecciona una opción para continuar:</p>
                <a href="login.php" class="btn btn-primary w-100 py-2 mb-3">
    <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
</a>

<a href="registrar.php" class="btn btn-secondary w-100 py-2">
    <i class="fa-solid fa-address-card"></i> Registrarse
</a>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
