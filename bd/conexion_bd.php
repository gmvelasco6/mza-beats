<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "login";

// Conectar
$conexion = new mysqli($host, $user, $pass, $db);
$conexion->set_charset("utf8");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>