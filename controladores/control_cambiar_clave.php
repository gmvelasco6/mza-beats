<?php
if(!empty($_POST["btn-cambiar"])){ 
    if (empty($_POST["password"]) && empty($_POST["new-password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        if($_POST["password"] != $_POST["new-password"]){
            echo '<div class="advertencia" align="center">Las contraseñas no coinciden</div>';
        }else{
            $pass=md5($_POST["password"]);
            $sql = "UPDATE usuarios SET clave='$pass' WHERE id=" . $_SESSION['id'];
            $resultado = $conexion->query($sql);
            if (!$resultado) {
                die("Error en la consulta: " . $conexion->error);
            } else {
                header("Location: ../index.php");
                exit;
            }
        }
    }
};
?>