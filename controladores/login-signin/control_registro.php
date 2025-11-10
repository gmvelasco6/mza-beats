<?php
// sirve para verificar si se presiono el boton de registro del formulario 
if(!empty($_POST["btn-registro"])){ 
    // sirve para verificar que todos estos campos no esten vacios y si esta vacio alguno de los campos dice el mensaje de Hasy campos vacios 
    if (empty($_POST["name"]) and empty($_POST["lastname"]) and empty($_POST["user"]) and empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // se usa para obtener los datos del formulario y almacenarlos en variables
        $name=$_POST["name"];
        $lastname=$_POST["lastname"];
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);
        $sql="INSERT INTO usuarios(nombre, apellido, usuario, clave) VALUES ('$name','$lastname','$user','$pass')";
        $resultado=$conexion->query( $sql);
        // se usa para mostrar dependiendo si la consulta fue exitosa o no el texto correspondiente
        if ($resultado) {
            echo '<div class="verificado" align="center">Usuario registrado correctamente</div>';
        } else {
            echo '<div class="advertencia" align="center">Error al registrar usuario</div>';
        }
        
    }
};
?>