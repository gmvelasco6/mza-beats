<?php
//CONSULTAS PARA ACTUALIZAR

//ACTUALIZAR BANDA
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $name = $_POST['nombre'];
    $description = $_POST['descripcion'];
    $img = $_POST['imagen_principal'];
    $img_bg = $_POST['imagen_fondo'];
    $music_genre = $_POST['genero'];

    $sql_update = "UPDATE bandas SET nombre='$name', descripcion='$description', imagen_principal='$img', imagen_fondo='$img_bg', genero='$music_genre' WHERE id='$id'";
    $conexion->query($sql_update);
}
//ACTUALIZAR USUARIO
if (isset($_POST['actualizar-usuario'])) {
    $id = $_POST['id'];
    $state = $_POST['estado'];
    $creation_count = $_POST['cantidad_creaciones'];

    $sql_update = "UPDATE usuarios SET estado='$state', cantidad_creaciones='$creation_count' WHERE id='$id'";
    $conexion->query($sql_update);
}

//CONSULTAS PARA ELMINAR

//ELIMINAR BANDA
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM bandas WHERE id=$id";
    $conexion->query($sql_delete);
}
//ELIMINAR USUARIO 
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM usuarios WHERE id='$id'";
    $conexion->query($sql_delete);
}
//ELIMINAR CONSULTA 
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM consultas WHERE id='$id'";
    $conexion->query($sql_delete);
}



//CONSULTAS PARA  MOSTRAR TODAS LAS TABLAS EN administrar.php
$sqlConsulta = "SELECT * FROM consultas";
$sqlUser = "SELECT * FROM usuarios";
$sqlIndie = "SELECT * FROM bandas WHERE genero='indie'";
$sqlPop = "SELECT * FROM bandas WHERE genero='pop'";
$sqlRock = "SELECT * FROM bandas WHERE genero='rock'";

//RESULTADO DE LAS CONSULTAS
$resultadoConsulta = $conexion->query($sqlConsulta);
$resultadoUser = $conexion->query($sqlUser);
$resultadoIndie = $conexion->query($sqlIndie);
$resultadoPop = $conexion->query($sqlPop);
$resultadoRock = $conexion->query($sqlRock);
?>