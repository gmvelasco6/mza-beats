<?php
// Muestra las bandas en HTML simple (nombre, descripcion, miniaturas)
include __DIR__ . '/../bd/conexion_bd.php';

// Verificar qué columnas existen para construir una consulta segura
$available = [];
$colsRes = $conexion->query("SHOW COLUMNS FROM bandas");
if ($colsRes) {
    while ($c = $colsRes->fetch_assoc()) {
        $available[] = $c['Field'];
    }
}

// Columnas que vamos a seleccionar si existen
$select = ['id', 'nombre', 'descripcion'];
if (in_array('imagen_principal', $available)) $select[] = 'imagen_principal';
if (in_array('imagen_fondo', $available)) $select[] = 'imagen_fondo';
// genero and fecha_creacion may be absent; handle where/order accordingly
$where = '';
$order = '';
if (in_array('genero', $available)) {
    $where = "WHERE genero = 'indie'";
}
if (in_array('fecha_creacion', $available)) {
    $order = 'ORDER BY fecha_creacion DESC';
}

$sql = 'SELECT ' . implode(', ', $select) . ' FROM bandas ' . $where . ' ' . $order;
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    echo "<section class='bandas-agregadas'>";
    echo "<h2 style='color:#fff;margin-bottom:20px;'>Bandas Agregadas</h2>";
        while($row = $resultado->fetch_assoc()){
            // Evitar notices si columnas no fueron seleccionadas
            $principalRoute = isset($row['imagen_principal']) ? $row['imagen_principal'] : '';
            $fondoRoute = isset($row['imagen_fondo']) ? $row['imagen_fondo'] : '';

            // Usar la clase .indie-container-uno y .indie-description-uno para aplicar tu estilo
            // Para permitir fondo por banda sin romper la CSS global, usamos una variable CSS --item-bg
            $sectionStyleAttr = '';
            if (!empty($fondoRoute)) {
                $sectionStyleAttr = "style=\"--item-bg: url('../" . htmlspecialchars($fondoRoute) . "');\"";
            }

            echo "<section class='indie-container-uno indie-item' " . $sectionStyleAttr . ">";

            // Imagen principal a la izquierda (o placeholder)
            if (!empty($principalRoute)) {
                echo "<div class='indie-thumb'><img src='../" . htmlspecialchars($principalRoute) . "' alt='" . htmlspecialchars($row['nombre']) . "' /></div>";
            } else {
                echo "<div class='indie-placeholder'>Sin imagen</div>";
            }

            // Descripción a la derecha usando la clase que suministraste
            echo "<div class='indie-description-uno'>";
            echo "<h2>" . htmlspecialchars($row['nombre']) . "</h2>";
            echo "<p>" . nl2br(htmlspecialchars($row['descripcion'])) . "</p>";
            echo "</div>";

            echo "</section>";
        }
    echo "</section>";
} else {
    echo "<div style='padding:16px;margin:20px;background:rgba(12, 35, 37, 0.9);border-radius:10px;color:#fff;'>No hay bandas agregadas en esta sección aún.</div>";
}

?>
