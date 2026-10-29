<?php
/**
 * "Mi cuenta": el usuario logueado cambia su nombre y su nombre de usuario.
 * Siempre se edita la cuenta de la sesión, nunca un id que venga del formulario.
 */
require __DIR__ . '/../../../backend/bootstrap.php';

requerir_login();

$usuario = usuario_actual();
$error = null;

if (es_post()) {
    $nombre = texto_post('nombre');
    $nombreUsuario = texto_post('usuario');

    $error = validar_datos_de_cuenta($nombre, $nombreUsuario, (int) $usuario['id']);

    if ($error === null) {
        usuario_actualizar_perfil((int) $usuario['id'], $nombre, $nombreUsuario);
        mensaje_guardar('ok', 'Datos actualizados');
        redirigir('vistas/administrar/usuario.php');
    }
}

// Si hubo un error, se vuelve a mostrar lo que escribió el usuario
$nombreMostrado = es_post() ? texto_post('nombre') : $usuario['nombre'];
$usuarioMostrado = es_post() ? texto_post('usuario') : $usuario['usuario'];

vista('partes/head', ['titulo' => 'Mi cuenta', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple'); ?>
    <main class="table-container-usuario">
        <h1 class="h1-usuario" align="center">Mi cuenta</h1>
        <?php vista('partes/mensajes', ['error' => $error]); ?>
        <form id="form-cuenta" method="POST"><?= csrf_campo() ?></form>
        <table class="table-usuarios" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Acción</th>
            </tr>
            <tr align="center">
                <td><input type="text" name="nombre" form="form-cuenta" value="<?= e($nombreMostrado) ?>" maxlength="<?= NOMBRE_LARGO_MAXIMO ?>"></td>
                <td><input type="text" name="usuario" form="form-cuenta" value="<?= e($usuarioMostrado) ?>" maxlength="<?= USUARIO_LARGO_MAXIMO ?>"></td>
                <td><a class="cambiar-contraseña" href="<?= e(url('vistas/administrar/cambiar-clave.php')) ?>">Cambiar</a></td>
                <td>
                    <button type="submit" name="actualizar" form="form-cuenta" class="btn-update-usuario">Actualizar</button>
                </td>
            </tr>
        </table>
    </main>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
