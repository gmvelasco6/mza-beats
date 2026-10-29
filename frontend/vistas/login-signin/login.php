<?php
/**
 * Inicio de sesión.
 */
require __DIR__ . '/../../../backend/bootstrap.php';

if (esta_logueado()) {
    redirigir('index.php');
}

$error = null;

if (es_post()) {
    $usuario = texto_post('user');
    $clave = $_POST['password'] ?? '';

    if ($usuario === '' || !is_string($clave) || $clave === '') {
        $error = 'Hay campos vacíos';
    } elseif (intentar_login($usuario, $clave)) {
        redirigir('index.php');
    } else {
        $error = 'Acceso denegado';
    }
}

vista('partes/head', ['titulo' => 'Inicio de Sesión', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple'); ?>
    <div class="formulario-container">
        <form class="formulario" align="center" method="post">
            <?= csrf_campo() ?>
            <h1 align="center">Iniciar Sesión</h1>

            <?php vista('partes/mensajes', ['error' => $error]); ?>

            <h2>Usuario</h2>
            <input type="text" id="user" class="input" name="user" value="<?= e(texto_post('user')) ?>" autocomplete="username">
            <h2>Contraseña</h2>
            <input type="password" id="password" class="input" name="password" autocomplete="current-password">
            <p>¿No has creado un usuario? <a href="<?= e(url('vistas/login-signin/register.php')) ?>">Regístrate</a></p>
            <input type="submit" name="btn-iniciar" class="btn" value="Iniciar Sesion">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
