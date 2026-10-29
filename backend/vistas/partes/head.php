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
</head>
