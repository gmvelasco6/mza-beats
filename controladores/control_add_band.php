<?php
// Controlador minimalista para agregar banda
session_start();

// Verificar permisos básicos
if (empty($_SESSION['id']) || $_SESSION['state'] != '1') {
    http_response_code(403);
    echo 'ERROR: No autorizado';
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'ERROR: Método no permitido';
    exit;
}

include __DIR__ . '/../bd/conexion_bd.php';

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$genero = trim($_POST['genero'] ?? 'indie');

if ($nombre === '' || $descripcion === '') {
    echo 'ERROR: Completa nombre y descripción';
    exit;
}

// Detectar si existe columna genero (simple y rápido)
$hasGenero = false;
$chk = $conexion->query("SHOW COLUMNS FROM bandas LIKE 'genero'");
if ($chk && $chk->num_rows > 0) { $hasGenero = true; }

if ($hasGenero) {
    $sql = "INSERT INTO bandas (nombre, descripcion, genero) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) { echo 'ERROR: ' . $conexion->error; exit; }
    $stmt->bind_param('sss', $nombre, $descripcion, $genero);
} else {
    $sql = "INSERT INTO bandas (nombre, descripcion) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) { echo 'ERROR: ' . $conexion->error; exit; }
    $stmt->bind_param('ss', $nombre, $descripcion);
}

if ($stmt->execute()) {
    // Redirigir directamente a la página de Indie
    header('Location: ../generos/indie.php');
    exit;
}

echo 'ERROR: ' . $stmt->error;
?>
