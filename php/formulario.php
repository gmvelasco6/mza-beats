<?php
// --- CONEXIÓN ---
$servername = "localhost";
$username = "root";
$password = "";
$database = "mi_base";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// --- SI SE ENVÍA EL FORMULARIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $edad = $_POST["edad"];

    $sql = "INSERT INTO usuarios ( nombre, email, edad) VALUES ( '$nombre', '$email', '$$edad')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Alumno agregado correctamente.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/images/logo/page-icon.png" type="image/x-icon">
    <title>MzaBeats</title>
</head>
<body>
    <h2>Agregar Alumno</h2>
    <form method="POST" action="">
        <label>Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Edad:</label>
        <input type="text" name="edad" required><br><br>

        <button type="submit">Guardar</button>
    </form>

    <hr>
    <h2>Lista de usuarios</h2>
    <?php
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>
                <tr><th>Nombre</th><th>Email</th><th>Edad</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['edad']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No hay alumnos registrados.";
    }

    $conn->close();
    ?>
</body>
</html>