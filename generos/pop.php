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
            <ul class="pop-nav">
                <li><a href="#pop-uno">Kush Mama</a></li>
                <li><a href="#pop-dos">Verona</a></li>
                <li><a href="#pop-tres">Candi Viosch</a></li>
            </ul>
        </div>
        <section class="pop-container-uno">
            <img src="../images/pop/Mariana-Paraway4.png" alt="imagen_Rock" class="pop-img">
            <div class="pop-description-uno">
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
        <hr>
        <section class="pop-container-dos" id="pop-uno">
            <div class="pop-description-dos">
                <h2>Kush Mama</h2>
                <p>
                    Kush Mama es una banda mendocina formada en 2018 que fusiona estilos como funk, pop, electrónica y electro-wave.
                        Su sonido combina guitarras rítmicas, bajos marcados, sintetizadores y percusión, logrando una mezcla moderna, bailable y muy energética.
                        Está integrada por Santiago Mendoza (voz y guitarra), Germán Sava (guitarra), Emiliano “Emi” Ramírez (bajo), “Chicho” Rodríguez (percusión), Charly Ruiz (teclados y sintetizadores) y Martín Fernández (batería).
                        En 2019 lanzaron su primer álbum High Espacial, y en 2021 presentaron Amor & Funk, un disco que consolidó su identidad sonora con canciones como “Borrando la Línea” y “Una Luz”.
                        La banda ha participado en diversos festivales y escenarios de Mendoza y otras provincias, destacándose por su energía en vivo y su propuesta fresca dentro de la escena musical independiente argentina.
                </p>
            </div>
            <img src="../images/pop/KushMama_Integrantes.jpg" alt="imagen_Rock" class="pop-img-uno">
        </section>
        <hr>
        <section class="pop-container-tres" id="pop-dos">
            <img src="../images/pop/verona-bg.png" alt="imagen_Rock" class="pop-img-dos">
            <div class="pop-description-tres">
                <h2>Verona</h2>
                <p>
                    Verona es una banda mendocina formada en 2017 que mezcla sonidos de synth-pop, new wave y post-punk con una estética moderna y emocional.
                    Está integrada por Leandro Villanueva (voz y guitarra), Federico Calderón (sintetizadores y coros), Martín Villanueva (batería) y Guillermo Martelossi (bajo).
                    Su estilo combina lo bailable y lo melancólico, con letras introspectivas y una fuerte presencia electrónica que remite a influencias del rock alternativo británico y la escena synth de los años 80. 
                    En 2021 lanzaron su primer disco homónimo Verona, grabado en Estudios Juno y coproducido por Gustavo Iglesias, con canciones como Vapor, Mantua y Alquimia.
                    Con una propuesta visual cuidada y un sonido potente en vivo, Verona se ha consolidado como una de las bandas más interesantes de la nueva escena indie de Mendoza.
                </p>
            </div>
        </section>
        <hr>
        <section class="pop-container-cuatro" id="pop-tres">
            <div class="pop-description-cuatro">
                <h2>Candi Viosch</h2>
                <p>
                    Verona es una banda mendocina formada en 2017 e integrada por Leandro Villanueva en guitarra y voz, Martín Villanueva en batería, Federico Calderón en sintetizadores y coros, y Guillermo Martelossi en bajo.
                    Su estilo combina synth pop con espíritu punk, mezclando influencias del new wave, el post-punk y la electrónica, logrando un sonido bailable y poético a la vez.
                    La propuesta de Verona busca unir lo estético con lo pasional, creando canciones con identidad propia dentro de la escena alternativa mendocina.
                    En diciembre de 2021 lanzaron su primer disco homónimo, “Verona”, que incluye temas como “Vapor”, “Mantua” y “Alquimia”.
                    Este último cuenta con un videoclip filmado en Mendoza y producido por Aztec Latin junto a OHCHO Comunicación.
                    La banda continúa consolidándose en la escena local con una estética moderna y letras que exploran emociones y paisajes urbanos.
                </p>
            </div>
            <img src="../images/pop/candi.png" alt="imagen_Rock" class="pop-img-tres">
        </section>
        <hr>
    </main>
    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <input class="btn-participar" type="submit" onclick="window.location.href='../php/formulario.php';" value="¡Quiero aparecer!">
        <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>