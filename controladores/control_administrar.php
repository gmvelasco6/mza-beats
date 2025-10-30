<?php
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
//ELIMINAR BANDA
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM bandas WHERE id=$id";
    $conexion->query($sql_delete);
}

//ACTUALIZAR USUARIO
if (isset($_POST['actualizar-usuario'])) {
    $id = $_POST['id'];
    $state = $_POST['estado'];

    $sql_update = "UPDATE usuarios SET estado='$state' WHERE id='$id'";
    $conexion->query($sql_update);
}

//ELIMINAR USUARIO
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM usuarios WHERE id='$id'";
    $conexion->query($sql_delete);
}

//Consultas para cada genero
$sqlUser = "SELECT * FROM usuarios";
$sqlIndie = "SELECT * FROM bandas WHERE genero='indie'";
$sqlPop = "SELECT * FROM bandas WHERE genero='pop'";
$sqlRock = "SELECT * FROM bandas WHERE genero='rock'";

$resultadoUser = $conexion->query($sqlUser);
$resultadoIndie = $conexion->query($sqlIndie);
$resultadoPop = $conexion->query($sqlPop);
$resultadoRock = $conexion->query($sqlRock);
?>