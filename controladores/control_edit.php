<?php
include("./bd/conexion_bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];

    // Obtener el estado actual del usuario
    $sql = "SELECT estado FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $estado_actual = $row['estado'];
        echo "<h2>Editar estado de usuario: $usuario</h2>";
        echo "<form method='POST' action='actualizar_estado.php'>
                <input type='hidden' name='usuario' value='$usuario'>
                <label for='estado'>Nuevo estado:</label>
                <select name='estado' id='estado'>
                    <option value='0' " . ($estado_actual == 0 ? "selected" : "") . ">0 (Inactivo)</option>
                    <option value='1' " . ($estado_actual == 1 ? "selected" : "") . ">1 (Activo)</option>
                </select>
                <button type='submit'>Guardar</button>
              </form>";
    } else {
        echo "Usuario no encontrado.";
    }
} else {
    echo "No se recibió ningún usuario.";
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && isset($_POST['estado'])) {
    $usuario = $_POST['usuario'];
    $nuevo_estado = $_POST['estado'];

    $sql = "UPDATE usuarios SET estado = ? WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $nuevo_estado, $usuario);

    if ($stmt->execute()) {
        echo "Estado actualizado correctamente.";
        echo "<br><a href='index.php'>Volver a la lista de usuarios</a>";
    } else {
        echo "Error al actualizar el estado.";
    }
} else {
    echo "Datos incompletos.";
}
?>
