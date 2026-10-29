<?php
/**
 * Sesión, login y permisos.
 *
 * En la sesión sólo se guarda el id del usuario. Sus datos (nombre, estado,
 * cantidad de creaciones) se leen de la base en cada pedido, así un cambio
 * hecho desde el panel de administración se aplica al instante.
 */

function iniciar_sesion_segura(): void
{
    $usaHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

    ini_set('session.use_strict_mode', '1');
    session_name('mzabeats_sesion');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $usaHttps,
        'httponly' => true,   // JavaScript no puede leer la cookie
        'samesite' => 'Lax',  // otros sitios no pueden mandarla en un POST
    ]);
    session_start();
}

/** Datos del usuario logueado, o null si no hay nadie logueado. */
function usuario_actual(): ?array
{
    static $usuario = null;
    static $cargado = false;

    if (!$cargado) {
        $cargado = true;
        if (!empty($_SESSION['usuario_id'])) {
            $usuario = usuario_buscar_por_id((int) $_SESSION['usuario_id']);
            if ($usuario === null) {
                // La cuenta fue eliminada mientras estaba logueado
                unset($_SESSION['usuario_id']);
            }
        }
    }

    return $usuario;
}

function esta_logueado(): bool
{
    return usuario_actual() !== null;
}

function es_admin(): bool
{
    return esta_logueado() && (int) usuario_actual()['estado'] === ESTADO_ADMIN;
}

/** Puede cargar bandas si tiene permisos y le quedan creaciones disponibles. */
function puede_agregar_bandas(): bool
{
    $usuario = usuario_actual();
    return $usuario !== null
        && (int) $usuario['estado'] >= ESTADO_USUARIO
        && (int) $usuario['cantidad_creaciones'] >= 1;
}

/** El botón "¡Quiero aparecer!" se muestra a visitantes y a cuentas sin permisos. */
function mostrar_boton_participar(): bool
{
    return !esta_logueado() || (int) usuario_actual()['estado'] === ESTADO_SIN_PERMISOS;
}

function requerir_login(): void
{
    if (!esta_logueado()) {
        redirigir('');
    }
}

function requerir_admin(): void
{
    if (!es_admin()) {
        redirigir('');
    }
}

/**
 * Valida usuario y contraseña. Si son correctos, inicia la sesión.
 *
 * Las cuentas viejas tienen la clave en MD5 (inseguro). Cuando una de esas
 * cuentas entra bien, su clave se vuelve a guardar con password_hash().
 */
function intentar_login(string $nombreUsuario, string $clave): bool
{
    $usuario = usuario_buscar_por_usuario($nombreUsuario);

    if ($usuario === null) {
        // Se calcula un hash igual para que el tiempo de respuesta no revele
        // si el usuario existe o no
        clave_hash($clave);
        return false;
    }

    if (!clave_es_correcta($clave, $usuario['clave'])) {
        return false;
    }

    if (clave_necesita_actualizarse($usuario['clave'])) {
        usuario_actualizar_clave((int) $usuario['id'], clave_hash($clave));
    }

    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int) $usuario['id'];
    return true;
}

function cerrar_sesion(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 3600,
            'path'     => $cookie['path'],
            'secure'   => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'],
        ]);
    }

    session_destroy();
}

function clave_hash(string $clave): string
{
    return password_hash($clave, PASSWORD_DEFAULT);
}

function clave_es_md5_vieja(string $hash): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $hash);
}

function clave_es_correcta(string $clave, string $hash): bool
{
    if (clave_es_md5_vieja($hash)) {
        return hash_equals($hash, md5($clave));
    }
    return password_verify($clave, $hash);
}

function clave_necesita_actualizarse(string $hash): bool
{
    return clave_es_md5_vieja($hash) || password_needs_rehash($hash, PASSWORD_DEFAULT);
}

/** Devuelve un mensaje de error si la clave nueva no cumple las reglas, o null si está bien. */
function validar_clave_nueva(string $clave, string $confirmacion): ?string
{
    if (largo($clave) < CLAVE_LARGO_MINIMO) {
        return 'La contraseña tiene que tener al menos ' . CLAVE_LARGO_MINIMO . ' caracteres';
    }
    if ($clave !== $confirmacion) {
        return 'Las contraseñas no coinciden';
    }
    return null;
}
