<?php
/**
 * Acceso a la tabla `bandas`.
 */

function genero_es_valido(string $genero): bool
{
    return array_key_exists($genero, GENEROS);
}

/**
 * Bandas de un género, opcionalmente filtradas por nombre.
 * Los comodines de LIKE (% y _) que escriba el usuario se buscan como texto.
 */
function bandas_por_genero(string $genero, string $busqueda = ''): array
{
    if ($busqueda === '') {
        return db_filas('SELECT * FROM bandas WHERE genero = ? ORDER BY id ASC', 's', [$genero]);
    }

    $patron = '%' . addcslashes($busqueda, '%_\\') . '%';
    return db_filas(
        'SELECT * FROM bandas WHERE genero = ? AND nombre LIKE ? ORDER BY id ASC',
        'ss',
        [$genero, $patron]
    );
}

/** Las bandas nuevas se crean sin imágenes; las agrega un administrador después. */
function banda_crear(string $nombre, string $descripcion, string $genero): int
{
    db_ejecutar(
        "INSERT INTO bandas (nombre, descripcion, imagen_principal, imagen_fondo, genero) VALUES (?, ?, '', '', ?)",
        'sss',
        [$nombre, $descripcion, $genero]
    );
    return db()->insert_id;
}

function banda_actualizar(int $id, string $nombre, string $descripcion, string $imagenPrincipal, string $imagenFondo, string $genero): void
{
    db_ejecutar(
        'UPDATE bandas SET nombre = ?, descripcion = ?, imagen_principal = ?, imagen_fondo = ?, genero = ? WHERE id = ?',
        'sssssi',
        [$nombre, $descripcion, $imagenPrincipal, $imagenFondo, $genero, $id]
    );
}

function banda_eliminar(int $id): void
{
    db_ejecutar('DELETE FROM bandas WHERE id = ?', 'i', [$id]);
}

/**
 * Las rutas de imagen tienen que apuntar a un archivo dentro de images/
 * (por ejemplo images/rock/02.png). Así nadie puede meter una URL externa
 * o código en el atributo style de la página.
 */
function ruta_imagen_es_valida(string $ruta): bool
{
    return $ruta === ''
        || (preg_match('#^images/[A-Za-z0-9_\-/]+\.(png|jpe?g|webp|gif)$#', $ruta) === 1
            && strpos($ruta, '..') === false);
}

/** Valida los datos de una banda. Devuelve el primer error encontrado, o null. */
function validar_banda(string $nombre, string $descripcion, string $genero): ?string
{
    if ($nombre === '' || $descripcion === '') {
        return 'Hay campos vacíos';
    }
    if (largo($nombre) > BANDA_NOMBRE_MAXIMO) {
        return 'El nombre es demasiado largo';
    }
    if (largo($descripcion) > TEXTO_LARGO_MAXIMO) {
        return 'La descripción es demasiado larga';
    }
    if (!genero_es_valido($genero)) {
        return 'Elegí un género válido';
    }
    return null;
}
