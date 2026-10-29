<?php
/**
 * Registro de usuarios nuevos.
 */
require __DIR__ . '/../../../backend/bootstrap.php';

if (esta_logueado()) {
    redirigir('index.php');
}

$error = null;

if (es_post()) {
    $nombre = texto_post('name');
    $usuario = texto_post('user');
    $clave = $_POST['password'] ?? '';
    $confirmacion = $_POST['password-confirm'] ?? '';
    $clave = is_string($clave) ? $clave : '';
    $confirmacion = is_string($confirmacion) ? $confirmacion : '';

    $error = validar_datos_de_cuenta($nombre, $usuario)
        ?? validar_clave_nueva($clave, $confirmacion);

    if ($error === null) {
        usuario_crear($nombre, $usuario, clave_hash($clave));
        mensaje_guardar('ok', 'Cuenta creada. Ya podés iniciar sesión.');
        redirigir('vistas/login-signin/login.php');
    }
}

vista('partes/head', ['titulo' => 'Registro', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple'); ?>
    <div class="formulario-container">
        <form class="formulario" method="post">
            <?= csrf_campo() ?>
            <h1>Registrarse</h1>

            <?php vista('partes/mensajes', ['error' => $error]); ?>

            <h2>Nombre</h2>
            <input type="text" id="name" class="input" name="name" value="<?= e(texto_post('name')) ?>" maxlength="<?= NOMBRE_LARGO_MAXIMO ?>">
            <h2>Usuario</h2>
            <input type="text" id="user" class="input" name="user" value="<?= e(texto_post('user')) ?>" maxlength="<?= USUARIO_LARGO_MAXIMO ?>" autocomplete="username">
            <h2>Contraseña</h2>
            <input type="password" id="password" class="input" name="password" minlength="<?= CLAVE_LARGO_MINIMO ?>" autocomplete="new-password">
            <h2>Repetir contraseña</h2>
            <input type="password" id="password-confirm" class="input" name="password-confirm" minlength="<?= CLAVE_LARGO_MINIMO ?>" autocomplete="new-password">
            <p>¿Ya tienes un usuario? <a href="<?= e(url('vistas/login-signin/login.php')) ?>">Inicia Sesión</a></p>
            <input type="submit" name="btn-registro" class="btn" value="Registrarme">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
