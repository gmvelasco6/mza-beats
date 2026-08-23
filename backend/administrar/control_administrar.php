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

    $consulta_update = "UPDATE bandas SET nombre= ?, descripcion= ?, imagen_principal= ?, imagen_fondo= ?, genero= ? WHERE id= ? ";
    $stmt_update = $conexion->prepare($consulta_update);

    if ($stmt_update) {

        $stmt_update->bind_param('sssssi', $name, $description, $img, $img_bg, $music_genre, $id);
        $stmt_update->execute();

    }else{
        die('Error al preparar la consulta: ' . $conexion->error);
    }
}
//ACTUALIZAR USUARIO
if (isset($_POST['actualizar-usuario'])) {
    $id = $_POST['id'];
    $state = $_POST['estado'];
    $creation_count = $_POST['cantidad_creaciones'];

    $consulta_update = "UPDATE usuarios SET estado= ?, cantidad_creaciones= ? WHERE id= ?";
    $stmt_update = $conexion->prepare($consulta_update);

    if($stmt_update){

        $stmt_update->bind_param('iii', $state, $creation_count, $id);
        $stmt_update->execute();

    }else{
        die('Error al preparar la consulta: ' . $conexion->error);
    }
}

//CONSULTAS PARA ELMINAR

//ELIMINAR BANDA
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $consulta_delete = "DELETE FROM bandas WHERE id=$id";
    $conexion->query($consulta_delete);
}
//ELIMINAR USUARIO 
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $consulta_delete = "DELETE FROM usuarios WHERE id='$id'";
    $conexion->query($consulta_delete);
}
//ELIMINAR CONSULTA 
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];

    $consulta_delete = "DELETE FROM consultas WHERE id='$id'";
    $conexion->query($consulta_delete);
}

//CONSULTAS PARA TODAS MOSTRAR LAS TABLAS EN administrar.php
$consultaConsult = "SELECT * FROM consultas";
$consultaUser = "SELECT * FROM usuarios";
$consultaIndie = "SELECT * FROM bandas WHERE genero='indie'";
$consultaPop = "SELECT * FROM bandas WHERE genero='pop'";
$consultaRock = "SELECT * FROM bandas WHERE genero='rock'";

//RESULTADO DE LAS CONSULTAS
$resultadoConsulta = $conexion->query($consultaConsult);
$resultadoUser = $conexion->query($consultaUser);
$resultadoIndie = $conexion->query($consultaIndie);
$resultadoPop = $conexion->query($consultaPop);
$resultadoRock = $conexion->query($consultaRock);
?>