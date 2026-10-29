<?php
/**
 * Funciones de ayuda usadas en todo el sitio.
 */

/**
 * Escapa un valor para mostrarlo en HTML.
 * Usala SIEMPRE que imprimas algo que venga de la base de datos o del usuario.
 */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Convierte un texto en un string de JavaScript seguro, para usar dentro de
 * atributos como onclick. Ejemplo: onclick="return confirm(<?= e(js($texto)) ?>)"
 */
function js(string $texto): string
{
    return json_encode($texto, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
}

/**
 * Arma una ruta relativa a la carpeta frontend/ desde la página actual.
 *
 * Ejemplo: url('generos/rock.php') devuelve "generos/rock.php" desde index.php
 * y "../../generos/rock.php" desde vistas/administrar/usuario.php.
 * Así los enlaces funcionan igual en Docker y en una subcarpeta de XAMPP.
 *
 * url('') es la página de inicio, sin "index.php" en la dirección.
 */
function url(string $ruta = ''): string
{
    static $prefijo = null;

    if ($prefijo === null) {
        $carpetaPagina = str_replace('\\', '/', dirname((string) realpath($_SERVER['SCRIPT_FILENAME'] ?? '')));
        $carpetaFrontend = str_replace('\\', '/', (string) realpath(RAIZ_FRONTEND));
        $subcarpeta = trim(substr($carpetaPagina, strlen($carpetaFrontend)), '/');
        $prefijo = $subcarpeta === '' ? '' : str_repeat('../', substr_count($subcarpeta, '/') + 1);
    }

    $destino = $prefijo . ltrim($ruta, '/');
    return $destino === '' ? './' : $destino;
}

/** Redirige a otra página del sitio y corta la ejecución. */
function redirigir(string $ruta): void
{
    header('Location: ' . url($ruta));
    exit;
}

/** Incluye una vista de backend/vistas/ pasándole sólo los datos indicados. */
function vista(string $nombre, array $datos = []): void
{
    extract($datos, EXTR_SKIP);
    require RAIZ_BACKEND . '/vistas/' . $nombre . '.php';
}

function es_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/** Lee un campo de texto enviado por POST, sin espacios al principio ni al final. */
function texto_post(string $campo): string
{
    $valor = $_POST[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

/** Lee un campo de texto enviado por GET (por ejemplo, el buscador). */
function texto_get(string $campo): string
{
    $valor = $_GET[$campo] ?? '';
    return is_string($valor) ? trim($valor) : '';
}

/** Lee un número entero enviado por POST. Devuelve null si no es un entero válido. */
function entero_post(string $campo): ?int
{
    $valor = filter_var(texto_post($campo), FILTER_VALIDATE_INT);
    return $valor === false ? null : $valor;
}

/**
 * IP del visitante. Si el pedido llega a través de un proxy de la red interna
 * (Docker, Tailscale Funnel, Cloudflare Tunnel), se usa la IP que informa el proxy.
 */
function ip_cliente(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $esProxyInterno = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    $reenviada = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '')[0]);

    if ($esProxyInterno && filter_var($reenviada, FILTER_VALIDATE_IP) !== false) {
        return $reenviada;
    }
    return $ip;
}

function largo(string $texto): int
{
    return mb_strlen($texto, 'UTF-8');
}

/**
 * Mensajes que sobreviven a una redirección (por ejemplo "Banda guardada").
 * $tipo es 'ok' o 'error'.
 */
function mensaje_guardar(string $tipo, string $texto): void
{
    $_SESSION['mensajes'][] = ['tipo' => $tipo, 'texto' => $texto];
}

function mensajes_obtener(): array
{
    $mensajes = $_SESSION['mensajes'] ?? [];
    unset($_SESSION['mensajes']);
    return $mensajes;
}

/**
 * Nunca mostramos errores internos al visitante: se guardan en el log del
 * servidor y se muestra un aviso genérico. Con APP_DEBUG=1 se ven en pantalla.
 */
function configurar_errores(): void
{
    $modoDebug = getenv('APP_DEBUG') === '1' || ($_SERVER['APP_DEBUG'] ?? '') === '1';
    ini_set('display_errors', $modoDebug ? '1' : '0');
    ini_set('log_errors', '1');

    if ($modoDebug) {
        return;
    }

    set_exception_handler(function (Throwable $error): void {
        error_log((string) $error);
        if (!headers_sent()) {
            http_response_code(500);
        }
        echo '<p style="font-family: sans-serif; text-align: center; margin-top: 3em">'
            . 'Ocurrió un error inesperado. Probá de nuevo en unos minutos.</p>';
    });
}

function enviar_cabeceras_de_seguridad(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
