<?php
// sirve para verificar si se presiono el boton de registro del formulario 
if(!empty($_POST["btn-registro"])){ 
<<<<<<< HEAD
    // sirve para verificar que todos estos campos no esten vacios y si esta vacio alguno de los campos dice el mensaje de Hasy campos vacios 
    if (empty($_POST["name"]) and empty($_POST["lastname"]) and empty($_POST["user"]) and empty($_POST["password"])){
=======
    if (empty($_POST["name"]) || empty($_POST["lastname"]) || empty($_POST["user"]) || empty($_POST["password"])){
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // se usa para obtener los datos del formulario y almacenarlos en variables
        $name=$_POST["name"];
        $lastname=$_POST["lastname"];
        $user=$_POST["user"];
        $pass=md5($_POST["password"]);
<<<<<<< HEAD
        $sql="INSERT INTO usuarios(nombre, apellido, usuario, clave) VALUES ('$name','$lastname','$user','$pass')";
        $resultado=$conexion->query( $sql);
        // se usa para mostrar dependiendo si la consulta fue exitosa o no el texto correspondiente
        if ($resultado) {
            echo '<div class="verificado" align="center">Usuario registrado correctamente</div>';
        } else {
            echo '<div class="advertencia" align="center">Error al registrar usuario</div>';
=======

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
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
        }
    }
};
?>