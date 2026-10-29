<?php
/**
 * Menú principal (inicio y páginas de géneros).
 *
 * @var bool $conSobreNosotros  Muestra el enlace "Sobre Nosotros" (sólo existe en el inicio)
 */
$conSobreNosotros = $conSobreNosotros ?? false;
?>
<nav>
    <div class="logo-container">
        <img class="logo" src="<?= e(url('images/logo/logoMzaBeats.png')) ?>" alt="MzaBeats">
    </div>

    <!-- Checkbox e icono hamburguesa fuera del container -->
    <input type="checkbox" class="btn-nav" id="btn-nav">
    <label for="btn-nav" class="menu-icon">&#9776;</label>

    <div class="nav-container">
        <ul class="nav-list">
            <li class="nav-list-item"><a href="<?= e(url('')) ?>">Inicio</a></li>
            <li class="linea">|</li>
            <li class="nav-list-item-genero">
                <input type="checkbox" id="btn-genero" class="btn-genero">
                <label for="btn-genero" class="genero-label">Género</label>
                <ul class="genero-list">
                    <?php foreach (GENEROS as $clave => $genero): ?>
                        <li class="genero-list-item"><a href="<?= e(url("generos/$clave.php")) ?>"><?= e($genero['nombre']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php if ($conSobreNosotros): ?>
                <li class="linea">|</li>
                <li><a href="#about-us">Sobre Nosotros</a></li>
            <?php endif; ?>
            <?php if (es_admin()): ?>
                <li class="linea">|</li>
                <li><a href="<?= e(url('vistas/administrar/administrar.php')) ?>">Administrar</a></li>
            <?php endif; ?>
            <?php if (esta_logueado()): ?>
                <li class="linea">|</li>
                <li><a href="<?= e(url('vistas/administrar/usuario.php')) ?>">Ver Cuenta</a></li>
            <?php endif; ?>
        </ul>

        <!-- Bloque de usuario dentro del desplegable móvil -->
        <div class="user-container-mobile">
            <?php vista('partes/bloque_usuario'); ?>
        </div>
    </div>

    <!-- Bloque de usuario para escritorio -->
    <div class="user-container">
        <?php vista('partes/bloque_usuario'); ?>
    </div>
</nav>
