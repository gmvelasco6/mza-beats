<?php
/**
 * Acceso a la tabla `consultas` (pedidos de bandas para aparecer en el sitio).
 */

function consulta_crear(string $nombre, string $razon, string $email): void
{
    db_ejecutar('INSERT INTO consultas (nombre, razon, email) VALUES (?, ?, ?)', 'sss', [$nombre, $razon, $email]);
}

function consultas_todas(): array
{
    return db_filas('SELECT * FROM consultas ORDER BY id ASC');
}

function consulta_eliminar(int $id): void
{
    db_ejecutar('DELETE FROM consultas WHERE id = ?', 'i', [$id]);
}

/** Valida una consulta. Devuelve el primer error encontrado, o null. */
function validar_consulta(string $nombre, string $razon, string $email): ?string
{
    if ($nombre === '' || $razon === '' || $email === '') {
        return 'Hay campos vacíos';
    }
    if (largo($nombre) > BANDA_NOMBRE_MAXIMO) {
        return 'El nombre es demasiado largo';
    }
    if (largo($razon) > CONSULTA_RAZON_MAXIMO) {
        return 'La razón es demasiado larga (máximo ' . CONSULTA_RAZON_MAXIMO . ' caracteres)';
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return 'El email no es válido';
    }
    return null;
}
