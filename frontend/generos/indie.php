<?php
/**
 * Página del género Indie. El diseño está en backend/vistas/genero.php.
 */
require __DIR__ . '/../../backend/bootstrap.php';

$genero = 'indie';
$busqueda = isset($_GET['limpiar']) ? '' : texto_get('buscador');

vista('genero', [
    'genero'   => $genero,
    'busqueda' => $busqueda,
    'bandas'   => bandas_por_genero($genero, $busqueda),
]);
