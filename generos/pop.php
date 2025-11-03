<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/genero-style.css">
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
                    <li class="nav-list-item-genero">
                    <input type="checkbox" id="btn-genero" class="btn-genero">
                    <label for="btn-genero" class="genero-label">Genero</label>
                        <ul class="genero-list">
                            <li class="genero-list-item"><a href="indie.php">Indie</a></li>
                            <li class="genero-list-item"><a href="pop.php">Pop</a></li>
                            <li class="genero-list-item"><a href="rock.php">Rock</a></li>
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
            <ul class="mini-nav">
                <li><a href="#pop-uno">Kush Mama</a></li>
                <li><a href="#pop-dos">Verona</a></li>
                <li><a href="#pop-tres">Candi Viosch</a></li>
            </ul>
        </div>
        <section class="container">
            <img src="../images/pop/00.png" alt="imagen_Rock" class="img-intro">
            <div class="description">
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
        <?php
            include("../bd/conexion_bd.php");
            include("../controladores/generos/control_pop.php");
        ?>
    </main>
    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <?php if($_SESSION["state"]=="0"){ ?>
            <input class="btn-participar" type="submit" onclick="window.location.href='php/formulario.php';" value="¡Quiero aparecer!">
        <?php } ?>        
            <p>&copy;Derechos de autor a Basigalup y Velasco</p>
    </footer>
</body>
</html>