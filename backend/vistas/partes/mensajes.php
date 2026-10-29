<?php
/**
 * Muestra los avisos pendientes ("Hay campos vacíos", "Banda guardada", etc.).
 *
 * @var string|null $error  Error de la validación actual (opcional)
 */
$mensajes = mensajes_obtener();
if (!empty($error)) {
    $mensajes[] = ['tipo' => 'error', 'texto' => $error];
}
?>
<?php foreach ($mensajes as $mensaje): ?>
    <div class="<?= $mensaje['tipo'] === 'ok' ? 'verificado' : 'advertencia' ?>" align="center"><?= e($mensaje['texto']) ?></div>
<?php endforeach; ?>
