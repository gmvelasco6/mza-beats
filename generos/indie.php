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
                    <?php if(!empty($_SESSION["id"]) and $_SESSION["state"]=="1"){ ?>
                        <li>|</li>
                        <li><a href="../php/usuarios.php">Admin. Usuarios</a></li>
                        <div id="contenedor"></div>
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
            <img src="../images/indie/indie.png" alt="imagen_Indie" class="indie-img-intro">
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
        <section  id="indie-uno"class="indie-container-uno">
            <div class="indie-description-uno">
                <h2>Usted Señalemelo</h2>
                <p>
                    Usted Señálemelo es una de las bandas más representativas del nuevo rock argentino surgido en 
                    Mendoza en la última década. Formada en 2009 por Juan Saieg (voz y sintetizadores), 
                    Lucca Beguerie Petrich (batería) y Gabriel “Cocó” Orozco (guitarra y sintetizadores), 
                    el trío se destaca por un estilo que combina elementos del rock alternativo, el pop psicodélico, 
                    el funk y la música electrónica, creando un sonido fresco, experimental y a la vez accesible.
                    <br>
                    Su debut discográfico llegó en 2015 con el álbum Usted Señálemelo, 
                    que llamó la atención por su originalidad y propuestas sonoras innovadoras dentro del panorama nacional. 
                    Sin embargo, su consagración se dio con II (2017), un disco más maduro, con letras introspectivas y un 
                    trabajo instrumental complejo que mezcla guitarras espaciales, bases rítmicas potentes y sintetizadores 
                    envolventes. Temas como "Big Bang" y "Agüetas" se convirtieron en himnos de una nueva 
                    generación.
                    <br>
                    Tras un receso de varios años, regresaron con fuerza en 2023 con Tripolar, un trabajo conceptual que 
                    explora nuevas texturas sonoras y que consolida su posición como uno de los proyectos más innovadores 
                    del rock latinoamericano contemporáneo. La banda se caracteriza por sus shows enérgicos y visualmente 
                    impactantes, que combinan una puesta en escena cuidada con una fuerte conexión con el público joven.
                </p>
            </div>
            <img src="../images/indie/indieUno.png" alt="imagen_Indie_Uno" class="indie-img">
        </section>
        <hr>
        <section  id="indie-dos" class="indie-container-dos">
            <img src="../images/indie/indieDos.png" alt="imagen_Indie_Dos" class="indie-img">
            <div class="indie-description-dos">
                <h2>Mi Amigo Invencible</h2>
                <p>
                    Mi Amigo Invencible es una de las bandas más influyentes e innovadoras del indie rock argentino actual. 
                    Nacida en Mendoza en 2007, el grupo está liderado por Mariano Di Césare (voz y guitarra), 
                    acompañado por un colectivo de músicos que ha evolucionado con el tiempo pero mantiene una esencia 
                    creativa en común.
                    <br>
                    Su propuesta musical se basa en una mezcla de rock alternativo, indie, pop y psicodelia, 
                    con un estilo que oscila entre lo íntimo y lo experimental. Las letras de Mi Amigo Invencible son 
                    poéticas, existenciales y narrativas, explorando emociones complejas y paisajes cotidianos desde una 
                    perspectiva sensible y contemporánea.
                    <br>
                    Con discos destacados como La Nostalgia Soundsystem (2013), Dutsiland (2019) y Isla de Oro (2022), 
                    la banda ha ido expandiendo su sonido hacia terrenos más sofisticados, incorporando sintetizadores, 
                    texturas electrónicas y arreglos cuidadosamente elaborados. Temas como "Máquina del Tiempo", 
                    "Nuestra Noche" o "Algo No Cambió" los han posicionado como referentes del indie nacional.
                    <br>
                    Además de su trabajo discográfico, Mi Amigo Invencible se destaca por sus presentaciones en vivo 
                    intensas y atmosféricas, donde logran construir un universo emocional que atrapa al público. 
                    Su consolidación como banda clave del panorama latinoamericano se refleja en giras por todo el país, 
                    presentaciones internacionales y colaboraciones con artistas de la escena independiente.
                </p>
            </div>
        </section>
        <hr>
        <section  id="indie-tres" class="indie-container-tres">
            <div class="indie-description-tres">
                <h2>Pasado Verde</h2>
                <p>
                    Pasado Verde es otra de las bandas fundamentales del llamado “nuevo sonido mendocino”, 
                    movimiento que revitalizó el rock argentino desde el interior del país en la última década. 
                    Formada en Mendoza a mediados de los 2000, la agrupación está integrada por Germán Gallardo 
                    (voz y guitarra), Luciano Barbeito (guitarra y coros), Gastón Ghirardi (bajo y coros) y Lucas Furlani 
                    (batería).
                    <br>
                    Su música se caracteriza por un estilo alternativo y melódico, 
                    con influencias que van desde el indie rock y el post-rock hasta el rock clásico. 
                    Las letras de Pasado Verde suelen ser profundas, emocionales y poéticas, abordando temas como la 
                    introspección, el paso del tiempo, las relaciones humanas y la búsqueda personal.
                    <br>
                    Desde su primer disco, Fuimos y Seremos (2008), hasta trabajos más recientes como Fuego y Flora (2018), 
                    la banda ha mostrado una evolución constante en sonido y producción, manteniendo siempre un sello íntimo 
                    y auténtico. Canciones como "Del árbol", "Mares" o "Por última vez" son ejemplos de su estilo sensible y 
                    potente a la vez.
                    <br>
                    Pasado Verde ha sabido construir una base de seguidores fieles y consolidarse como un referente del rock 
                    independiente argentino, destacándose por sus presentaciones en vivo cargadas de energía, sensibilidad y una 
                    fuerte conexión emocional con el público.
                </p>
            </div>
            <img src="../images/indie/indieTres.png" alt="imagen_Indie_Tres" class="indie-img">
        </section>
    </main>
    <?php
        // Listado dinámico de bandas agregadas (soporta tablas sin columna 'genero')
        try {
            include_once __DIR__ . '/../bd/conexion_bd.php';
            if (isset($conexion) && $conexion instanceof mysqli) {
                // Detectar columnas existentes
                $cols = [];
                if ($resCols = $conexion->query("SHOW COLUMNS FROM bandas")) {
                    while ($c = $resCols->fetch_assoc()) { $cols[$c['Field']] = true; }
                }

                $hasGenero = isset($cols['genero']);
                // Elegir columna de orden válida
                if (isset($cols['fecha_creacion'])) $orderCol = 'fecha_creacion';
                elseif (isset($cols['creado_en'])) $orderCol = 'creado_en';
                else $orderCol = 'id';

                // Construir consulta según exista o no 'genero'
                if ($hasGenero) {
                    $sql = "SELECT id, nombre, descripcion, imagen_principal, imagen_fondo FROM bandas WHERE genero = ? ORDER BY $orderCol DESC";
                    $stmt = $conexion->prepare($sql);
                    if ($stmt) {
                        $gen = 'indie';
                        $stmt->bind_param('s', $gen);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    }
                } else {
                    $sql = "SELECT id, nombre, descripcion, imagen_principal, imagen_fondo FROM bandas ORDER BY $orderCol DESC";
                    $result = $conexion->query($sql);
                }

                if (isset($result) && $result && $result->num_rows > 0) {
                    echo '<section class="bandas-agregadas">';
                    echo '<h2 style="color:#fff0de; text-align:center; margin-bottom:12px;">Bandas agregadas por la comunidad</h2>';
                    while ($row = $result->fetch_assoc()) {
                        $nombre = htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
                        $descripcion = nl2br(htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
                        $imgPrincipal = isset($row['imagen_principal']) ? '../' . ltrim($row['imagen_principal'], '/\\') : '';
                        $imgFondo = isset($row['imagen_fondo']) ? '../' . ltrim($row['imagen_fondo'], '/\\') : '';

                        $bgStyle = $imgFondo !== '' ? "--item-bg: url('" . htmlspecialchars($imgFondo, ENT_QUOTES, 'UTF-8') . "');" : '';

                        echo '<article class="indie-item" style="' . $bgStyle . '">';
                        if ($imgPrincipal !== '') {
                            echo '<div class="indie-thumb"><img src="' . htmlspecialchars($imgPrincipal, ENT_QUOTES, 'UTF-8') . '" alt="' . $nombre . '"></div>';
                        } else {
                            echo '<div class="indie-placeholder">Sin imagen</div>';
                        }
                        echo '<div class="indie-description">';
                        echo '<h2>' . $nombre . '</h2>';
                        echo '<p>' . $descripcion . '</p>';
                        echo '</div>';
                        echo '</article>';
                    }
                    echo '</section>';
                }
            }
        } catch (Throwable $e) {
            // Silencioso en producción; para debug, se podría loguear
        }
    ?>

    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <input class="btn-participar" type="submit" onclick="window.location.href='../php/formulario.php';" value="¡Quiero aparecer!">
        <?php if(!empty($_SESSION["id"]) && $_SESSION["state"]=="1"): ?>
            <input class="btn-participar" type="button" style="align-self:flex-start; margin-left:20px;" onclick="window.location.href='../php/agregar_banda.php';" value="Agregar banda">
        <?php endif; ?>
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>