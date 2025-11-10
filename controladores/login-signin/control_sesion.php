<?php
session_start();
if(!empty($_POST["btn-iniciar"])){ 
    if (empty($_POST["user"]) and empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);

        $consulta= "SELECT * FROM usuarios WHERE usuario= ? AND clave= ?";
        $stmt=$conexion->prepare($consulta);
        if (!$consulta) {
            die("Error al preparar consulta: " . $conexion->error);
        }
        $stmt->bind_param('ss', $user, $pass);
        $stmt->execute();
        $resultado=$stmt->get_result();

        if ($datos=$resultado->fetch_object()) {
            $_SESSION["id"]=$datos->id;
            $_SESSION["name"]=$datos->nombre;
            $_SESSION["user"]=$datos->usuario;
            $_SESSION["state"]=$datos->estado;
            $_SESSION["creation_count"]=$datos->cantidad_creaciones;
            header("location:../../index.php");
        } else {
            echo '<div class="advertencia" align="center">Acceso denegado</div>';
    }

}
};
?>