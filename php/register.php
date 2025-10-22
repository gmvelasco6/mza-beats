<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/php-style.css">
	<link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
	<title>Registro</title>
</head>
<body>
	<nav>
		<img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="javascript:history.back()">Volver</a></li>
		</ul>
	</nav>
	<h1 align="center">Registrarse</h1>	
	<form align="center" method="post" action="">
		<?php
			include("../bd/conexion_bd.php");
			include("../controladores/control_registro.php");
		?>
        <h2>Nombre</h2>
		<input type="text" id="name" class="input" name="name">
		<h2>Apellido</h2>
		<input type="text" id="lastname" class="input" name="lastname">
		<h2>Usuario</h2>
		<input type="text" id="user" class="input" name="user">
		<h2>Contraseña</h2>
		<input type="password" id="password" class="input" name="password">
		<p>Ya tenes un usuario? <a href="login.php">Inicia Sesion</a></p>
		<input type="submit" name="btn-registro" class="btn" value="Registrarme">
	</form>
</body>
</html>