<?php
/**
 * Arranque común de la aplicación.
 *
 * Cada página de frontend/ lo incluye en su primera línea. Se encarga de:
 *   - cargar la configuración, las funciones de ayuda y los modelos;
 *   - iniciar la sesión con cookies seguras;
 *   - rechazar cualquier envío POST sin token CSRF válido.
 *
 * backend/ queda fuera de la carpeta pública, así que nada de lo que está
 * acá se puede abrir directamente desde el navegador.
 */

define('RAIZ_BACKEND', __DIR__);
define('RAIZ_FRONTEND', dirname(__DIR__) . '/frontend');

require RAIZ_BACKEND . '/config/app.php';
require RAIZ_BACKEND . '/lib/helpers.php';
require RAIZ_BACKEND . '/lib/db.php';
require RAIZ_BACKEND . '/lib/csrf.php';
require RAIZ_BACKEND . '/lib/auth.php';
require RAIZ_BACKEND . '/modelos/bandas.php';
require RAIZ_BACKEND . '/modelos/usuarios.php';
require RAIZ_BACKEND . '/modelos/consultas.php';

configurar_errores();
enviar_cabeceras_de_seguridad();
iniciar_sesion_segura();

if (es_post()) {
    csrf_verificar();
}
