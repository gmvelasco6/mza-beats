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
	<?php
		session_start();
		if(empty($_SESSION["id"])){
			header("Location: ../../index.php");
			exit;
		}
	?>
	<nav>
		<img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
			<li class="nav-list-item"><a class="link" href="../../index.php">Ir a Inicio</a></li>
		</ul>
	</nav>
	<div class="formulario-container">
		<?php
			include("../../../backend/bd/conexion_bd.php");
			include("../../../backend/logica/administrar/control_cambiar_clave.php");
		?>
		<form class="formulario" align="center" method="post" action="">
			<h1 align="center">Cambiar Clave</h1>
			<h2>Nueva Clave</h2>
			<input type="password" id="password" class="input" name="password">
			<h2>Confirmar Clave</h2>
			<input type="password" id="new-password" class="input" name="new-password">

			<input type="submit" name="btn-cambiar" class="btn" value="Cambiar">
		</form>
	</div>
	<footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
