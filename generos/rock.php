<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/rock-style.css">
    <link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
    <title>Rock</title>
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
            <ul class="rock-nav">
                <li><a href="#rock-uno">Karamelo Santo</a></li>
                <li><a href="#rock-dos">Santo Tabú</a></li>
                <li><a href="#rock-tres">Chantas</a></li>
                <li><a href="#rock-cuatro">Brut4l</a></li>
            </ul>
        </div>
        <section class="rock-container">
            <img src="../images/rock/00.png" alt="imagen_Rock" class="rock-img">
            <div class="rock-description">
                <h2>Seccion Rock</h2>
                <p>
                    El rock mendocino tiene una historia sólida y vibrante, 
                    con bandas que han sabido combinar la potencia del género con un 
                    fuerte arraigo local. Desde los años 90, 
                    grupos como Chancho Va comenzaron a marcar el rumbo con un estilo directo y 
                    letras comprometidas, mientras que bandas como Monos en Bolas 
                    y La Skandalosa Tripulación sumaron nuevas capas al sonido rockero, 
                    mezclando funk, ska y reggae.
                    La escena actual mantiene viva la energía del rock con propuestas 
                    que van desde lo alternativo hasta el hard rock, 
                    consolidándose en escenarios locales, festivales provinciales y
                    encuentros de música independiente. 
                    El espíritu del rock en Mendoza está marcado por la autogestión, 
                    la pasión por el vivo y la conexión genuina con el público, 
                    convirtiéndolo en uno de los géneros más influyentes de la provincia.
                </p>
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
            // Si existe la columna genero, mostrar solo bandas rock
            $result = $conexion->query("SELECT * FROM bandas WHERE genero = 'rock' ORDER BY id ASC");
        } else {
            // Si no existe la columna genero, no mostrar bandas (rock solo para nuevas)
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
                    // Diseño normal (contador impar): usar clase 'rock-section'
                    echo '<hr>';
                    echo '<div class="rock-section" style="' . $bgStyle . '">';
                    echo '<div><img class="rock-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                    echo '<div class="rock-description">';
                    echo '<h2>' . $nombre . '</h2>';
                    echo '<p>' . $descripcion . '</p>';
                    echo '</div>';
                    echo '</div>';
                }else{
                    // Diseño inverso (contador par): usar clase 'rock-section-reverse'
                    echo '<hr>';
                    echo '<div class="rock-section-reverse" style="' . $bgStyle . '">';
                    echo '<div><img class="rock-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                    echo '<div class="rock-description">';
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