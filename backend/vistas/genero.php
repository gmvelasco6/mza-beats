<?php
/**
 * Página de un género (Indie, Pop, Rock...).
 *
 * @var string $genero    Clave del género en GENEROS (ej. 'rock')
 * @var string $busqueda  Texto buscado, o '' si no se buscó nada
 * @var array  $bandas    Bandas a mostrar
 */
$info = GENEROS[$genero];

vista('partes/head', ['titulo' => $info['nombre'], 'hojaDeEstilo' => 'css/genero-style.css']);
?>
<body>
    <header id="inicio">
        <?php vista('partes/nav_sitio'); ?>
    </header>
    <main>
        <form class="buscador-container" method="GET">
            <input type="text" name="buscador" placeholder="Buscar banda..." value="<?= e($busqueda) ?>">
            <div class="botones-buscador">
                <button type="submit" name="buscar">Buscar</button>
                <button type="submit" name="limpiar">Limpiar</button>
            </div>
        </form>

        <?php if ($busqueda === ''): ?>
            <section id="<?= e($genero) ?>" class="container">
                <img src="<?= e(url($info['imagen'])) ?>" alt="imagen_<?= e($info['nombre']) ?>" class="img-intro">
                <div class="description">
                    <h2>Sección <?= e($info['nombre']) ?></h2>
                    <p><?= e($info['intro']) ?></p>
                </div>
            </section>
        <?php endif; ?>

        <?php if (empty($bandas)): ?>
            <h2 class="notificacion">No se encontraron resultados</h2>
        <?php endif; ?>

        <?php foreach ($bandas as $posicion => $banda): ?>
            <?php
                // Las bandas se muestran alternando la imagen a izquierda y derecha
                $clase = $posicion % 2 === 0 ? 'section' : 'section-reverse';
                $imagen = ruta_imagen_es_valida($banda['imagen_principal']) && $banda['imagen_principal'] !== ''
                    ? $banda['imagen_principal']
                    : IMAGEN_BANDA_POR_DEFECTO;
                $estiloFondo = ruta_imagen_es_valida($banda['imagen_fondo']) && $banda['imagen_fondo'] !== ''
                    ? "--item-bg: url('" . url($banda['imagen_fondo']) . "');"
                    : '';
            ?>
            <hr>
            <div class="<?= $clase ?>" style="<?= e($estiloFondo) ?>">
                <div><img class="img" src="<?= e(url($imagen)) ?>" alt="<?= e($banda['nombre']) ?>"></div>
                <div class="description">
                    <h2><?= e($banda['nombre']) ?></h2>
                    <p><?= e($banda['descripcion']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    <footer>
        <a href="#inicio" class="flecha">&uparrow;</a>
        <?php if (mostrar_boton_participar()): ?>
            <input class="btn-participar" type="button" onclick="window.location.href='<?= e(url('vistas/formulario.php')) ?>';" value="¡Quiero aparecer!">
        <?php endif; ?>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
