<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Banda</title>
    <link rel="stylesheet" href="../css/php-style.css">
	<link rel="shortcut icon" href="../images/logo/page-icon.png" type="image/x-icon">
</head>
<body>
<?php
    session_start();
    // verificar si alguno de los dos se cumple osea que si no tenes secion te manda al index o si tu estado es menor a 1 tambien te manda al index
    if(empty($_SESSION["id"]) || $_SESSION["state"] < "1"){
        header("Location: ../index.php");
        exit;
    }
?>
    <nav>
        <img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
        <ul class="nav-list">
            <li class="nav-list-item"><a class="link" href="../index.php">Volver a Inicio</a></li>
        </ul>
    </nav>
    <main>
        <div class="formulario-container">
            <form class="formulario" method="POST" action="../controladores/control_add_band.php">
                <h1>Agregar Banda</h1>
                
                <h2>Nombre de la banda:</h2>
                <input type="text" id="nombre" name="nombre" required placeholder="Ejemplo: Los Rockeros">

                <h2>Cuéntanos sobre la banda:</h2>
                <textarea id="descripcion" name="descripcion" required placeholder="Describe la historia de la banda, su estilo musical, integrantes, etc." cols="50"  rows="6"></textarea>
            
                <p>Por favor, mandá fotos de tu banda al siguiente correo:<a href="mailto:mzabeatsbandas@gmail.com"> mzabeatsbandas@gmail.com</a></p>
                
                <h2>Género musical:</h2>
                <select id="genero" name="genero" required>
                    <option value="">Selecciona un género</option>
                    <option value="indie">Indie</option>
                    <option value="pop">Pop</option>
                    <option value="rock">Rock</option>
                </select>
                
                <button type="submit" class="btn-submit">Guardar Banda</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='../generos/indie.php'">Cancelar</button>

            </form>
        </div>
    </main>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
    </body>
</html>