<?php
/**
 * Protección CSRF.
 *
 * Evita que otra página web envíe formularios a MzaBeats en nombre de un
 * usuario logueado. Cada formulario POST lleva un token secreto de la sesión
 * (csrf_campo()) y bootstrap.php rechaza cualquier POST que no lo traiga.
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Campo oculto para poner dentro de cada <form method="post">. */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_verificar(): void
{
    $enviado = $_POST['csrf_token'] ?? '';

    if (!is_string($enviado) || !hash_equals(csrf_token(), $enviado)) {
        http_response_code(400);
        exit('El formulario venció o no es válido. Volvé atrás, recargá la página y probá de nuevo.');
    }
}
