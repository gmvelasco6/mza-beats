<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../css/php-style.css">
	<link rel="shortcut icon" href="../../images/logo/page-icon.png" type="image/x-icon">
	<title>Inicio de Sesion</title>
</head>
<body>
	<nav>
		<img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="../../index.php">Ir a Inicio</a></li>
		</ul>
	</nav>
	<div class="formulario-container">
		<?php
			include("../../bd/conexion_bd.php");
			include("../../controladores/login-signin/control_sesion.php");
		?>
		<form class="formulario" align="center" method="post" action="">
			<h1 align="center">Iniciar Sesion</h1>
			<h2>Usuario</h2>
			<input type="text" id="user" class="input" name="user">
			<h2>Contraseña</h2>
			<input type="password" id="password" class="input" name="password">
			<p>No has creado un usuario? <a href="register.php">Registrate</a></p>
			<input type="submit" name="btn-iniciar" class="btn" value="Iniciar Sesion">
		</form>
	</div>
	<footer>
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>