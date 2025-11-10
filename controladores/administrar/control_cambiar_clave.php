<?php
//sirve para verifica si se ha presionado el boton "btn-cambiar" del formulario y solo se ejecuta si el formulario fue enviado
if(!empty($_POST["btn-cambiar"])){ 
    // sirve para verificar que el campo de contraseña no este vacio
    if (empty($_POST["password"]) && empty($_POST["new-password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // sirve para verififcar que las contraseñas coincidadn
        if($_POST["password"] != $_POST["new-password"]){
            echo '<div class="advertencia" align="center">Las contraseñas no coinciden</div>';
        }else{
            //realiza la consulta a la base de datos para cambiar la contraseña y crea una consulta para actualizarla 
            $pass=md5($_POST["password"]);
            $sql = "UPDATE usuarios SET clave='$pass' WHERE id=" . $_SESSION['id'];
            $resultado = $conexion->query($sql);
            // si hay algun error en la consulta musestra el mensaje de error de consulta 
            if (!$resultado) {
                die("Error en la consulta: " . $conexion->error);
            } else {
                header("Location: ../../index.php");
                exit;
            }
        }
    }
};
?>