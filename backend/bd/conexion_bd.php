<?php
    
$host     = getenv('MYSQLHOST')     ?: 'localhost';
$user     = getenv('MYSQLUSER')     ?: 'root';
$pass     = getenv('MYSQLPASSWORD') ?: '';
$db_name  = getenv('MYSQLDATABASE') ?: 'mzabeats';
$port     = getenv('MYSQLPORT')     ?: '3306';

$conexion = new mysqli($host, $user, $pass, $db_name, (int)$port);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>