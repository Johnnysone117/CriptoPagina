<?php
// Simular error de activación para demostrar la notificación
$error = isset($_GET['error']) ? $_GET['error'] : ''; // Usar 'activacion' como ejemplo de error
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Criptografía - Hashing</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+ua7Kw1TIq0FfDOMi7kMxWGbqu5T6zHoZ6nOPlYw5RlOqaO8Bz7VZGVF77" crossorigin="anonymous">
    <style>
        body {
            background-image: url('IM2.JPG'); /* Reemplaza esta URL con la imagen de criptografía */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
        }
        .main-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            backdrop-filter: blur(5px); /* Desenfoque de fondo */
            background-color: rgba(0, 0, 0, 0.6); /* Fondo oscuro translúcido */
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            padding: 3rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            background-color: white;
            width: 100%;
            max-width: 400px; /* Asegura que la tarjeta no sea demasiado ancha */
        }
        .btn-custom, .btn-register {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 18px;
            width: 100%; /* Asegura que los botones ocupen el 100% del ancho de su contenedor */
            margin-bottom: 10px;
        }
        .btn-custom:hover, .btn-register:hover {
            background-color: #0056b3;
        }
        .btn-register {
            background-color: #28a745;
        }
        .btn-register:hover {
            background-color: #218838;
        }
        .developers-section {
            margin-top: 4rem;
            padding: 2rem 0;
            background-color: #f8f9fa;
        }
        .developer-card {
            margin: 1rem;
            padding: 1.5rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .developer-card img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .developer-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .developer-description {
            font-size: 14px;
            color: #6c757d;
        }
        /* Estilos para los logos */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            width: calc(100% - 40px); /* Ajusta el ancho para dar espacio entre ambos logos */
            z-index: 10; /* Asegura que los logos estén sobre otros elementos */
        }
        .logo-container img {
            max-width: 150px; /* Ajusta el tamaño máximo de los logos */
            height: auto;
        }

        /* Notificación estilo Bootstrap */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            width: 300px;
            background-color: #f8d7da;
            border-left: 5px solid #dc3545;
            border-radius: 8px;
            padding: 10px 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .notification .btn-close {
            background-color: transparent;
            border: none;
            font-size: 20px;
            color: #dc3545;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Contenedor para los logos -->
    <div class="logo-container">
        <img src="logo1.png" alt="Logo Izquierda">
        <img src="logo2.png" alt="Logo Derecha">
    </div>

    <div class="container main-container">
        <div class="card">
            <h2 class="mb-4">Criptografía y Hashing</h2>
            <p class="text-muted mb-4">Explora cómo se utiliza el hash para asegurar datos de forma eficiente. Descubre los algoritmos y su aplicación en la seguridad informática.</p>
            <button class="btn btn-custom" onclick="window.location.href='Login.php'">Inicia sesión</button>
            <button class="btn btn-register" onclick="window.location.href='registro.php'">Registrarse</button>
        </div>
    </div>

    <!-- Apartado de Desarrolladores -->
    <div class="container developers-section text-center">
        <h3 class="mb-4">Conoce a los desarrolladores</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="D3.jpg" alt="Desarrollador 1">
                    <div class="developer-name">David Salvador</div>
                    <div class="developer-description">Especialista en seguridad informática, responsable del diseño del sistema de hash.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="D2.jpg" alt="Desarrollador 2">
                    <div class="developer-name">Jesus Emmanuel</div>
                    <div class="developer-description">Desarrolladora frontend, encargada de la interfaz de usuario y la experiencia de usuario.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="developer-card">
                    <img src="D1.jpg" alt="Desarrollador 3">
                    <div class="developer-name">Johnny Torres</div>
                    <div class="developer-description">Desarrollador backend, especializado en el manejo de bases de datos y la implementación de algoritmos de criptografía.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notificación de error de activación -->
    <?php
    if ($error == 'activacion') {
        echo '<div class="notification" role="alert">';
        echo '<strong class="me-2">¡Su cuenta fue activada!</strong>';
        echo 'Bienvenido.';
        echo '<button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
    ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyFWt7R1p73e/78b5wSTY2eMk/2nV8in0zvC9FI4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-J1Fz60VfbdaOM4dTfPftIu4IQAXySZChpIK+6Kp+dqO1a9aybD8L+M1y2e8vBd+8" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0FfDOMi7kMxWGbqu5T6zHoZ6nOPlYw5RlOqaO8Bz7VZGVF77" crossorigin="anonymous"></script>
</body>
</html>
