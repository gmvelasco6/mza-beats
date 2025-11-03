<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mzabeats";
$conexion=new mysqli($host,$user,$pass,$db);
if($conexion->connect_error){
    die("Error de conexion: " . $conexion->connect_error);
}
?>