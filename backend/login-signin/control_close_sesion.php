<?php
// inicia la sesion del usuario
session_start();
// destruye  la sesion actual
session_destroy();
// se encarga de redirigir al usuario a la pagina de login
header("Location: ../../frontend/vistas/login-signin/login.php");
?>