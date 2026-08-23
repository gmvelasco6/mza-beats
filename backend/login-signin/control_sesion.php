<?php
$error_login = '';

if (!empty($_POST["btn-iniciar"])) {
    if (empty($_POST["user"]) || empty($_POST["password"])) {
        $error_login = '<div class="advertencia" align="center">Hay campos vacíos</div>';
    } else {
        $user = $_POST["user"];
        $pass = md5($_POST["password"]);

        $consulta = "SELECT * FROM usuarios WHERE usuario = ? AND clave = ?";
        $stmt = $conexion->prepare($consulta);
        
        if (!$stmt) {
            die("Error al preparar consulta: " . $conexion->error);
        }

        $stmt->bind_param('ss', $user, $pass);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($datos = $resultado->fetch_object()) {
            $_SESSION["id"]             = $datos->id;
            $_SESSION["name"]           = $datos->nombre;
            $_SESSION["user"]           = $datos->usuario;
            $_SESSION["pass"]           = $datos->clave;
            $_SESSION["state"]          = $datos->estado;
            $_SESSION["creation_count"] = $datos->cantidad_creaciones;

            header("Location: ../../index.php");
            exit(); // Detener la ejecución del script tras redirigir
        } else {
            $error_login = '<div class="advertencia" align="center">Acceso denegado</div>';
        }
    }
}
?>