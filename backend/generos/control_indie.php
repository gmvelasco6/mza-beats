<?php
$buscador = $_GET['buscador'] ?? '';

if (isset($_GET['limpiar'])) {
    $buscador = '';
}
$resultado = ($buscador != '')
    ? $conexion->query("SELECT * FROM bandas WHERE genero = 'indie' AND nombre LIKE '%$buscador%' ORDER BY id ASC")
    : $conexion->query("SELECT * FROM bandas WHERE genero = 'indie' ORDER BY id ASC");
if($resultado->num_rows==0){
    echo '<h2 class="notificacion">No se encontraron resultados</h2>';
}
$c = 0;
while ($row = $resultado->fetch_assoc()) {
    $nombre = $row['nombre'];
    $descripcion = $row['descripcion'];

    $imgPrincipal = '../' . $row['imagen_principal'];
    $imgFondo = '../' . $row['imagen_fondo'];

    $bgStyle = $imgFondo !== '../'? "--item-bg: url('" . $imgFondo . "');": '';

    $c++;
    if ($c % 2 != 0) {
        echo '<hr>';
        echo '<div class="section" style="' . $bgStyle . '">';
        echo '<div><img class="img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
        echo '<div class="description">';
        echo '<h2>' . $nombre . '</h2>';
        echo '<p>' . $descripcion . '</p>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<hr>';
        echo '<div class="section-reverse" style="' . $bgStyle . '">';
        echo '<div><img class="img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
        echo '<div class="description">';
        echo '<h2>' . $nombre . '</h2>';
        echo '<p>' . $descripcion . '</p>';
        echo '</div>';
        echo '</div>';
    }
}
?>