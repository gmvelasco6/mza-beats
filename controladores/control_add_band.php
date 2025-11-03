<?php
session_start();
include("../bd/conexion_bd.php");

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$genero = trim($_POST['genero'] ?? '');

if ($genero !== '') {
    $sql = "INSERT INTO bandas (nombre, descripcion, genero) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die('Error al preparar la consulta: ' . $conexion->error);
    }
    $stmt->bind_param('sss', $nombre, $descripcion, $genero);

    if ($stmt->execute()) {
        // Después de insertar la banda, actualizar cantidad_creaciones
        $userId = $_SESSION['id'];
        $sqlUpdateUser = "UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ?";
        $stmtUpdateUser = $conexion->prepare($sqlUpdateUser);
        if ($stmtUpdateUser) {
            $stmtUpdateUser->bind_param('i', $userId);
            $stmtUpdateUser->execute();
            $_SESSION['creation_count']--;
        }
        
        header('Location: ../generos/' . $genero . '.php');
        exit;
    }
}

echo 'ERROR: ' . $stmt->error;
?>