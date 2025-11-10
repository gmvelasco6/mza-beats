<?php
//sirve para verifica si se ha presionado el boton "btn-cambiar" del formulario y solo se ejecuta si el formulario fue enviado
if(!empty($_POST["btn-cambiar"])){ 
<<<<<<< HEAD
    // sirve para verificar que el campo de contraseña no este vacio
    if (empty($_POST["password"]) && empty($_POST["new-password"])){
=======
    if (empty($_POST["password"]) || empty($_POST["new-password"])){
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
        echo '<div class="advertencia" align="center">Hay campos vacios</div>';
    }else{
        // sirve para verififcar que las contraseñas coincidadn
        if($_POST["password"] != $_POST["new-password"]){
            echo '<div class="advertencia" align="center">Las contraseñas no coinciden</div>';
        }else{
<<<<<<< HEAD
            //realiza la consulta a la base de datos para cambiar la contraseña y crea una consulta para actualizarla 
            $pass=md5($_POST["password"]);
            $sql = "UPDATE usuarios SET clave='$pass' WHERE id=" . $_SESSION['id'];
            $resultado = $conexion->query($sql);
            // si hay algun error en la consulta musestra el mensaje de error de consulta 
            if (!$resultado) {
=======
            $id = $_SESSION["id"];
            $pass=md5($_POST["password"]);
            $sql = "UPDATE usuarios SET clave=? WHERE id= ?";
            $stmt = $conexion->prepare($sql);
            if($stmt){
                $stmt->bind_param('si', $pass, $id);
                $stmt->execute();
                if($stmt->execute()){
                    header("Location: ../../index.php");
                }else{
                    echo '<div class="advertencia" align="center">Error al actualizar contraseña</div>';                    
                }
            }else{
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
                die("Error en la consulta: " . $conexion->error);
            }
        }
    }
};
?>