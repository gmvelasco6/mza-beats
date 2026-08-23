<?php
session_start();

// Cargar la lógica ANTES de renderizar cualquier salida HTML
include("../../../backend/bd/conexion_bd.php");
include("../../../backend/login-signin/control_registro.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/php-style.css">
    <link rel="shortcut icon" href="../../images/logo/page-icon.png" type="image/x-icon">
    <title>Registro</title>
</head>
<body>
    <nav>
        <img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
        <ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="../../index.php">Ir a Inicio</a></li>
        </ul>
    </nav>
    <div class="formulario-container">
        <form class="formulario" method="post" action="">
            <h1>Registrarse</h1>

            <?php 
            if (!empty($error_registro)) {
                echo $error_registro;
            }
            ?>

            <h2>Nombre</h2>
            <input type="text" id="name" class="input" name="name">
            <h2>Usuario</h2>
            <input type="text" id="user" class="input" name="user">
            <h2>Contraseña</h2>
            <input type="password" id="password" class="input" name="password">
            <p>¿Ya tienes un usuario? <a href="login.php">Inicia Sesión</a></p>
            <input type="submit" name="btn-registro" class="btn" value="Registrarme">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>