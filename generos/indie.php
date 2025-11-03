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
                <li><a href="#indie-uno">Usted Señalemelo</a></li>
                <li><a href="#indie-dos">Mi Amigo Invencible</a></li>
                <li><a href="#indie-tres">Pasado Verde</a></li>
            </ul>
        </div>
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
        <?php
            include("../bd/conexion_bd.php");
            include("../controladores/generos/control_indie.php");
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
