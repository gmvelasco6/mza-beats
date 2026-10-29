<?php
/**
 * Carga de una banda nueva.
 * Sólo para usuarios con permisos y creaciones disponibles; cada banda
 * cargada descuenta una creación.
 */
require __DIR__ . '/../../backend/bootstrap.php';

if (!puede_agregar_bandas()) {
    redirigir('index.php');
}

$error = null;

if (es_post()) {
    $nombre = texto_post('nombre');
    $descripcion = texto_post('descripcion');
    $genero = texto_post('genero');

    $error = validar_banda($nombre, $descripcion, $genero);

    if ($error === null) {
        $usuarioId = (int) usuario_actual()['id'];

        // La banda se guarda sólo si se pudo descontar la creación (y viceversa)
        $guardada = db_transaccion(function () use ($usuarioId, $nombre, $descripcion, $genero) {
            if (!usuario_descontar_creacion($usuarioId)) {
                return false;
            }
            banda_crear($nombre, $descripcion, $genero);
            return true;
        });

        if ($guardada) {
            redirigir("generos/$genero.php");
        }
        $error = 'No te quedan creaciones disponibles';
    }
}

vista('partes/head', ['titulo' => 'Agregar Banda', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple', ['textoVolver' => 'Volver a Inicio']); ?>
    <main>
        <div class="formulario-container">
            <form class="formulario" method="POST">
                <?= csrf_campo() ?>
                <h1>Agregar Banda</h1>

                <?php vista('partes/mensajes', ['error' => $error]); ?>

                <h2>Nombre de la banda:</h2>
                <input type="text" id="nombre" name="nombre" required placeholder="Ejemplo: Los Rockeros" value="<?= e(texto_post('nombre')) ?>" maxlength="<?= BANDA_NOMBRE_MAXIMO ?>">

                <h2>Cuéntanos sobre la banda:</h2>
                <textarea id="descripcion" name="descripcion" required placeholder="Describe la historia de la banda, su estilo musical, integrantes, etc." cols="50" rows="6" maxlength="<?= TEXTO_LARGO_MAXIMO ?>"><?= e(texto_post('descripcion')) ?></textarea>

                <p>Por favor, mandá fotos de tu banda al siguiente correo:<a href="mailto:mzabeatsbandas@gmail.com"> mzabeatsbandas@gmail.com</a></p>

                <h2>Género musical:</h2>
                <select id="genero" name="genero" required>
                    <option value="">Selecciona un género</option>
                    <?php foreach (GENEROS as $clave => $datosGenero): ?>
                        <option value="<?= e($clave) ?>" <?= texto_post('genero') === $clave ? 'selected' : '' ?>><?= e($datosGenero['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-submit">Guardar Banda</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='<?= e(url('index.php')) ?>'">Cancelar</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
