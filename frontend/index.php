<?php
/**
 * Página de inicio.
 */
require __DIR__ . '/../backend/bootstrap.php';

vista('partes/head', ['titulo' => 'MzaBeats', 'hojaDeEstilo' => 'css/style.css']);
?>
<body>
    <header>
        <?php vista('partes/nav_sitio', ['conSobreNosotros' => true]); ?>
    </header>
    <main>
        <div class="intro">
            <h1>Bienvenido a MzaBeats</h1>
            <h3>Mendoza no solo es la tierra del buen vino, sino también, de la buena música.
                <br>En MzaBeats encontrarás tu próxima banda favorita.</h3>
        </div>

        <?php $primero = true; ?>
        <?php foreach (GENEROS as $clave => $genero): ?>
            <?php if (!$primero): ?><hr><?php endif; $primero = false; ?>
            <section class="<?= e($clave) ?>-container">
                <img src="<?= e(url($genero['imagen_inicio'])) ?>" alt="imagen_<?= e($genero['nombre']) ?>" class="<?= e($clave) ?>-img">
                <div class="<?= e($clave) ?>-description">
                    <h2>Sección <?= e($genero['nombre']) ?></h2>
                    <p><?= e($genero['intro']) ?></p>
                </div>
            </section>
        <?php endforeach; ?>

        <section id="about-us" class="about-us">
            <h2>¿Qué es MzaBeats?</h2>
            <p>MzaBeats es una página web que almacena todas las bandas de la provincia de Mendoza, Argentina.
                Nuestra idea es que la cultura musical mendocina sea más reconocida en todo el mundo.
            </p>
            <h2>¿Cómo aparezco en la página?</h2>
            <p>Si sos una banda o un artista mendocino, solo tenés que completar el
                formulario de consulta con tu nombre, mail y razón de participar,
                y nos pondremos en contacto lo más rápido posible.</p>
        </section>
    </main>
    <footer>
        <?php if (mostrar_boton_participar()): ?>
            <input class="btn-participar" type="button" onclick="window.location.href='<?= e(url('vistas/formulario.php')) ?>';" value="¡Quiero aparecer!">
        <?php endif; ?>
        <?php if (puede_agregar_bandas()): ?>
            <input class="btn-agregar" type="button" onclick="window.location.href='<?= e(url('vistas/agregar_banda.php')) ?>';" value="Agregar banda">
        <?php endif; ?>
        <p>&copy;Todos los derechos reservados</p>
    </footer>
</body>
</html>
