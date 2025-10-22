
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images\logo\page-icon.png" type="image/x-icon">
    <title>MzaBeats</title>
</head>
<body>
    <?php
        session_start();
    ?>
    <header>
        <nav>
            <div class="logo-container">
                <img class="logo" src="images\logo\logoMzaBeats.png" alt="">
            </div>
            <div class="nav-container">
                <ul class="nav-list">
                    <li class="nav-list-item"><a href="index.php">Inicio</a></li>
                    <li>|</li>
                    <li class="nav-list-item-genero"><a href="">Generos</a>
                        <ul class="genero-list">
                            <li class="genero-list-item"><a href="generos\indie.php">Indie</a></li>
                            <li class="genero-list-item"><a href="generos\pop.php">Pop</a></li>
                            <li class="genero-list-item"><a href="generos\rock.php">Rock</a></li>
                           <li class="genero-list-item"><a href="generos\otros.php">Otros</a></li>
                        </ul>    
                    </li>
                    <li>|</li>
                    <li><a href="#about-us">Sobre Nosotros</a></li>
                    <?php if(!empty($_SESSION["id"]) and $_SESSION["state"]=="1"){ ?>
                        <li>|</li>
                        <li><a href="php/usuarios.php">Admin. Usuarios</a></li>
                        <div id="contenedor"></div>
                    <?php } ?>
                </ul>
            </div>
            <div class="user-container">
                <ul>
                    <?php
                    if(!empty($_SESSION["id"])){
                        echo "<li>HOLA ".$_SESSION["user"]. " |</li>";
                        echo "<li><a class='salir' href='controladores/control_close_sesion.php'>SALIR</a></li>";
                    }else{
                        echo "<li><a class='ini-sesion' href='php/login.php'>LOG IN</a> |</li> ";
                        echo "<li><a class='registrarse' href='php/register.php'> SIGN IN</a></li> ";
                    }
                        
                    ?>
                    
                </ul>
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
            <img src="images/inicio/pop.png" alt="imagen_Pop" class="pop-img">
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
            <h2>¿Pregunta?</h2>
            <p>Respuesta larga</p>
            <h2>¿Pregunta?</h2>
            <p>Respuesta larga</p>
            <h2>¿Pregunta?</h2>
            <p>Respuesta larga</p>
            <h2>¿Pregunta?</h2>
            <p>Respuesta larga</p>
        </section>
    </main>
    <footer>
        <input class="btn-participar" type="submit" onclick="window.location.href='php/formulario.php';" value="¡Quiero aparecer!">
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>