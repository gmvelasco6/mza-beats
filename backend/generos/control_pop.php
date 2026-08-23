<?php
$buscador = $_GET['buscador'] ?? '';

if (isset($_GET['limpiar'])) {
    $buscador = '';
}
$resultado = ($buscador != '')
    ? $conexion->query("SELECT * FROM bandas WHERE genero = 'pop' AND nombre LIKE '%$buscador%' ORDER BY id ASC")
    : $conexion->query("SELECT * FROM bandas WHERE genero = 'pop' ORDER BY id ASC");
if($resultado->num_rows==0){
    echo '<h2 class="notificacion">No se encontraron resultados</h2>';
}
$c = 0;
// se encarga de verificar si la consulta fue exitosa y devolvio resultados
if ($resultado) {
    // sirve para recorrer cada fila (banda) 
    while ($row = $resultado->fetch_assoc()) {
        // sirve para extraer los datos de cada banda del array
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        // sirve para subir las rutas de las imágenes
        $imgPrincipal = '../' . $row['imagen_principal'];
        $imgFondo = '../' . $row['imagen_fondo'];
        
        // Crear estilo CSS para imagen de fondo 
        $bgStyle = $imgFondo !== '../' ? "--item-bg: url('" . $imgFondo . "');" : '';
         // se utiliza para cambiar los diseños de las secciones (contador par o impar)
        $c++;
        
        // Alternar entre dos diseños diferentes según si el contador es par o impar
        if($c % 2 != 0){
            echo '<hr>';
            echo '<div class="section" style="' . $bgStyle . '">';
            echo '<div><img class="img" src="' . $imgPrincipal . '" alt="' . $nombre . '"></div>';
            echo '<div class="description">';
            echo '<h2>' . $nombre . '</h2>';
            echo '<p>' . $descripcion . '</p>';
            echo '</div>';
            echo '</div>';
        }else{
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
}
?>