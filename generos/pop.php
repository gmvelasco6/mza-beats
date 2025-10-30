<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pop-style.css">
    <link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
    <title>Pop</title>
</head>
<body>
    <?php
        session_start();
    ?>
    <header id="inicio">
        <nav>
            <div class="logo-container">
                <img class="logo" src="../images\logo\logoMzaBeats.png" alt="">
            </div>
            <div class="nav-container">
                <ul class="nav-list">
                    <li class="nav-list-item"><a class="link" href="../index.php">Inicio</a></li>
                    <li>|</li>
                    <li class="nav-list-item-genero"><a href="">Generos</a>
                        <ul class="genero-list">
                            <li class="genero-list-item"><a href="indie.php">Indie</a></li>
                            <li class="genero-list-item"><a href="pop.php">Pop</a></li>
                            <li class="genero-list-item"><a href="rock.php">Rock</a></li>
                           <li class="genero-list-item"><a href="otros.php">Otros</a></li>
                        </ul>    
                    </li>
                    <?php if(!empty($_SESSION["id"])){ ?>
                        <li class="linea">|</li>
                        <li><a href="php/usuario.php">Ver Cuenta</a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="user-container">
                <ul>
                    <?php
                        if(!empty($_SESSION["id"])){
                            echo "<li>HOLA ".$_SESSION["user"]. " |</li>";
                            echo "<li><a class='salir' href='../controladores/control_close_sesion.php'>SALIR</a></li>";
                        }else{
                            echo "<li><a class='ini-sesion' href='../php/login.php'>LOG IN</a> |</li> ";
                            echo "<li><a class='registrarse' href='../php/register.php'> SIGN IN</a></li> ";
                        } 
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        <div class="intro">
            <ul class="pop-nav">
                <li><a href="#pop-uno">Kush Mama</a></li>
                <li><a href="#pop-dos">Verona</a></li>
                <li><a href="#pop-tres">Candi Viosch</a></li>
            </ul>
        </div>
        <section class="pop-container">
            <img src="../images/pop/00.png" alt="imagen_Rock" class="pop-img">
            <div class="pop-description">
                <h2>Seccion Pop</h2>
                <p>
                    La escena pop de Mendoza ha crecido con fuerza en los últimos años, 
                    dando lugar a propuestas frescas y originales que combinan 
                    lo melódico con lo experimental. 
                    Artistas como Mariana Päraway han llevado el pop mendocino a un nivel 
                    más íntimo y sofisticado, fusionando sintetizadores,
                    sonidos andinos y letras cargadas de emoción. 
                    También destacan bandas como Spaghetti Western, 
                    que incorporan elementos electrónicos y una estética retro que los 
                    hace únicos dentro del género. El pop mendocino no se limita a lo comercial, 
                    sino que explora distintas sonoridades con identidad propia, 
                    influenciado por la diversidad cultural y el espíritu artístico de la región. 
                    Estos proyectos suelen presentarse en espacios culturales independientes, 
                    festivales locales y ciclos acústicos, fortaleciendo una comunidad 
                    creativa en constante evolución.
            </div>
        </section>
    </main>
    <?php
    include("../bd/conexion_bd.php");    
    
    // Verificar si existe la columna genero y adaptar la consulta
    $hasGenero = false;
    $checkCol = $conexion->query("SHOW COLUMNS FROM bandas LIKE 'genero'");
    if ($checkCol && $checkCol->num_rows > 0) {
        $hasGenero = true;
    }
    
    if ($hasGenero) {
        // Si existe la columna genero, mostrar solo bandas pop
        $result = $conexion->query("SELECT * FROM bandas WHERE genero = 'pop' ORDER BY id ASC");
    } else {
        // Si no existe la columna genero, no mostrar bandas (pop solo para nuevas)
        $result = false;
    }
    // Inicializar contador para alternar el diseño de las bandas
    $c = 0;
    
    // Verificar si la consulta fue exitosa y devolvió resultados
    if ($result) {
        // Recorrer cada fila (banda) del resultado de la consulta
        while ($row = $result->fetch_assoc()) {
            // Extraer los datos de cada banda del array asociativo
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            // Construir rutas de las imágenes agregando '../' para subir un directorio
            $imgPrincipal = '../' . $row['imagen_principal'];
            $imgFondo = '../' . $row['imagen_fondo'];
            
            // Crear estilo CSS para imagen de fondo (si existe una ruta válida)
            $bgStyle = $imgFondo !== '../' ? "--item-bg: url('" . $imgFondo . "');" : '';
            // Incrementar contador para alternar diseños
            $c++;
            
            // Alternar entre dos diseños diferentes según si el contador es par o impar
            if($c % 2 != 0){
                // Diseño normal (contador impar): usar clase 'pop-section'
                echo '<hr>';
                echo '<div class="pop-section" style="' . $bgStyle . '">';
                echo '<div><img class="pop-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                echo '<div class="pop-description">';
                echo '<h2>' . $nombre . '</h2>';
                echo '<p>' . $descripcion . '</p>';
                echo '</div>';
                echo '</div>';
            }else{
                // Diseño inverso (contador par): usar clase 'pop-section-reverse'
                echo '<hr>';
                echo '<div class="pop-section-reverse" style="' . $bgStyle . '">';
                echo '<div><img class="pop-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                echo '<div class="pop-description">';
                echo '<h2>' . $nombre . '</h2>';
                echo '<p>' . $descripcion . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
    ?>
    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <input class="btn-participar" type="submit" onclick="window.location.href='../php/formulario.php';" value="¡Quiero aparecer!">
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>