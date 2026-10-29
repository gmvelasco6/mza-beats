<?php
/**
 * Panel de administración: usuarios, bandas por género y consultas.
 *
 * Cada botón manda un campo "accion" que dice exactamente qué hacer
 * (antes un mismo "Eliminar" borraba la banda, el usuario y la consulta
 * que tuvieran ese id).
 */
require __DIR__ . '/../../../backend/bootstrap.php';

requerir_admin();

if (es_post()) {
    $accion = texto_post('accion');
    $id = entero_post('id') ?? 0;
    $miId = (int) usuario_actual()['id'];
    $volverA = 'vistas/administrar/administrar.php';

    switch ($accion) {
        case 'actualizar-banda':
            $nombre = texto_post('nombre');
            $descripcion = texto_post('descripcion');
            $genero = texto_post('genero');
            $imagenPrincipal = texto_post('imagen_principal');
            $imagenFondo = texto_post('imagen_fondo');

            $error = validar_banda($nombre, $descripcion, $genero);
            if ($error === null && (!ruta_imagen_es_valida($imagenPrincipal) || !ruta_imagen_es_valida($imagenFondo))) {
                $error = 'Las imágenes tienen que ser rutas como images/rock/02.png';
            }

            if ($error === null) {
                banda_actualizar($id, $nombre, $descripcion, $imagenPrincipal, $imagenFondo, $genero);
                mensaje_guardar('ok', "Banda \"$nombre\" actualizada");
            } else {
                mensaje_guardar('error', $error);
            }
            $volverA .= '#tabla-' . (genero_es_valido($genero) ? $genero : 'usuarios');
            break;

        case 'eliminar-banda':
            banda_eliminar($id);
            mensaje_guardar('ok', 'Banda eliminada');
            break;

        case 'actualizar-usuario':
            $estado = entero_post('estado');
            $creaciones = entero_post('cantidad_creaciones');

            if ($estado === null || !array_key_exists($estado, ESTADOS)) {
                mensaje_guardar('error', 'Estado inválido. Usá ' . implode(', ', array_map(
                    fn ($valor, $texto) => "$valor ($texto)",
                    array_keys(ESTADOS),
                    ESTADOS
                )));
            } elseif ($creaciones === null || $creaciones < 0) {
                mensaje_guardar('error', 'La cantidad de creaciones tiene que ser un número mayor o igual a 0');
            } elseif ($id === $miId && $estado !== ESTADO_ADMIN) {
                mensaje_guardar('error', 'No podés quitarte el rol de administrador a vos mismo');
            } else {
                usuario_actualizar_permisos($id, $estado, $creaciones);
                mensaje_guardar('ok', 'Usuario actualizado');
            }
            $volverA .= '#tabla-usuarios';
            break;

        case 'eliminar-usuario':
            if ($id === $miId) {
                mensaje_guardar('error', 'No podés eliminar tu propia cuenta desde el panel');
            } else {
                usuario_eliminar($id);
                mensaje_guardar('ok', 'Usuario eliminado');
            }
            $volverA .= '#tabla-usuarios';
            break;

        case 'eliminar-consulta':
            consulta_eliminar($id);
            mensaje_guardar('ok', 'Consulta eliminada');
            $volverA .= '#tabla-consultas';
            break;
    }

    // Después de guardar se recarga la página (así F5 no repite la acción)
    redirigir($volverA);
}

$usuarios = usuarios_todos();
$bandasPorGenero = [];
foreach (GENEROS as $clave => $genero) {
    $bandasPorGenero[$clave] = bandas_por_genero($clave);
}
$consultas = consultas_todas();

