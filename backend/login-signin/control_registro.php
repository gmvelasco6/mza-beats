<?php
// sirve para verificar si se presiono el boton de registro del formulario 
if(!empty($_POST["btn-registro"])){
    if (empty($_POST["name"]) || empty($_POST["user"]) || empty($_POST["password"])){
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // se usa para obtener los datos del formulario y almacenarlos en variables
        $name=$_POST["name"];
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);

        //REVISO QUE EL USUARIO SEA UNICO
        $consulta_check = "SELECT id FROM usuarios WHERE usuario = ? ";
        $stmt_check = $conexion->prepare($consulta_check);
        $stmt_check->bind_param('s', $user);
        $stmt_check->execute();
        $stmt_check->store_result();
        if($stmt_check->num_rows > 0){
            echo '<div class="advertencia" align="center">Usuario existente, elige otro</div>';
        }else{
            //CONSULTA PARA INGRESAR DATOS
            $consulta="INSERT INTO usuarios(nombre, usuario, clave) VALUES (?, ?, ?)";
            $stmt=$conexion->prepare( $consulta);
            if($stmt){
                $stmt->bind_param('ssss', $name, $user, $pass);
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