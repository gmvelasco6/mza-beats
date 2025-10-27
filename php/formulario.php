<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/php-style.css">
    <link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
	<title>Inicio de Sesion</title>
</head>
<body>
	<nav>
		<img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="javascript:history.back()">Volver</a></li>
		</ul>
	</nav>
	<h1 align="center">¡Se parte de MzaBeats!</h1>
	<h2 align="center">Completá este formulario para poder ser parte de la página web.</h2>
	<?php
		include("../bd/conexion_bd.php");
		include("../controladores/control_formulario.php");
	?>
	<div class="form-container">
		<form class="formulario" align="center" method="post" action="">
			
			<input type="text" id="band" name="band" placeholder="  Nombre de la Banda">
			<textarea name="description" id="description" class="descripcion" placeholder="  Escribí una descripción detallada de tu banda..." rows="8" cols="33"></textarea>
			<input type="text" id="user" name="user" placeholder="  Usuario">
			<input type="password" id="password" name="password" placeholder="  Contraseña">
			<br>
			<input type="submit" name="btn-iniciar" class="btn" value="Enviar">
		</form>
	</div>
	
</body>
</html>