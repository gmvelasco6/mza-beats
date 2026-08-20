<?php
$resultado = $conexion->query("SELECT * FROM bandas WHERE genero = 'rock' ORDER BY id ASC");
$c = 0;
// se encarga de verificar si la consulta fue exitosa y devolvio resultados
if ($resultado) {
    // Recorrer cada fila (banda) del resultado de la consulta
    while ($row = $resultado->fetch_assoc()) {
        // Extraer los datos de cada banda del array asociativo
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        // Construir rutas de las imágenes agregando '../' para subir un directorio
        $imgPrincipal = '../' . $row['imagen_principal'];
        $imgFondo = '../' . $row['imagen_fondo'];
        
        // Crear estilo CSS para imagen de fondo (si existe una ruta válida)
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