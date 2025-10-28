<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/php-style.css">
	<link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
	<title>Administrar Bandas</title>
</head>
<body>
	<nav>
		<img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="../index.php">Volver a Incio</a></li>
		</ul>
	</nav>
    <?php include("../bd/conexion_bd.php");

        // Procesar actualización de estado si se envió el formulario
        if (isset($_POST['actualizar'])) {
            $id = $_POST['id'];
            $name = $_POST['nombre'];
            $description = $_POST['descripcion'];
            $img = $_POST['imagen_principal'];
            $img_bg = $_POST['imagen_fondo'];

            $sql_update = "UPDATE bandas SET nombre='$name', descripcion='$description', imagen_principal='$img', imagen_fondo='$img_bg' WHERE id=$id";
            $conexion->query($sql_update);
        }
        $sql = "SELECT * FROM bandas";
        $resultado = $conexion->query($sql);
    ?>
    <main class="table-container">
        <h1 align="center">Bandas</h1>
        <table class="table-bandas" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Imagen Principal</th>
                <th>Imagen Fondo</th>
                <th>Acción</th>
            </tr>
        <?php while ($fila = $resultado->fetch_assoc()){ ?>
            <tr>
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $fila['nombre']; ?>"></td>
                    <td><textarea name="descripcion" class="descripcion"><?php echo $fila['descripcion']; ?></textarea></td>
                    <td><input type="text" name="imagen_principal" value="<?php echo $fila['imagen_principal']; ?>"></td>
                    <td><input type="text" name="imagen_fondo" value="<?php echo $fila['imagen_fondo']; ?>"></td>

                    <td><button type="submit" name="actualizar" class="btn-update">Actualizar</button></td>
                </form>
            </tr>
        <?php }?>
    </main>

</body>
</html>