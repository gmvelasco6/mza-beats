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

    $sql_update = "UPDATE bandas SET nombre= ?, descripcion= ?, imagen_principal= ?, imagen_fondo= ?, genero= ? WHERE id= ? ";
    $stmt_update = $conexion->prepare($sql_update);

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

    $sql_update = "UPDATE usuarios SET estado= ?, cantidad_creaciones= ? WHERE id= ?";
    $stmt_update = $conexion->prepare($sql_update);

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

<<<<<<< HEAD


//CONSULTAS PARA  MOSTRAR TODAS LAS TABLAS EN administrar.php
=======
//CONSULTAS PARA TODAS MOSTRAR LAS TABLAS EN administrar.php
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
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