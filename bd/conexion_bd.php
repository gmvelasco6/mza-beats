<?php
// Conexion a la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$db = "mzabeats";
// sirve para conectar a la base de datos
$conexion=new mysqli($host,$user,$pass,$db);
if($conexion->connect_error){
    die("Error de conexion: " . $conexion->connect_error);
}
?>