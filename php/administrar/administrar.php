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
    if(empty($_SESSION["id"]) || $_SESSION["state"] != "67"){
        header("Location: ../../index.php");
        exit;
    }
?>
	<nav class="nav-admin">
		<img class="logo" src="../../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a href="../../index.php">Volver a Inicio</a></li>
		</ul>
	</nav>
    <div class="intro">
            <ul class="admin-nav">
                <li><a href="#tabla-usuarios">Usuarios</a></li>
                <li><a href="#tabla-indie">Indie</a></li>
                <li><a href="#tabla-pop">Pop</a></li>
                <li><a href="#tabla-rock">Rock</a></li>
                <li><a href="#tabla-consultas">Consultas</a></li>
            </ul>
        </div>
    <?php
        include("../../bd/conexion_bd.php");
        include("../../controladores/administrar/control_administrar.php")
?>
    <main class="table-container">
        <h1 id="tabla-usuarios" align="center">Usuarios</h1>
        <table class="table-usuarios" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Estado</th>
                <th>Cantidad de Creaciones</th>
                <th>Acción</th>
            </tr>
            <!--MOSTRAR USUARIOS-->
        <?php while ($filaUser = $resultadoUser->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $filaUser['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $filaUser['nombre']; ?>" readonly></td>
                    <td><input type="text" name="apellido" value="<?php echo $filaUser['apellido']; ?>" readonly></td>
                    <td><input type="text" name="usuario" value="<?php echo $filaUser['usuario']; ?>" readonly></td>
                    <td><input type="text" name="clave" value="<?php echo $filaUser['clave']; ?>" readonly></td>
                    <td><input type="text" name="estado" value="<?php echo $filaUser['estado']; ?>"></td>
                    <td><input type="text" name="cantidad_creaciones" value="<?php echo $filaUser['cantidad_creaciones']; ?>"></td>

                    <td>
                        <button type="submit" name="actualizar-usuario" class="btn-update">Actualizar</button>
                        <button type="submit" name="eliminar" class="btn-delete">Eliminar</button>
                    </td>

                </form>
            </tr>
        <?php }?>
        </table>
        <h1 id="tabla-indie" align="center">Bandas Indie</h1>
        <table class="table-bandas" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Imagen Principal</th>
                <th>Imagen Fondo</th>
                <th>Genero</th>
                <th>Acción</th>
            </tr>
            <!--MOSTAR BANDAS-->
        <?php while ($filaIndie = $resultadoIndie->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $filaIndie['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $filaIndie['nombre']; ?>"></td>
                    <td><textarea name="descripcion" class="descripcion"><?php echo $filaIndie['descripcion']; ?></textarea></td>
                    <td><input type="text" name="imagen_principal" value="<?php echo $filaIndie['imagen_principal']; ?>"></td>
                    <td><input type="text" name="imagen_fondo" value="<?php echo $filaIndie['imagen_fondo']; ?>"></td>
                    <td><input type="text" name="genero" value="<?php echo $filaIndie['genero']; ?>"></td>

                    <td>
                        <button type="submit" name="actualizar" class="btn-update">Actualizar</button>
                        <button type="submit" name="eliminar" class="btn-delete">Eliminar</button>
                    </td>

                </form>
            </tr>
        <?php }?>
        </table>
        <h1 id="tabla-pop" align="center">Bandas Pop</h1>
        <table class="table-bandas" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Imagen Principal</th>
                <th>Imagen Fondo</th>
                <th>Genero</th>
                <th>Acción</th>
           </tr>
            <!--MOSTAR BANDAS-->
        <?php while ($filaPop = $resultadoPop->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $filaPop['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $filaPop['nombre']; ?>"></td>
                    <td><textarea name="descripcion" class="descripcion"><?php echo $filaPop['descripcion']; ?></textarea></td>
                    <td><input type="text" name="imagen_principal" value="<?php echo $filaPop['imagen_principal']; ?>"></td>
                    <td><input type="text" name="imagen_fondo" value="<?php echo $filaPop['imagen_fondo']; ?>"></td>
                    <td><input type="text" name="genero" value="<?php echo $filaPop['genero']; ?>"></td>

                    <td>
                        <button type="submit" name="actualizar" class="btn-update">Actualizar</button>
                        <button type="submit" name="eliminar" class="btn-delete">Eliminar</button>
                    </td>
                    
                </form>
            </tr>
        <?php }?>
        </table>
        <h1 id="tabla-rock" align="center">Bandas Rock</h1>
        <table class="table-bandas" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Imagen Principal</th>
                <th>Imagen Fondo</th>
                <th>Genero</th>
                <th>Acción</th>
            </tr>
            <!--MOSTAR BANDAS-->
        <?php while ($filaRock = $resultadoRock->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $filaRock['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $filaRock['nombre']; ?>"></td>
                    <td><textarea name="descripcion" class="descripcion"><?php echo $filaRock['descripcion']; ?></textarea></td>
                    <td><input type="text" name="imagen_principal" value="<?php echo $filaRock['imagen_principal']; ?>"></td>
                    <td><input type="text" name="imagen_fondo" value="<?php echo $filaRock['imagen_fondo']; ?>"></td>
                    <td><input type="text" name="genero" value="<?php echo $filaRock['genero']; ?>"></td>

                    <td>
                        <button type="submit" name="actualizar" class="btn-update">Actualizar</button>
                        <button type="submit" name="eliminar" class="btn-delete">Eliminar</button>
                    </td>

                </form>
            </tr>
        <?php }?>
        </table>

        <h1 id="tabla-consultas" align="center">Consultas</h1>
        <table class="table-usuarios" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Razon</th>
                <th>Email</th>
                <th>Acción</th>
            </tr>
            <!--MOSTRAR USUARIOS-->
        <?php while ($filaConsultas = $resultadoConsulta->fetch_assoc()){ ?>
            <tr align="center">
                <form class="formulario" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $filaConsultas['id']; ?>">
                    <td><input type="text" name="nombre" value="<?php echo $filaConsultas['nombre']; ?>"></td>
                    <td><textarea name="descripcion" class="descripcion"><?php echo $filaConsultas['razon']; ?></textarea></td>
                    <td><input type="text" name="email" value="<?php echo $filaConsultas['email']; ?>"></td>
                    <td>
                        <button type="submit" name="eliminar" class="btn-delete">Eliminar</button>
                    </td>
                </form>
            </tr>
        <?php } ?>
        </table>
    </main>
    <footer>
    </footer>
</body>
</html>