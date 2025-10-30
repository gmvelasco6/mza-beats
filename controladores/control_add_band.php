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
$genero = trim($_POST['genero'] ?? '');

if ($nombre === '' || $descripcion === '') {
    echo 'ERROR: Completa nombre y descripción';
    exit;
}

// Verificar si existe la columna genero
$hasGenero = false;
$chk = $conexion->query("SHOW COLUMNS FROM bandas LIKE 'genero'");
if ($chk && $chk->num_rows > 0) { 
    $hasGenero = true; 
}

// Validar género permitido (independientemente de si la columna existe); si no viene, se permite vacío
$generosPermitidos = ['indie', 'pop', 'rock'];
if ($genero !== '' && !in_array($genero, $generosPermitidos)) {
    echo 'ERROR: Género no válido';
    exit;
}

// Si no existe la columna y el usuario eligió un género válido, intentar crear la columna automáticamente
if (!$hasGenero && $genero !== '') {
    $conexion->query("ALTER TABLE bandas ADD COLUMN genero VARCHAR(50) NULL");
    // Si se pudo crear, marcar como disponible
    $chk2 = $conexion->query("SHOW COLUMNS FROM bandas LIKE 'genero'");
    if ($chk2 && $chk2->num_rows > 0) {
        $hasGenero = true;
    }
}

if ($hasGenero && $genero !== '') {
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
    // Redirigir a la página del género seleccionado si existe, sino a indie por defecto
    $redireccion = ($hasGenero && $genero !== '') ? $genero : 'indie';
    header('Location: ../generos/' . $redireccion . '.php');
    exit;
}

echo 'ERROR: ' . $stmt->error;
?>