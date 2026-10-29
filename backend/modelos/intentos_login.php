<?php
/**
 * Acceso a la tabla `intentos_login`: registra los logins fallidos para
 * bloquear temporalmente a quien prueba contraseñas sin parar.
 */

/** ¿Hay demasiados intentos fallidos recientes para este usuario o esta IP? */
function login_bloqueado(string $nombreUsuario, string $ip): bool
{
    $porUsuario = db_fila(
        'SELECT COUNT(*) AS total FROM intentos_login WHERE usuario = ? AND creado_en > NOW() - INTERVAL ? MINUTE',
        'si',
        [$nombreUsuario, LOGIN_BLOQUEO_MINUTOS]
    );
    $porIp = db_fila(
        'SELECT COUNT(*) AS total FROM intentos_login WHERE ip = ? AND creado_en > NOW() - INTERVAL ? MINUTE',
        'si',
        [$ip, LOGIN_BLOQUEO_MINUTOS]
    );

    return (int) $porUsuario['total'] >= LOGIN_MAX_INTENTOS_USUARIO
        || (int) $porIp['total'] >= LOGIN_MAX_INTENTOS_IP;
}

function login_registrar_fallo(string $nombreUsuario, string $ip): void
{
    db_ejecutar('INSERT INTO intentos_login (usuario, ip) VALUES (?, ?)', 'ss', [$nombreUsuario, $ip]);
    // Los registros viejos ya no sirven para nada
    db_ejecutar('DELETE FROM intentos_login WHERE creado_en < NOW() - INTERVAL 1 DAY');
}

function login_limpiar_fallos(string $nombreUsuario): void
{
    db_ejecutar('DELETE FROM intentos_login WHERE usuario = ?', 's', [$nombreUsuario]);
}
