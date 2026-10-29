<?php
/**
 * Cambio de contraseña del usuario logueado.
 * Pide la contraseña actual para que nadie pueda cambiarla desde una sesión abierta ajena.
 */
require __DIR__ . '/../../../backend/bootstrap.php';

requerir_login();

$error = null;

if (es_post()) {
    $usuario = usuario_actual();
    $actual = $_POST['clave-actual'] ?? '';
    $nueva = $_POST['password'] ?? '';
    $confirmacion = $_POST['new-password'] ?? '';

    if (!is_string($actual) || !is_string($nueva) || !is_string($confirmacion)
        || $actual === '' || $nueva === '' || $confirmacion === '') {
        $error = 'Hay campos vacios';
    } elseif (!clave_es_correcta($actual, $usuario['clave'])) {
        $error = 'La contraseña actual no es correcta';
    } else {
        $error = validar_clave_nueva($nueva, $confirmacion);
    }

    if ($error === null) {
        usuario_actualizar_clave((int) $usuario['id'], clave_hash($nueva));
        session_regenerate_id(true);
        mensaje_guardar('ok', 'Contraseña actualizada');
        redirigir('vistas/administrar/usuario.php');
    }
}

vista('partes/head', ['titulo' => 'Cambiar Clave', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple'); ?>
    <div class="formulario-container">
        <?php vista('partes/mensajes', ['error' => $error]); ?>
        <form class="formulario" align="center" method="post">
            <?= csrf_campo() ?>
            <h1 align="center">Cambiar Clave</h1>
            <h2>Clave Actual</h2>
            <input type="password" id="clave-actual" class="input" name="clave-actual" autocomplete="current-password">
            <h2>Nueva Clave</h2>
            <input type="password" id="password" class="input" name="password" minlength="<?= CLAVE_LARGO_MINIMO ?>" autocomplete="new-password">
            <h2>Confirmar Clave</h2>
            <input type="password" id="new-password" class="input" name="new-password" minlength="<?= CLAVE_LARGO_MINIMO ?>" autocomplete="new-password">

            <input type="submit" name="btn-cambiar" class="btn" value="Cambiar">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
