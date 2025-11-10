<?php
session_start();
if(isset($_POST['btn-crear'])){
    $nombre=$_POST['nombre'];
    $razon=$_POST['razon'];
    $email=$_POST['email'];
    $consulta = "INSERT INTO consultas (nombre, razon, email) VALUES ('$nombre', '$razon', '$email')";
    $resultado=$conexion->query($consulta);
    if($resultado){
        echo '<div class="verificado" align="center">Consulta registrado correctamente</div>';
    }else{
        echo '<div class="advertencia" align="center">Error al registrar consulta</div>';
    }
}
?>