<?php
$error_registro = '';

if (!empty($_POST["btn-registro"])) {
    if (empty($_POST["name"]) || empty($_POST["user"]) || empty($_POST["password"])) {
        $error_registro = '<div class="advertencia" align="center">Hay campos vacíos</div>';
    } else {
        $name = $_POST["name"];
        $user = $_POST["user"];
        $pass = md5($_POST["password"]);

        // Validar que el usuario sea único
        $consulta_check = "SELECT id FROM usuarios WHERE usuario = ?";
        $stmt_check = $conexion->prepare($consulta_check);
        $stmt_check->bind_param('s', $user);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_registro = '<div class="advertencia" align="center">Usuario existente, elige otro</div>';
        } else {
            // Consulta para ingresar datos (3 parámetros = 'sss')
            $consulta = "INSERT INTO usuarios(nombre, usuario, clave) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($consulta);

            if ($stmt) {
                $stmt->bind_param('sss', $name, $user, $pass);
                if ($stmt->execute()) {
                    header("Location: ../../index.php");
                    exit();
                } else {
                    $error_registro = '<div class="advertencia" align="center">Error al registrar usuario</div>';
                }
            } else {
                $error_registro = '<div class="advertencia" align="center">Error al realizar la consulta</div>';
            }
        }
    }
}
?>