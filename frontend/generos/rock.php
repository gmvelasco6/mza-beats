<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/genero-style.css">
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
                        echo "<li>HOLA ".$_SESSION["user"]."</li>";
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
                    echo "<li>HOLA ".$_SESSION["user"]. " |</li>";
                    echo "<li><a class='salir' href='../logica/login-signin/control_close_sesion.php'>SALIR</a></li>";
                    echo "</div>";
                }else{
                    echo "<li><a class='ini-sesion' href='../vistas/login-signin/login.php'>LOG IN</a> |</li> ";
                    echo "<li><a class='registrarse' href='../vistas/login-signin/register.php'> SIGN IN</a></li> ";
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
            <img src="../images/rock/00.png" alt="imagen_Rock" class="img-intro">
            <div class="description">
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
        <?php endif;
            include("../../backend/bd/conexion_bd.php");
            include("../../backend/generos/control_rock.php");
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