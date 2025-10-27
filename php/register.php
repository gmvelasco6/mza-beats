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
	<?php
		include("../bd/conexion_bd.php");
		include("../controladores/control_registro.php");
	?>
	<div class="form-container">
		<form class="formulario" align="center" method="post" action="">
			<h1 align="center">Registrarse</h1>	
			<input type="text" id="name" class="input" name="name" placeholder="  Nombre">
			<input type="text" id="lastname" class="input" name="lastname" placeholder="  Apellido">
			<input type="text" id="user" class="input" name="user" placeholder="  Usuario">
			<input type="password" id="password" class="input" name="password" placeholder="  Contraseña">
			<p>Ya tenes un usuario? <a href="login.php">Inicia Sesion</a></p>
			<input type="submit" name="btn-registro" class="btn" value="Registrarme">
		</form>
	</div>
	
</body>
</html>