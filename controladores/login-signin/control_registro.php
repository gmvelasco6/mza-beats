<?php
if(!empty($_POST["btn-registro"])){ 
    if (empty($_POST["name"]) || empty($_POST["lastname"]) || empty($_POST["user"]) || empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        $name=$_POST["name"];
        $lastname=$_POST["lastname"];
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);

        //REVISO QUE EL USUARIO SEA UNICO
        $sql_check = "SELECT id FROM usuarios WHERE usuario = ? ";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param('s', $user);
        $stmt_check->execute();
        $stmt_check->store_result();
        if($stmt_check->num_rows > 0){
            echo '<div class="advertencia" align="center">Usuario existente, elige otro</div>';
        }else{
            //CONSULTA PARA INGRESAR DATOS
            $sql="INSERT INTO usuarios(nombre, apellido, usuario, clave) VALUES (?, ?, ?, ?)";
            $stmt=$conexion->prepare( $sql);
            if($stmt){
                $stmt->bind_param('ssss', $name, $lastname, $user, $pass);
                if($stmt->execute()){
                    header("Location: ../../index.php");            
                }else{
                    echo '<div class="advertencia" align="center">Error al registrar usuario</div>';
                    die("Error al ejecutar la consulta: " . $stmt->error);
                }
            }else{
                echo '<div class="advertencia" align="center">Error al realizar consulta</div>';
            }
        }
    }
};
?>