<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/logo/page-icon.png" type="image/x-icon">
    <title>MzaBeats</title>
</head>
<body>
    <?php
        session_start();
    ?>
    <header>
        <nav>
            <div class="logo-container">
                <img class="logo" src="images/logo/logoMzaBeats.png" alt="">
            </div>

            <!-- Checkbox e icono hamburguesa fuera del container -->
            <input type="checkbox" class="btn-nav" id="btn-nav">
            <label for="btn-nav" class="menu-icon">&#9776;</label>

            <div class="nav-container">
                <ul class="nav-list">
                    <li class="nav-list-item"><a href="index.php">Inicio</a></li>
                    <li class="linea">|</li>
                    <li class="nav-list-item-genero">
                        <input type="checkbox" id="btn-genero" class="btn-genero">
                        <label for="btn-genero" class="genero-label">Genero</label>
                        <ul class="genero-list">
                            <li class="genero-list-item"><a href="generos/indie.php">Indie</a></li>
                            <li class="genero-list-item"><a href="generos/pop.php">Pop</a></li>
                            <li class="genero-list-item"><a href="generos/rock.php">Rock</a></li>
                        </ul>    
                    </li>
                    <li class="linea">|</li>
                    <li><a href="#about-us">Sobre Nosotros</a></li>
                    <?php 
                        if(!empty($_SESSION["id"]) and $_SESSION["state"]=="67"){ 
                            echo "<li class='linea'>|</li>";
                            echo "<li><a href='vistas/administrar/administrar.php'>Administrar</a></li>";
                        }
                        if(!empty($_SESSION["id"])){
                            echo "<li class='linea'>|</li>";
                            echo "<li><a href='vistas/administrar/usuario.php'>Ver Cuenta</a></li>";
                        }
                    ?>
                </ul>

                <!-- BLOQUE DE USUARIO DENTRO DEL DESPLEGABLE MÓVIL -->
                <div class="user-container-mobile">
                    <?php 
                    echo "<ul class='user-list'>";
                    if(!empty($_SESSION["id"])){
                        echo "<a href='vistas/administrar/usuario.php'><img src='images/inicio/usuario.png' class='img-usuario' alt='img-usuario'></a>";
                        echo "<div>";
                        echo "<li>HOLA ".$_SESSION["name"]."</li>";
                        echo "<li><a class='salir' href='logica/login-signin/control_close_sesion.php'>Log out</a></li>";
                        echo "</div>";
                    }else{
                        echo "<li><a class='ini-sesion' href='vistas/login-signin/login.php'>Log in</a></li>";
                        echo "<li><a class='registrarse' href='vistas/login-signin/register.php'>Sign in</a></li>";
                    }
                    echo "</ul>";
                    ?>
                </div>
            </div>

            <!-- BLOQUE DE USUARIO PARA ESCRITORIO -->
            <div class="user-container">
                <?php 
                echo "<ul class='user-list'>";
                if(!empty($_SESSION["id"])){
                    echo "<a href='vistas/administrar/usuario.php'><img src='images/inicio/usuario.png' class='img-usuario' alt='img-usuario'></a>";
                    echo "<div>";
                    echo "<li>HOLA ".$_SESSION["name"]." |</li>";
                    echo "<li><a class='salir' href='logica/login-signin/control_close_sesion.php'>SALIR</a></li>";
                    echo "</div>";
                }else{
                    echo "<li><a class='ini-sesion' href='vistas/login-signin/login.php'>LOG IN</a> |</li> ";
                    echo "<li><a class='registrarse' href='vistas/login-signin/register.php'> SIGN IN</a></li> ";
                }
                echo "</ul>";
                ?>
            </div>
        </nav>
    </header>
    <main>
        <div class="intro">
            <h1>Bienvenido a MzaBeats</h1>
            <h3>Mendoza no solo es la tierra del buen vino, sino tambien, de la buena musica. 
                <br>En MzaBeats encontraras tu proxima banda favortia.</h3>
            </div>
        <section class="indie-container">
                <img src="images/inicio/indie.png" alt="imagen_Indie" class="indie-img">
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
        <section class="pop-container"> 
            <img src="images/inicio/pop.png" alt="imagen_Pop" class="pop-img">
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
                </p>
            </div>
        </section>
        <hr>
        <section class="rock-container">
            <img src="images/inicio/rock.png" alt="imagen_Rock" class="rock-img">
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
        <section id="about-us" class="about-us">
            <h2>¿Que es MzaBeats?</h2>
            <p>MzaBeats es una página web que almacena todas las bandas originas de la provincia de Mendoza, Argentina.
                Nuestra idea es que la cultura musical Mendocina sea más reconocida, por todo el mundo.
            </p>
            <h2>¿Como aparezco en la pagina?</h2>
            <p>Si sos una banda o un artista mendocino solo tenes que llenar el 
                formulario de consulta con tu nombre, mail y razon de participar
                y nos pondremos en contacto lo más rapido posible</p>
        </section>
    </main>
    <footer>
                <!-- (!empty es igual a no vacio y empty es igual a vacio) -->
                <!-- sirve para que si el usuario no tiene sesion no pueda agregar bandas y si si estado es mayor o igual a 1 le parace el boton para poder agregar bandas -->
        <?php if(empty($_SESSION["state"])){ ?>
            <input class="btn-participar" type="submit" onclick="window.location.href='vistas/formulario.php';" value="¡Quiero aparecer!">
        <?php } ?>
        <?php if(!empty($_SESSION["id"]) && $_SESSION["creation_count"]>="1"){ ?>
            <input class="btn-agregar" type="button" onclick="window.location.href='vistas/agregar_banda.php';" value="Agregar banda">
        <?php } ?>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>

</body>
</html>