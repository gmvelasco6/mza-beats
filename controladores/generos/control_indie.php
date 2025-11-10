<?php
$resultado = $conexion->query("SELECT * FROM bandas WHERE genero = 'indie' ORDER BY id ASC");
$c = 0;
// se encarga de verificar si la consulta fue exitosa y devolvio resultados
if ($resultado) {
    // Recorrer cada fila (banda) del resultado de la consulta
    while ($row = $resultado->fetch_assoc()) {
        // Extraer los datos de cada banda del array 
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
<<<<<<< HEAD
        // sirve para subir las rutas de las imágenes 
=======

        // Construir rutas de las imágenes agregando '../' para subir un directorio
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
        $imgPrincipal = '../' . $row['imagen_principal'];
        $imgFondo = '../' . $row['imagen_fondo'];
        // sirve para crear el estilo CSS para la imagen de fondo 
        $bgStyle = $imgFondo !== '../' ? "--item-bg: url('" . $imgFondo . "');" : '';
<<<<<<< HEAD
        // se utiliza para cambiar los diseños de las secciones (contador par o impar)
=======
        
        // Incrementar contador para alternar diseños
>>>>>>> 5deddf882ae5ff82a53861c5c39914f44fe0e28a
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