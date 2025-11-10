<?php
session_start();
include("../bd/conexion_bd.php");

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$genero = trim($_POST['genero'] ?? '');

//CONSULTA
$sql = "INSERT INTO bandas (nombre, descripcion, genero) VALUES (?, ?, ?)";

//PREPARAR CONSULTA
$stmt = $conexion->prepare($sql);
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
    $sqlUpdate = "UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ?";
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