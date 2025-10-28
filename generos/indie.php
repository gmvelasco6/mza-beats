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
                // Construir lista de campos según existan en la tabla
                $selectFields = ['id', 'nombre', 'descripcion'];
                if (isset($cols['imagen_principal'])) { $selectFields[] = 'imagen_principal'; }
                if (isset($cols['imagen_fondo'])) { $selectFields[] = 'imagen_fondo'; }
                $fieldsSql = implode(', ', $selectFields);
                // Elegir columna de orden válida
                if (isset($cols['fecha_creacion'])) $orderCol = 'fecha_creacion';
                elseif (isset($cols['creado_en'])) $orderCol = 'creado_en';
                else $orderCol = 'id';

                // Construir consulta según exista o no 'genero'
                if ($hasGenero) {
                    $sql = "SELECT $fieldsSql FROM bandas WHERE genero = ? ORDER BY $orderCol DESC";
                    $stmt = $conexion->prepare($sql);
                    if ($stmt) {
                        $gen = 'indie';
                        $stmt->bind_param('s', $gen);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    }
                } else {
                    $sql = "SELECT $fieldsSql FROM bandas ORDER BY $orderCol DESC";
                    $result = $conexion->query($sql);
                }
                
                if (isset($result) && $result && $result->num_rows > 0) {
                     while ($row = $result->fetch_assoc()) {

                        $nombre = htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
                        $descripcion = nl2br(htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8'));
                        $rawPrincipal = $row['imagen_principal'] ?? '';
                        $rawFondo = $row['imagen_fondo'] ?? '';
                        $isAbs = function($p){ return (bool)preg_match('~^(https?:)?//|^data:~i', $p); };
                        $imgPrincipal = $rawPrincipal ? ($isAbs($rawPrincipal) ? $rawPrincipal : ('../' . ltrim($rawPrincipal, '/\\'))) : '';
                        $imgFondo = $rawFondo ? ($isAbs($rawFondo) ? $rawFondo : ('../' . ltrim($rawFondo, '/\\'))) : '';

                        $bgStyle = $imgFondo !== '' ? "--item-bg: url('" . htmlspecialchars($imgFondo, ENT_QUOTES, 'UTF-8') . "');" : '';
                        $c=$c+1;
                        if($c % 2 != 0){
                            echo '<hr>';
                            echo '<div class="indie-section" style="' . $bgStyle . '">';
                            if ($imgPrincipal !== '') {
                                echo '<div><img class="indie-img" src="' . htmlspecialchars($imgPrincipal, ENT_QUOTES, 'UTF-8') . '" alt="' . $nombre . '"></div>';
                            }
                            echo '<div class="indie-description">';
                            echo '<h2>' . $nombre . '</h2>';
                            echo '<p>' . $descripcion . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }else{
                            echo '<hr>';
                            echo '<div class="indie-section-reverse" style="' . $bgStyle . '">';
                            if ($imgPrincipal !== '') {
                                echo '<div><img class="indie-img" src="' . htmlspecialchars($imgPrincipal, ENT_QUOTES, 'UTF-8') . '" alt="' . $nombre . '"></div>';
                            }
                            echo '<div class="indie-description">';
                            echo '<h2>' . $nombre . '</h2>';
                            echo '<p>' . $descripcion . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</section>';
                    }  
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
