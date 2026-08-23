<?php
    session_start();
?>
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
    <header id="inicio">
        <nav>
            <div class="logo-container">
                <img class="logo" src="../images/logo/logoMzaBeats.png" alt="">
            </div>

            <!-- Checkbox e icono hamburguesa fuera del container -->
            <input type="checkbox" class="btn-nav" id="btn-nav">
            <label for="btn-nav" class="menu-icon">&#9776;</label>

            <div class="nav-container">
                <ul class="nav-list">
                    <li class="nav-list-item"><a href="../index.php">Inicio</a></li>
                    <li class="linea">|</li>
                    <li class="nav-list-item-genero">
                        <input type="checkbox" id="btn-genero" class="btn-genero">
                        <label for="btn-genero" class="genero-label">Genero</label>
                        <ul class="genero-list">
                            <li class="genero-list-item"><a href="indie.php">Indie</a></li>
                            <li class="genero-list-item"><a href="pop.php">Pop</a></li>
                            <li class="genero-list-item"><a href="rock.php">Rock</a></li>
                        </ul>    
                    </li>
                    <?php 
                        if(!empty($_SESSION["id"]) and $_SESSION["state"]=="67"){ 
                            echo "<li class='linea'>|</li>";
                            echo "<li><a href='../vistas/administrar/administrar.php'>Administrar</a></li>";
                        }
                        if(!empty($_SESSION["id"])){
                            echo "<li class='linea'>|</li>";
                            echo "<li><a href='../vistas/administrar/usuario.php'>Ver Cuenta</a></li>";
                        }
                    ?>
                </ul>

                <!-- BLOQUE DE USUARIO REPETIDO PARA MÓVIL (DENTRO DEL DESPLEGABLE) -->
                <div class="user-container-mobile">
                    <?php 
                    echo "<ul class='user-list'>";
                    if(!empty($_SESSION["id"])){
                        echo "<a href='../vistas/administrar/usuario.php'><img src='../images/inicio/usuario.png' class='img-usuario' alt='img-usuario'></a>";
                        echo "<div>";
                        echo "<li>Hola! ".$_SESSION["user"]."</li>";
                        echo '<li class="linea"> | </li>';
                        echo "<li><a class='salir' href='../sesion_control/login-signin/control_close_sesion.php'>Log out</a></li>";
                        echo "</div>";
                    }else{
                        echo "<li><a class='ini-sesion' href='../vistas/login-signin/login.php'>Log in</a></li>";
                        echo '<li class="linea"> | </li>';
                        echo "<li><a class='registrarse' href='../vistas/login-signin/register.php'>Sign in</a></li>";
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
                    echo "<a href='../vistas/administrar/usuario.php'><img src='../images/inicio/usuario.png' class='img-usuario' alt='img-usuario'></a>";
                    echo "<div>";
                    echo "<li>Hola! ".$_SESSION["user"]. "</li>";
                    echo '<li class="linea"> | </li>';
                    echo "<li><a class='salir' href='../sesion_control/login-signin/control_close_sesion.php'>Log out</a></li>";
                    echo "</div>";
                }else{
                    echo "<li><a class='ini-sesion' href='../vistas/login-signin/login.php'>Log in</a></li> ";
                    echo '<li class="linea"> | </li>';
                    echo "<li><a class='registrarse' href='../vistas/login-signin/register.php'> Sign in</a></li> ";
                }
                echo "</ul>";
                ?>
            </div>
        </nav>
    </header>
    <main>
        <form class="buscador-container" method="GET">
            <input type="text" name="buscador" placeholder="Buscar banda...">
            <div class="botones-buscador">
                <button type="submit" name="buscar">Buscar</button>
                <button type="submit" name="limpiar">Limpiar</button>
            </div>
        </form>
        <?php if(empty($_GET["buscador"])): ?>
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
        <?php endif;
            include("../../backend/bd/conexion_bd.php");
            include("../../backend/generos/control_pop.php");
        ?>
    </main>
    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <?php if(empty($_SESSION["state"])){ ?>
            <input class="btn-participar" type="submit" onclick="window.location.href='../vistas/formulario.php';" value="¡Quiero aparecer!">
        <?php } ?>   
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>