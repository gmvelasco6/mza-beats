<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Banda</title>
    <link rel="stylesheet" href="../css/indie-style.css">
    <link rel="stylesheet" href="../css/agregar-style.css">
    <link rel="shortcut icon" href="../images/logo/page-icon.png" type="image/x-icon">
</head>
<body>
<?php
    session_start();
    // Verificar si el usuario está logueado y tiene permisos
    if(empty($_SESSION["id"]) || $_SESSION["state"] != "1"){
        header("Location: ../index.php");
        exit;
    }
?>
<!-- Menú simple -->
<header id="inicio">
    <nav style="background: rgba(12, 35, 37, 0.9); padding: 1rem;">
        <a href="../index.php" style="color: white; text-decoration: none; margin-right: 20px;">Inicio</a>
        <a href="../generos/indie.php" style="color: white; text-decoration: none;">Volver a Indie</a>
    </nav>
</header>
<main>
    <div class="form-banda-container">
        <form id="form-banda" class="form-banda" enctype="multipart/form-data" method="POST" action="../controladores/control_add_band.php">
            <h2>Agregar Nueva Banda de Indie</h2>
            
            <!-- Nombre de la banda -->
            <div class="form-group">
                <label for="nombre">Nombre de la banda:</label>
                <input type="text" 
                       id="nombre" 
                       name="nombre" 
                       required 
                       placeholder="Ejemplo: Los Rockeros">
            </div>
            
            <!-- Descripción de la banda -->
            <div class="form-group">
                <label for="descripcion">Cuéntanos sobre la banda:</label>
                <textarea id="descripcion" 
                         name="descripcion" 
                         required 
                         placeholder="Describe la historia de la banda, su estilo musical, integrantes, etc."
                         rows="6"></textarea>
            </div>
            
            <!-- Imágenes -->
            <div class="form-group">
                <label for="imagen_principal">Foto principal de la banda:</label>
                <input type="file" 
                       id="imagen_principal" 
                       name="imagen_principal" 
                       accept="image/*" 
                       required>
                <div class="preview-container" id="preview-principal"></div>
                <small style="color: #fff">Esta imagen aparecerá como la foto principal de la banda</small>
            </div>

            <div class="form-group">
                <label for="imagen_fondo">Imagen de fondo:</label>
                <input type="file" 
                       id="imagen_fondo" 
                       name="imagen_fondo" 
                       accept="image/*" 
                       required>
                <div class="preview-container" id="preview-fondo"></div>
                <small style="color: #fff">Esta imagen se usará como fondo de la descripción</small>
            </div>

            <!-- Campo oculto para el género -->
            <input type="hidden" name="genero" value="indie">
            
            <!-- Botones -->
            <div class="buttons">
                <button type="submit" class="btn-submit">Guardar Banda</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='../generos/indie.php'">Cancelar</button>
            </div>

            <!-- Mensajes de éxito o error -->
            <div id="response-message"></div>
        </form>
    </div>
</main>
<footer>
    <a href="#inicio" class="flecha">&uparrow;</a>
    <input class="btn-participar" type="submit" onclick="window.location.href='../php/formulario.php';" value="¡Quiero aparecer!">
    <p>&copy;Derechos de autor a Basigalup y Velasco</p>
</footer>
<script>
    // Previsualización de imágenes
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.getElementById('imagen_principal').addEventListener('change', function() {
        previewImage(this, 'preview-principal');
    });
    document.getElementById('imagen_fondo').addEventListener('change', function() {
        previewImage(this, 'preview-fondo');
    });
    // Manejo del formulario (usa texto plano: "OK: ..." o "ERROR: ...")
    document.getElementById('form-banda').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const messageDiv = document.getElementById('response-message');
        messageDiv.textContent = 'Guardando...';
        messageDiv.className = 'response-message';

        fetch('../controladores/control_add_band.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.text())
        .then(text => {
            // Interpreta prefijos simples
            if (text.startsWith('OK:')) {
                const msg = text.slice(3).trim();
                messageDiv.textContent = msg || 'Operación correcta.';
                messageDiv.className = 'response-message success';
                setTimeout(() => location.href = '../generos/indie.php', 1200);
            } else if (text.startsWith('ERROR:')) {
                const msg = text.slice(6).trim();
                messageDiv.textContent = msg || 'Error en la operación.';
                messageDiv.className = 'response-message error';
            } else {
                // Respuesta inesperada
                messageDiv.textContent = text;
                messageDiv.className = 'response-message error';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDiv.textContent = 'Error al procesar la solicitud';
            messageDiv.className = 'response-message error';
        });
    });
</script>
</body>
</html>