vista('partes/head', ['titulo' => 'Administrar Bandas', 'hojaDeEstilo' => 'css/php-style.css']);
?>
<body>
    <?php vista('partes/nav_simple', ['textoVolver' => 'Volver a Inicio', 'claseNav' => 'nav-admin']); ?>
    <div class="intro">
        <ul class="admin-nav-list">
            <li><a href="#tabla-usuarios">Usuarios</a></li>
            <?php foreach (GENEROS as $clave => $genero): ?>
                <li><a href="#tabla-<?= e($clave) ?>"><?= e($genero['nombre']) ?></a></li>
            <?php endforeach; ?>
            <li><a href="#tabla-consultas">Consultas</a></li>
        </ul>
    </div>
    <main class="table-container">
        <?php vista('partes/mensajes'); ?>

        <h1 id="tabla-usuarios" align="center">Usuarios</h1>
        <p align="center">Estados: <?php foreach (ESTADOS as $valor => $texto): ?><?= e("$valor = $texto") ?>&nbsp;&nbsp; <?php endforeach; ?></p>
        <table class="table" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Cantidad Creaciones</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($usuarios as $fila): ?>
                <?php $formulario = 'form-usuario-' . (int) $fila['id']; ?>
                <tr align="center">
                    <td><input type="text" value="<?= e($fila['nombre']) ?>" readonly></td>
                    <td><input type="text" value="<?= e($fila['usuario']) ?>" readonly></td>
                    <td><input type="text" name="estado" form="<?= $formulario ?>" value="<?= e($fila['estado']) ?>"></td>
                    <td><input type="text" name="cantidad_creaciones" form="<?= $formulario ?>" value="<?= e($fila['cantidad_creaciones']) ?>"></td>
                    <td>
                        <form id="<?= $formulario ?>" method="POST">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= (int) $fila['id'] ?>">
                            <button type="submit" name="accion" value="actualizar-usuario" class="btn-update">Actualizar</button>
                            <button type="submit" name="accion" value="eliminar-usuario" class="btn-delete"
                                onclick="return confirm(<?= e(js('¿Eliminar al usuario ' . $fila['usuario'] . '?')) ?>);">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php foreach (GENEROS as $clave => $genero): ?>
            <h1 id="tabla-<?= e($clave) ?>" align="center">Bandas <?= e($genero['nombre']) ?></h1>
            <table class="table" cellpadding="5">
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Imagen Principal</th>
                    <th>Imagen Fondo</th>
                    <th>Genero</th>
                    <th>Acción</th>
                </tr>
                <?php foreach ($bandasPorGenero[$clave] as $fila): ?>
                    <?php $formulario = 'form-banda-' . (int) $fila['id']; ?>
                    <tr align="center">
                        <td><input type="text" name="nombre" form="<?= $formulario ?>" value="<?= e($fila['nombre']) ?>"></td>
                        <td><textarea name="descripcion" form="<?= $formulario ?>" class="descripcion"><?= e($fila['descripcion']) ?></textarea></td>
                        <td><input type="text" name="imagen_principal" form="<?= $formulario ?>" value="<?= e($fila['imagen_principal']) ?>"></td>
                        <td><input type="text" name="imagen_fondo" form="<?= $formulario ?>" value="<?= e($fila['imagen_fondo']) ?>"></td>
                        <td><input type="text" name="genero" form="<?= $formulario ?>" value="<?= e($fila['genero']) ?>"></td>
                        <td>
                            <form id="<?= $formulario ?>" method="POST">
                                <?= csrf_campo() ?>
                                <input type="hidden" name="id" value="<?= (int) $fila['id'] ?>">
                                <button type="submit" name="accion" value="actualizar-banda" class="btn-update">Actualizar</button>
                                <button type="submit" name="accion" value="eliminar-banda" class="btn-delete"
                                    onclick="return confirm(<?= e(js('¿Eliminar la banda ' . $fila['nombre'] . '?')) ?>);">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>

        <h1 id="tabla-consultas" align="center">Consultas</h1>
        <table class="table" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Razon</th>
                <th>Email</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($consultas as $fila): ?>
                <tr align="center">
                    <td><input type="text" value="<?= e($fila['nombre']) ?>" readonly></td>
                    <td><textarea class="descripcion" readonly><?= e($fila['razon']) ?></textarea></td>
                    <td><input type="text" value="<?= e($fila['email']) ?>" readonly></td>
                    <td>
                        <form method="POST">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= (int) $fila['id'] ?>">
                            <button type="submit" name="accion" value="eliminar-consulta" class="btn-delete"
                                onclick="return confirm('¿Eliminar esta consulta?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <footer>
        <p>&copy;Derechos de autor Reservados</p>
    </footer>
</body>
</html>
