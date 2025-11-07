<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../css/php-style.css">
	<link rel="shortcut icon" href="../../images/logo/page-icon.png" type="image/x-icon">
	<title>Administrar Bandas</title>
</head>
<body>
<?php
    session_start();
    if(empty($_SESSION["id"])){
        header("Location: ../../index.php");
        exit;
    }
?>
	<nav class="nav-admin">
		<img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a href="../../index.php">Ir a Inicio</a></li>
		</ul>
	</nav>
    <?php
        include("../../bd/conexion_bd.php");
        $id=$_SESSION['id'];
        //ACTUALIZAR USUARIO
        if (isset($_POST['actualizar'])) {
            $id = $_POST['id'];
            $name = $_POST['nombre'];
            $lastname = $_POST['apellido'];
            $user = $_POST['usuario'];
            $pass = $_POST['clave'];

            $sql_update = "UPDATE usuarios SET nombre='$name', apellido='$lastname', usuario='$user', clave='$pass' WHERE id='$id'";
            $conexion->query($sql_update);
        }

        //Consultas para cada genero
        $sql = "SELECT * FROM usuarios WHERE id='$id'";
        $resultado = $conexion->query($sql);
    ?>
    <main class="table-container">
        <h1 align="center">Mi cuenta</h1>
        <table class="table-usuarios" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Acción</th>
            </tr>
        <?php while ($fila = $resultado->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $fila['nombre']; ?>"></td>
                    <td><input type="text" name="apellido" value="<?php echo $fila['apellido']; ?>"></td>
                    <td><input type="text" name="usuario" value="<?php echo $fila['usuario']; ?>"></td>
                    <td><a class="cambiar-contraseña" href="../administrar/cambiar-clave.php">Cambiar</a></td>

                    <td>    
                        <button type="submit" name="actualizar" class="btn-update-usuario">Actualizar</button>
                    </td>

                </form>
            </tr>
        <?php }?>
        </table>
    </main>
    <footer>
    </footer>
</body>
</html>