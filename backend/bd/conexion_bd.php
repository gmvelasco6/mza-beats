<?php
$host     = $_ENV['MYSQLHOST']     ?? $_SERVER['MYSQLHOST']     ?? getenv('MYSQLHOST')     ?: 'localhost';
$user     = $_ENV['MYSQLUSER']     ?? $_SERVER['MYSQLUSER']     ?? getenv('MYSQLUSER')     ?: 'root';
$pass     = $_ENV['MYSQLPASSWORD'] ?? $_SERVER['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?: '';
$db_name  = $_ENV['MYSQLDATABASE'] ?? $_SERVER['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?: 'mzabeats';
$port     = $_ENV['MYSQLPORT']     ?? $_SERVER['MYSQLPORT']     ?? getenv('MYSQLPORT')     ?: '3306';

$conexion = new mysqli($host, $user, $pass, $db_name, (int)$port);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>