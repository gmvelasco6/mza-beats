<?php
/**
 * Acceso a la tabla `usuarios`.
 */

function usuario_buscar_por_id(int $id): ?array
{
    return db_fila('SELECT * FROM usuarios WHERE id = ?', 'i', [$id]);
}

function usuario_buscar_por_usuario(string $nombreUsuario): ?array
{
    return db_fila('SELECT * FROM usuarios WHERE usuario = ?', 's', [$nombreUsuario]);
}

/** Lista para el panel de administración (sin la columna de la clave). */
function usuarios_todos(): array
{
    return db_filas('SELECT id, nombre, usuario, estado, cantidad_creaciones FROM usuarios ORDER BY id ASC');
}

/** ¿Ya hay otra cuenta con ese nombre de usuario? */
function usuario_existe(string $nombreUsuario, int $exceptoId = 0): bool
{
    return db_fila('SELECT id FROM usuarios WHERE usuario = ? AND id <> ?', 'si', [$nombreUsuario, $exceptoId]) !== null;
}

/** Las cuentas nuevas empiezan como usuario común y sin creaciones disponibles. */
function usuario_crear(string $nombre, string $nombreUsuario, string $claveHash): int
{
    db_ejecutar(
        'INSERT INTO usuarios (nombre, usuario, clave, estado, cantidad_creaciones) VALUES (?, ?, ?, ?, 0)',
        'sssi',
        [$nombre, $nombreUsuario, $claveHash, ESTADO_USUARIO]
    );
    return db()->insert_id;
}

function usuario_actualizar_perfil(int $id, string $nombre, string $nombreUsuario): void
{
    db_ejecutar('UPDATE usuarios SET nombre = ?, usuario = ? WHERE id = ?', 'ssi', [$nombre, $nombreUsuario, $id]);
}

function usuario_actualizar_clave(int $id, string $claveHash): void
{
    db_ejecutar('UPDATE usuarios SET clave = ? WHERE id = ?', 'si', [$claveHash, $id]);
}

function usuario_actualizar_permisos(int $id, int $estado, int $cantidadCreaciones): void
{
    db_ejecutar(
        'UPDATE usuarios SET estado = ?, cantidad_creaciones = ? WHERE id = ?',
        'iii',
        [$estado, $cantidadCreaciones, $id]
    );
}

/** Resta una creación disponible. Devuelve false si ya no le quedaba ninguna. */
function usuario_descontar_creacion(int $id): bool
{
    return db_ejecutar(
        'UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ? AND cantidad_creaciones > 0',
        'i',
        [$id]
    ) === 1;
}

function usuario_eliminar(int $id): void
{
    db_ejecutar('DELETE FROM usuarios WHERE id = ?', 'i', [$id]);
}

/** Valida nombre y usuario de una cuenta. Devuelve el primer error encontrado, o null. */
function validar_datos_de_cuenta(string $nombre, string $nombreUsuario, int $exceptoId = 0): ?string
{
    if ($nombre === '' || $nombreUsuario === '') {
        return 'Hay campos vacíos';
    }
    if (largo($nombre) > NOMBRE_LARGO_MAXIMO) {
        return 'El nombre es demasiado largo';
    }
    if (largo($nombreUsuario) < USUARIO_LARGO_MINIMO || largo($nombreUsuario) > USUARIO_LARGO_MAXIMO) {
        return 'El usuario tiene que tener entre ' . USUARIO_LARGO_MINIMO . ' y ' . USUARIO_LARGO_MAXIMO . ' caracteres';
    }
    if (preg_match('/^[A-Za-z0-9_.\-]+$/', $nombreUsuario) !== 1) {
        return 'El usuario sólo puede tener letras, números, punto, guion y guion bajo';
    }
    if (usuario_existe($nombreUsuario, $exceptoId)) {
        return 'Usuario existente, elige otro';
    }
    return null;
}
