<?php
// Iniciar sesión y configurar el tipo de respuesta
session_start();
// Keep logging but send plain text responses (simple "OK:..." or "ERROR:...")
header('Content-Type: text/plain; charset=utf-8');

// Logger helpers (siempre definidos)
$debugLog = __DIR__ . '/../bd/add_band_debug.log';
function log_debug($msg) {
    global $debugLog;
    $line = '['.date('Y-m-d H:i:s').'] ' . $msg . PHP_EOL;
    @file_put_contents($debugLog, $line, FILE_APPEND);
}
function echo_text($success, $message) {
    $prefix = $success ? 'OK:' : 'ERROR:';
    log_debug('RESPONSE: ' . $prefix . ' ' . $message);
    echo $prefix . ' ' . $message;
    exit;
}

// 1. Verificar que el usuario tenga permisos
if(empty($_SESSION["id"]) || $_SESSION["state"] != "1") {
    log_debug('Acceso denegado: usuario sin permiso');
    echo_text(false, 'No tienes permiso para realizar esta acción.');
}

// 2. Conectar con la base de datos
include __DIR__ . '/../bd/conexion_bd.php';

// 3. Preparar la carpeta para las imágenes
$carpetaImagenes = __DIR__ . '/../images/bandas/';
if (!is_dir($carpetaImagenes)) {
    mkdir($carpetaImagenes, 0755, true);
}

// 4. Inicializar respuesta
$respuesta = ['success' => false, 'message' => 'No se recibió información válida.'];

// 5. Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $genero = trim($_POST['genero'] ?? 'indie');

    // Verificar datos obligatorios
    if ($nombre === '' || $descripcion === '') {
        echo_text(false, 'Por favor completa todos los campos.');
    }

    // 6. Procesar la imagen principal
    $imagen_principal = '';
    if (!empty($_FILES['imagen_principal']['name'])) {
        $archivo = $_FILES['imagen_principal'];
            // Verificar errores de subida
            if (isset($archivo['error']) && $archivo['error'] !== UPLOAD_ERR_OK) {
                $msg = 'Error en subida de imagen principal. Código: ' . $archivo['error'];
                log_debug($msg);
                echo_text(false, $msg);
            }
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        // Verificar que sea una imagen válida
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $nombreArchivo = 'principal_' . uniqid() . '.' . $extension;
            $rutaCompleta = $carpetaImagenes . $nombreArchivo;
            
            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                $imagen_principal = 'images/bandas/' . $nombreArchivo;
            } else {
                $msg = 'No se pudo mover la imagen principal a: ' . $rutaCompleta;
                log_debug($msg);
                echo_text(false, $msg);
            }
        }
    }

    // 7. Procesar la imagen de fondo
    $imagen_fondo = '';
    if (!empty($_FILES['imagen_fondo']['name'])) {
        $archivo = $_FILES['imagen_fondo'];
        // Verificar errores de subida
        if (isset($archivo['error']) && $archivo['error'] !== UPLOAD_ERR_OK) {
            $msg = 'Error en subida de imagen de fondo. Código: ' . $archivo['error'];
            log_debug($msg);
            echo_text(false, $msg);
        }
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        // Verificar que sea una imagen válida
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $nombreArchivo = 'fondo_' . uniqid() . '.' . $extension;
            $rutaCompleta = $carpetaImagenes . $nombreArchivo;

            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                $imagen_fondo = 'images/bandas/' . $nombreArchivo;
            } else {
                $msg = 'No se pudo mover la imagen de fondo a: ' . $rutaCompleta;
                log_debug($msg);
                echo_text(false, $msg);
            }
        }
    }

    // 8. Verificar que se subieron las imágenes
    if ($imagen_principal === '' || $imagen_fondo === '') {
        $msg = 'Error al subir las imágenes. Asegúrate de subir ambas imágenes en formato jpg, jpeg, png o gif.';
        log_debug($msg);
        echo_text(false, $msg);
    }

    // 9. Guardar en la base de datos (modo simple y seguro)
    try {
        // Detectar columnas existentes en la tabla bandas
        $existing = [];
        $resCols = $conexion->query("SHOW COLUMNS FROM bandas");
        if ($resCols) {
            while ($c = $resCols->fetch_assoc()) {
                $existing[] = $c['Field'];
            }
        }

        // Construir INSERT dinámico solo con columnas existentes
        $fields = ['nombre', 'descripcion'];
        $placeholders = ['?', '?'];
        $values = [$nombre, $descripcion];
        $types = 'ss';

        if (in_array('imagen_principal', $existing)) {
            $fields[] = 'imagen_principal'; $placeholders[] = '?'; $values[] = $imagen_principal; $types .= 's';
        }
        if (in_array('imagen_fondo', $existing)) {
            $fields[] = 'imagen_fondo'; $placeholders[] = '?'; $values[] = $imagen_fondo; $types .= 's';
        }
        if (in_array('genero', $existing)) {
            $fields[] = 'genero'; $placeholders[] = '?'; $values[] = $genero; $types .= 's';
        }

        // --- Protección contra envíos duplicados: si existe columna de tiempo, impedir mismo nombre en X segundos
        $timeCol = null;
        if (in_array('fecha_creacion', $existing)) $timeCol = 'fecha_creacion';
        elseif (in_array('creado_en', $existing)) $timeCol = 'creado_en';
        if ($timeCol) {
            $dupSql = "SELECT id FROM bandas WHERE nombre = ? AND $timeCol >= (NOW() - INTERVAL 20 SECOND) LIMIT 1";
            $dupStmt = $conexion->prepare($dupSql);
            if ($dupStmt) {
                $dupStmt->bind_param('s', $nombre);
                $dupStmt->execute();
                $dupStmt->store_result();
                if ($dupStmt->num_rows > 0) {
                    log_debug('Duplicado detectado para nombre: ' . $nombre);
                    echo_text(false, 'Parece que ya enviaste esa banda hace poco, espera unos segundos antes de intentarlo.');
                }
            }
        }

        $sql = 'INSERT INTO bandas (' . implode(',', $fields) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $conexion->prepare($sql);
        if ($stmt === false) {
            $msg = 'Error al preparar consulta: ' . $conexion->error;
            log_debug($msg);
            echo_text(false, $msg);
        }

        // bind_param requiere referencias
        $params = array_merge([$types], $values);
        $refs = [];
        foreach ($params as $k => $v) $refs[$k] = &$params[$k];
        if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
            $msg = 'Error al bind_param: ' . $stmt->error;
            log_debug($msg);
            echo_text(false, $msg);
        }

        if ($stmt->execute()) {
            $msg = 'Inserción correcta. ID: ' . $stmt->insert_id;
            log_debug($msg);
            echo_text(true, '¡La banda se agregó correctamente!');
        } else {
            $msg = 'Error al ejecutar consulta: ' . $stmt->error;
            log_debug($msg);
            echo_text(false, $msg);
        }
    } catch (Exception $e) {
        $msg = 'Exception: ' . $e->getMessage();
        log_debug($msg);
        echo_text(false, 'Error en el servidor: ' . $e->getMessage());
    }
} else {
    echo_text(false, 'Método no permitido');
}
?>
