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
    <title>Indie</title>
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
                        echo "<li><a class='salir' href='../logica/login-signin/control_close_sesion.php'>Log out</a></li>";
                        echo "</div>";
                    }else{
                        echo "<li><a class='ini-sesion' href='../vistas/login-signin/login.php'>Log in</a></li>";
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
                    echo "<li>Hola! ".$_SESSION["user"]. " |</li>";
                    echo "<li><a class='salir' href='../logica/login-signin/control_close_sesion.php'>Log out</a></li>";
                    echo "</div>";
                }else{
                    echo "<li><a class='ini-sesion' href='../vistas/login-signin/login.php'>Log in</a> |</li> ";
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
        <section id="indie" class="container"> 
            <img src="../images/indie/00.png" alt="imagen_Indie" class="img-intro">
            <div class="description">
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
        <?php endif;
            include("../../backend/bd/conexion_bd.php");
            include("../../backend/generos/control_indie.php");
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
