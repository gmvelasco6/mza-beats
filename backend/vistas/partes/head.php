<?php
/**
 * Comienzo de cada página: doctype, <head> y estilos.
 *
 * @var string $titulo        Texto de la pestaña del navegador
 * @var string $hojaDeEstilo  Ruta del CSS dentro de frontend/ (ej. 'css/style.css')
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= e(url($hojaDeEstilo)) ?>">
    <link rel="shortcut icon" href="<?= e(url('images/logo/page-icon.png')) ?>" type="image/x-icon">
    <title><?= e($titulo) ?></title>
    <?php if (variable_entorno('UMAMI_WEBSITE_ID', '') !== ''): ?>
        <!-- Estadísticas de visitas (Umami). Sólo se activa si está configurado UMAMI_WEBSITE_ID -->
        <script defer src="<?= e(variable_entorno('UMAMI_SCRIPT_URL', '/u/script.js')) ?>" data-website-id="<?= e(variable_entorno('UMAMI_WEBSITE_ID', '')) ?>"></script>
    <?php endif; ?>
</head>
