<?php
/**
 * Saludo y enlaces de sesión (Log in / Sign in / Log out).
 * Se usa dos veces en el menú: una para móvil y otra para escritorio.
 */
?>
<ul class="user-list">
    <?php if (esta_logueado()): ?>
        <a href="<?= e(url('vistas/administrar/usuario.php')) ?>"><img src="<?= e(url('images/inicio/usuario.png')) ?>" class="img-usuario" alt="img-usuario"></a>
        <div>
            <li>Hola! <?= e(usuario_actual()['usuario']) ?></li>
            <li class="linea"> | </li>
            <li><a class="salir" href="<?= e(url('sesion_control/control_close_sesion.php')) ?>">Log out</a></li>
        </div>
    <?php else: ?>
        <li><a class="ini-sesion" href="<?= e(url('vistas/login-signin/login.php')) ?>">Log in</a></li>
        <li class="linea"> | </li>
        <li><a class="registrarse" href="<?= e(url('vistas/login-signin/register.php')) ?>">Sign in</a></li>
    <?php endif; ?>
</ul>
