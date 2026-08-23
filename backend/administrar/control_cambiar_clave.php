<?php
//sirve para verifica si se ha presionado el boton "btn-cambiar" del formulario y solo se ejecuta si el formulario fue enviado
if(!empty($_POST["btn-cambiar"])){ 
    if (empty($_POST["password"]) || empty($_POST["new-password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // sirve para verififcar que las contraseñas coincidadn
        if($_POST["password"] != $_POST["new-password"]){
            echo '<div class="advertencia" align="center">Las contraseñas no coinciden</div>';
        }else{
            $id = $_SESSION["id"];
            $pass=md5($_POST["password"]);
            $consulta = "UPDATE usuarios SET clave=? WHERE id= ?";
            $stmt = $conexion->prepare($consulta);
            if($stmt){
                $stmt->bind_param('si', $pass, $id);
                $stmt->execute();
                if($stmt->execute()){
                    header("Location: ../../index.php");
                }else{
                    echo '<div class="advertencia" align="center">Error al actualizar contraseña</div>';                    
                }
            }else{
                die("Error en la consulta: " . $conexion->error);
            }
        }
    }
};
?>