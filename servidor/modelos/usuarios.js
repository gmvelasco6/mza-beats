/**
 * Acceso a la tabla `usuarios`.
 */
import { AVATARES, ESTADO_ADMIN, ESTADO_USUARIO, LIMITES } from '../config.js';
import { ejecutar, fila, filas, transaccion } from '../lib/db.js';
import { largo } from '../lib/texto.js';

export function usuarioPorId(id) {
  return fila('SELECT * FROM usuarios WHERE id = ?', [id]);
}

export function usuarioPorNombre(nombreUsuario) {
  return fila('SELECT * FROM usuarios WHERE usuario = ?', [nombreUsuario]);
}

/** Lista para el panel de administración (sin la columna de la clave). */
export function usuariosTodos() {
  return filas('SELECT id, nombre, usuario, estado, cantidad_creaciones, avatar FROM usuarios ORDER BY id ASC');
}

/** ¿Ya hay otra cuenta con ese nombre de usuario? */
async function usuarioExiste(nombreUsuario, exceptoId) {
  return (await fila('SELECT id FROM usuarios WHERE usuario = ? AND id <> ?', [nombreUsuario, exceptoId])) !== null;
}

/** Las cuentas nuevas empiezan como usuario común y sin creaciones disponibles. */
export function usuarioCrear(nombre, nombreUsuario, claveHash, conexion) {
  return ejecutar(
    'INSERT INTO usuarios (nombre, usuario, clave, estado, cantidad_creaciones) VALUES (?, ?, ?, ?, 0)',
    [nombre, nombreUsuario, claveHash, ESTADO_USUARIO],
    conexion,
  );
}

export function usuarioActualizarPerfil(id, nombre, nombreUsuario) {
  return ejecutar('UPDATE usuarios SET nombre = ?, usuario = ? WHERE id = ?', [nombre, nombreUsuario, id]);
}

export function usuarioActualizarClave(id, claveHash) {
  return ejecutar('UPDATE usuarios SET clave = ? WHERE id = ?', [claveHash, id]);
}

/** La clave de uno de los dibujos, o null para volver a la imagen por defecto. */
export function usuarioActualizarAvatar(id, avatar) {
  return ejecutar('UPDATE usuarios SET avatar = ? WHERE id = ?', [avatar, id]);
}

/** Sólo se aceptan los dibujos del sitio. Vacío significa "sin dibujo". */
export function avatarEsValido(avatar) {
  return avatar === '' || AVATARES.some(({ clave }) => clave === avatar);
}

/** La imagen de perfil de un usuario. */
export function rutaAvatar(usuario) {
  return avatarEsValido(usuario?.avatar ?? '') && usuario?.avatar
    ? `/images/avatares/${usuario.avatar}.svg`
    : '/images/inicio/usuario.png';
}

export function usuarioActualizarPermisos(id, estado, cantidadCreaciones) {
  return ejecutar('UPDATE usuarios SET estado = ?, cantidad_creaciones = ? WHERE id = ?', [
    estado,
    cantidadCreaciones,
    id,
  ]);
}

/** Resta una creación disponible. Devuelve false si ya no le quedaba ninguna. */
export async function usuarioDescontarCreacion(id, conexion) {
  const resultado = await ejecutar(
    'UPDATE usuarios SET cantidad_creaciones = cantidad_creaciones - 1 WHERE id = ? AND cantidad_creaciones > 0',
    [id],
    conexion,
  );
  return resultado.affectedRows === 1;
}

/**
 * Borra la cuenta y todo lo que se guardaba de ella: sus aceptaciones y sus
 * intentos de login. Las sesiones que tenga abiertas dejan de valer solas
 * (lib/sesion.js no encuentra la cuenta y las cierra).
 */
export function usuarioEliminar(id) {
  return transaccion(async (conexion) => {
    const cuenta = await fila('SELECT usuario FROM usuarios WHERE id = ?', [id], conexion);
    await ejecutar('DELETE FROM aceptaciones WHERE usuario_id = ?', [id], conexion);
    if (cuenta) {
      await ejecutar('DELETE FROM intentos_login WHERE usuario = ?', [cuenta.usuario], conexion);
    }
    return ejecutar('DELETE FROM usuarios WHERE id = ?', [id], conexion);
  });
}

/** ¿Es el único administrador? Entonces no puede borrar su cuenta: el panel quedaría sin dueño. */
export async function esElUnicoAdmin(id) {
  const otros = await fila('SELECT COUNT(*) AS total FROM usuarios WHERE estado = ? AND id <> ?', [ESTADO_ADMIN, id]);
  return otros.total === 0;
}

/** Reglas de nombre y usuario que no necesitan consultar la base. */
export function validarFormatoDeCuenta(nombre, nombreUsuario) {
  if (nombre === '' || nombreUsuario === '') {
    return 'Hay campos vacíos';
  }
  if (largo(nombre) > LIMITES.nombreMaximo) {
    return 'El nombre es demasiado largo';
  }
  if (largo(nombreUsuario) < LIMITES.usuarioMinimo || largo(nombreUsuario) > LIMITES.usuarioMaximo) {
    return `El usuario tiene que tener entre ${LIMITES.usuarioMinimo} y ${LIMITES.usuarioMaximo} caracteres`;
  }
  if (!/^[A-Za-z0-9_.-]+$/.test(nombreUsuario)) {
    return 'El usuario sólo puede tener letras, números, punto, guion y guion bajo';
  }
  return null;
}

/** Valida nombre y usuario de una cuenta. Devuelve el primer error encontrado, o null. */
export async function validarDatosDeCuenta(nombre, nombreUsuario, exceptoId = 0) {
  const error = validarFormatoDeCuenta(nombre, nombreUsuario);
  if (error) {
    return error;
  }
  if (await usuarioExiste(nombreUsuario, exceptoId)) {
    return 'Usuario existente, elige otro';
  }
  return null;
}
