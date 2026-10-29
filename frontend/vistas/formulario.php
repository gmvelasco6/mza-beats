<?php
/**
 * Formulario "¡Quiero aparecer!": una banda pide sumarse al sitio.
 * Las consultas se ven en el panel de administración.
 */
require __DIR__ . '/../../backend/bootstrap.php';

$error = null;

if (es_post()) {
    $nombre = texto_post('nombre');
    $razon = texto_post('razon');
    $email = texto_post('email');

    $error = validar_consulta($nombre, $razon, $email);

    if ($error === null) {
        consulta_crear($nombre, $razon, $email);
        mensaje_guardar('ok', 'Consulta registrada correctamente');
        redirigir('vistas/formulario.php');
    }
}

vista('partes/head', ['titulo' => '¡Quiero aparecer!', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple'); ?>
    <div class="formulario-container">
        <h1>¡Se parte de MzaBeats!</h1>
        <p>Completá este formulario <br> para poder ser parte de la página web.</p>
        <?php vista('partes/mensajes', ['error' => $error]); ?>
        <form class="formulario" method="post">
            <?= csrf_campo() ?>
            <h2>Nombre</h2>
            <input type="text" id="nombre" class="input" name="nombre" placeholder="Nombre de la Banda" value="<?= e(texto_post('nombre')) ?>" required maxlength="<?= BANDA_NOMBRE_MAXIMO ?>">
            <h2>Razon</h2>
            <textarea id="razon" name="razon" required placeholder="Escribi la razon por la cual queres aparecer en MzaBeats..." cols="35" rows="6" maxlength="<?= CONSULTA_RAZON_MAXIMO ?>"><?= e(texto_post('razon')) ?></textarea>
            <h2>Email</h2>
            <input type="email" id="email" class="input" name="email" value="<?= e(texto_post('email')) ?>" required>
            <br>
            <input type="submit" name="btn-crear" class="btn" value="Enviar">
        </form>
    </div>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
