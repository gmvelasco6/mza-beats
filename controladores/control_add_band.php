<?php
session_start();
include("../bd/conexion_bd.php");

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$genero = trim($_POST['genero'] ?? '');

// sirve para que el campo de genero no este vacio y (? ? ?) sirve para evitar sql injection y hace que el codigo sea mas seguro
if ($genero !== '') {
    $consulta = "INSERT INTO bandas (nombre, descripcion, genero) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($consulta);
    // sirve para validar si la colsulta se preparo correctamente y si no muestra el error
    if (!$stmt) {
        die('Error al preparar la consulta: ' . $conexion->error);
    }
    // se usa para asociar los parametros a la consulta preparada osea a los (? ? ?) y el 'sss' significa parametros de tipo string 
    $stmt->bind_param('sss', $nombre, $descripcion, $genero);
    // sirve para la ejecucion e insercion de la banda 
    if ($stmt->execute()) {
        // Después de insertar la banda, actualizar cantidad_creaciones
        $userId = $_SESSION['id'];
        $consultaUpdateUser = "UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ?";
        $stmtUpdateUser = $conexion->prepare($consultaUpdateUser);
        if ($stmtUpdateUser) {
            $stmtUpdateUser->bind_param('i', $userId);
            $stmtUpdateUser->execute();
            $_SESSION['creation_count']--;
        }
        
        header('Location: ../generos/' . $genero . '.php');
        exit;
    }
}
//CONSULTA
$consulta = "INSERT INTO bandas (nombre, descripcion, genero) VALUES (?, ?, ?)";

//PREPARAR CONSULTA
$stmt = $conexion->prepare($consulta);
if (!$stmt) {
    die('Error al preparar la consulta: ' . $conexion->error);
}

//ASIGNAR TIPO Y VALOR A ? ? ?
$stmt->bind_param('sss', $nombre, $descripcion, $genero);

//EJECUTAR EL STATEMENT
if ($stmt->execute()) {
    //ACTUALIZAR CANTIDAD DE CREACIONES
    $userId = $_SESSION['id'];
    //CONSULTA
    $consultaUpdate = "UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ?";
    //PREPARO
    $stmtUpdate = $conexion->prepare($sqlUpdate);
    if ($stmtUpdate) {
        //ASIGNO
        $stmtUpdate->bind_param('i', $userId);
        //EJECUTO
        $stmtUpdate->execute();
        $_SESSION['creation_count']--;
    }else{
        die('Error al actualizar la consulta: ' . $conexion->error);
    }
    //REDIRIGIR A LA PAGINA CORRESPONDIENTE
    header('Location: ../generos/' . $genero . '.php');
    exit;
}else{
    die('Error al ejecutar la consulta: ' . $conexion->error);
}
?>