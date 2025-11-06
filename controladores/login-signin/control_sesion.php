<?php
session_start();
if(!empty($_POST["btn-iniciar"])){ 
    if (empty($_POST["user"]) and empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);
        $sql=$conexion->query("SELECT * FROM usuarios WHERE usuario='$user' AND clave='$pass' ");
        if (!$sql) {
            die("Error en la consulta: " . $conexion->error);
        }
        if ($datos=$sql->fetch_object()) {
            $_SESSION["id"]=$datos->id;
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