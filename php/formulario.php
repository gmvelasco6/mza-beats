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
            <li class="nav-list-item"><a class="link" href="../index.php">Ir a Inicio</a></li>
		</ul>
	</nav>
	
	<?php
		include("../bd/conexion_bd.php");
		include("../controladores/control_formulario.php");
	?>
	<div class="formulario-container">	
		<h1>¡Se parte de MzaBeats!</h1>
		<p>Completá este formulario <br> para poder ser parte de la página web.</p>	
		<form class="formulario" method="post">
			<h2>Nombre</h2>
			<input type="text" id="user" class="input" name="user" placeholder="Nombre de la Banda">
			<h2>Razon</h2>
			<textarea id="razon" name="razon" required placeholder="Escribi la razon por la cual queres aparecer en MzaBeats..." cols="35"  rows="6"></textarea>
			<h2>Email</h2>
			<input type="password" id="password" class="input" name="password">
			<br>
			<input type="submit" name="btn-iniciar" class="btn" value="Enviar">
		</form>
	</div>
	
	<footer>
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>