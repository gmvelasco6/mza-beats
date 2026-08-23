<?php
session_start();

// 1. Incluir la base de datos y procesar la lógica de sesión ANTES del HTML
include("../../../backend/bd/conexion_bd.php");
include("../../../backend/login-signin/control_sesion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/php-style.css">
    <link rel="shortcut icon" href="../../images/logo/page-icon.png" type="image/x-icon">
    <title>Inicio de Sesión</title>
</head>
<body>
    <nav>
        <img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
        <ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="../../index.php">Ir a Inicio</a></li>
        </ul>
    </nav>
    <div class="formulario-container">
        <form class="formulario" align="center" method="post" action="">
            <h1 align="center">Iniciar Sesión</h1>
            
            <?php 
            // Mostrar los mensajes de error/advertencia que genere control_sesion.php
            if (isset($error_login)) {
                echo $error_login;
            }
            ?>

            <h2>Usuario</h2>
            <input type="text" id="user" class="input" name="user">
            <h2>Contraseña</h2>
            <input type="password" id="password" class="input" name="password">
            <p>¿No has creado un usuario? <a href="register.php">Regístrate</a></p>
            <input type="submit" name="btn-iniciar" class="btn" value="Iniciar Sesion">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>