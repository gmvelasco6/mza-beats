<?php
/**
 * Menú corto de las páginas de formularios: logo y un enlace al inicio.
 *
 * @var string $textoVolver  Texto del enlace (por defecto "Ir a Inicio")
 * @var string $claseNav     Clase CSS extra del <nav> (el panel admin usa "nav-admin")
 */
$textoVolver = $textoVolver ?? 'Ir a Inicio';
$claseNav = $claseNav ?? '';
?>
<nav<?= $claseNav !== '' ? ' class="' . e($claseNav) . '"' : '' ?>>
    <img class="logo" src="<?= e(url('images/logo/logoMzaBeats.png')) ?>" alt="MzaBeats">
    <ul class="nav-list">
        <li class="nav-list-item"><a class="link" href="<?= e(url('')) ?>"><?= e($textoVolver) ?></a></li>
    </ul>
</nav>
