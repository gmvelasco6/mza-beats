<?php
/**
 * Página del género Pop. El diseño está en backend/vistas/genero.php.
 */
require __DIR__ . '/../../backend/bootstrap.php';

$genero = 'pop';
$busqueda = isset($_GET['limpiar']) ? '' : texto_get('buscador');

vista('genero', [
    'genero'   => $genero,
    'busqueda' => $busqueda,
    'bandas'   => bandas_por_genero($genero, $busqueda),
]);
