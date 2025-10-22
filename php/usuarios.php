<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/php-style.css">
	<link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
	<title>Administrar usuarios</title>
</head>
<body>
	<nav>
		<img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
		<ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="javascript:history.back()">Volver</a></li>
		</ul>
	</nav>
	<h1 align="center">Usuarios</h1>
	<?php
include("../bd/conexion_bd.php");

// Procesar actualización de estado si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && isset($_POST['estado'])) {
    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];

    $sql = "UPDATE usuarios SET estado = ? WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("is", $estado, $usuario);

    if ($stmt->execute()) {
        echo "<p align='center' style='color:green;'>✅ Estado actualizado para el usuario <strong>$usuario</strong>.</p>";
    } else {
        echo "<p align='center' style='color:red;'>❌ Error al actualizar el estado.</p>";
    }
}

// Mostrar tabla de usuarios
$sql = "SELECT nombre, apellido, usuario, estado FROM usuarios";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    echo "<table class='tabla' cellpadding='10' cellspacing='0'>
    <tr><th>Nombre</th><th>Apellido</th><th>Usuario</th><th>Estado</th><th>Acciones</th></tr>";
    while($row = $resultado->fetch_assoc()) {
        echo "<tr>
        <td>{$row['nombre']}</td>
        <td>{$row['apellido']}</td>
        <td>{$row['usuario']}</td>
        <td>{$row['estado']}</td>
        <td>
            <form class='tabla' method='POST' style='display:inline-block;'>
                <input type='hidden' name='usuario' value='{$row['usuario']}'>
                <select name='estado'>
                    <option value='0' " . ($row['estado'] == 0 ? "selected" : "") . ">0 (Inactivo)</option>
                    <option value='1' " . ($row['estado'] == 1 ? "selected" : "") . ">1 (Activo)</option>
                </select>
                <button type='submit'>Guardar</button>
            </form>
        </td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay usuarios registrados.</p>";
}
?>
</body>

</body>
</html>