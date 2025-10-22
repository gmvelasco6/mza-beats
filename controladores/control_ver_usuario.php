<?php
$sql = "SELECT nombre, apellido, usuario, estado FROM usuarios";
$resultado = $conexion->query($sql);
if ($resultado->num_rows > 0) {
    echo "<table class='tabla'>
    <tr><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Estado</th><th>Acciones</th></tr>";
    while($row = $resultado->fetch_assoc()) {
        echo "<tr>
        <td>{$row['nombre']}</td>
        <td>{$row['apellido']}</td>
        <td>{$row['usuario']}</td>
        <td>{$row['estado']}</td>
        <td>
            <form method='post' action='control_edit.php'>
                <input type='hidden' name='estado' value='{$row['estado']}'>
                <button type='submit'>Editar Estado</button>
            </form>
        </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "No hay usuarios registrados.";
}
?>