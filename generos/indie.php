<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/indie-style.css">
    <link rel="shortcut icon" href="..\images\logo\page-icon.png" type="image/x-icon">
    <title>Indie</title>
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
            <ul class="indie-nav">
                <li><a href="#indie-uno">Usted Señalemelo</a></li>
                <li><a href="#indie-dos">Mi Amigo Invencible</a></li>
                <li><a href="#indie-tres">Pasado Verde</a></li>
            </ul>
        </div>
        <section  id="indie" class="indie-container">
            <img src="../images/indie/00.png" alt="imagen_Indie" class="indie-img-intro">
            <div class="indie-description">
                <h2>Seccion Indie</h2>
                <p>
                    Mendoza ha sido un semillero de música alternativa y emergente, 
                    con una rica escena indie que ha crecido a lo largo de los años. 
                    La ciudad ha visto surgir a bandas como Mi Amigo Invencible, 
                    que se ha consolidado como una de las más representativas, 
                    fusionando elementos del indie rock con un sonido melódico y profundo. 
                    Otros grupos como Pasado Verde y Usted Señalemelo 
                    también han dejado una huella en la escena local e incluso 
                    han ganado reconocimiento a nivel nacional.
                    La escena indie mendocina se caracteriza por su autenticidad, 
                    con bandas que experimentan con diferentes estilos y sonidos, 
                    desde el folk más suave hasta el rock más experimental. 
                    Los bares y teatros de la ciudad se llenan de jóvenes 
                    que disfrutan de la libertad creativa que esta movida representa, 
                    convirtiendo a Mendoza en uno de los lugares más vibrantes para 
                    el indie en Argentina.
                </p>
            </div>
        </section>
        <hr>
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
            // Si existe la columna genero, mostrar indie y bandas sin genero
            $result = $conexion->query("SELECT * FROM bandas WHERE genero = 'indie' OR genero IS NULL ORDER BY id ASC");
        } else {
            // Si no existe la columna genero, mostrar todas las bandas
            $result = $conexion->query("SELECT * FROM bandas ORDER BY id ASC");
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
                    // Diseño normal (contador impar): usar clase 'indie-section'
                    echo '<hr>';
                    echo '<div class="indie-section" style="' . $bgStyle . '">';
                    echo '<div><img class="indie-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                    echo '<div class="indie-description">';
                    echo '<h2>' . $nombre . '</h2>';
                    echo '<p>' . $descripcion . '</p>';
                    echo '</div>';
                    echo '</div>';
                }else{
                    // Diseño inverso (contador par): usar clase 'indie-section-reverse'
                    echo '<hr>';
                    echo '<div class="indie-section-reverse" style="' . $bgStyle . '">';
                    echo '<div><img class="indie-img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
                    echo '<div class="indie-description">';
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
