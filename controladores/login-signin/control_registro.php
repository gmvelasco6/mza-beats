<?php
if(!empty($_POST["btn-registro"])){ 
    if (empty($_POST["name"]) and empty($_POST["lastname"]) and empty($_POST["user"]) and empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        $name=$_POST["name"];
        $lastname=$_POST["lastname"];
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);
        $sql="INSERT INTO usuarios(nombre, apellido, usuario, clave) VALUES ('$name','$lastname','$user','$pass')";
        $resultado=$conexion->query( $sql);
        if ($resultado) {
            echo '<div class="verificado" align="center">Usuario registrado correctamente</div>';
        } else {
            echo '<div class="advertencia" align="center">Error al registrar usuario</div>';
        }
        
    }
};
?>