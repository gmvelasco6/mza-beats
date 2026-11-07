/**
 * Sesión, login y permisos.
 *
 * En la sesión sólo se guarda el id del usuario. Sus datos (nombre, estado,
 * cantidad de creaciones) se leen de la base en cada pedido, así un cambio
 * hecho desde el panel de administración se aplica al instante.
 *
 * Las sesiones se guardan en la tabla `sesiones`, que se crea sola, para que
 * nadie pierda la suya cuando se reinicia el servidor.
 */
import session from 'express-session';
import crearAlmacen from 'express-mysql-session';
import { ENTORNO, ESTADO_ADMIN, ESTADO_SIN_PERMISOS, ESTADO_USUARIO } from '../config.js';
import { grupo } from './db.js';
import { claveEsCorrecta, claveHash, necesitaActualizarse } from './claves.js';
import { usuarioActualizarClave, usuarioPorId, usuarioPorNombre } from '../modelos/usuarios.js';

const UN_DIA = 24 * 60 * 60 * 1000;

export function crearSesiones() {
  if (ENTORNO.secretoSesion.length < 32) {
    throw new Error('Falta SESSION_SECRET: tiene que tener al menos 32 caracteres');
  }
  const Almacen = crearAlmacen(session);
  return session({
    name: 'mzabeats_sesion',
    secret: ENTORNO.secretoSesion,
    resave: false,
    saveUninitialized: false,
    store: new Almacen(
      { createDatabaseTable: true, expiration: UN_DIA, schema: { tableName: 'sesiones' } },
      grupo,
    ),
    cookie: {
      httpOnly: true, // JavaScript no puede leer la cookie
      sameSite: 'lax', // otros sitios no pueden mandarla en un POST
      secure: 'auto', // sólo por HTTPS cuando el pedido llega por HTTPS
    },
  });
}

export const esAdmin = (usuario) => usuario?.estado === ESTADO_ADMIN;

/** Puede cargar bandas si tiene permisos y le quedan creaciones disponibles. */
export const puedeAgregarBandas = (usuario) =>
  Boolean(usuario) && usuario.estado >= ESTADO_USUARIO && usuario.cantidad_creaciones >= 1;

/** El botón "¡Quiero aparecer!" se muestra a visitantes y a cuentas sin permisos. */
export const mostrarBotonParticipar = (usuario) =>
  !usuario || usuario.estado === ESTADO_SIN_PERMISOS;

/** Carga el usuario de la sesión y lo deja disponible para las rutas y las plantillas. */
export async function cargarUsuario(req, res, next) {
  let usuario = null;
  if (req.session.usuarioId) {
    usuario = await usuarioPorId(req.session.usuarioId);
    if (!usuario) {
      delete req.session.usuarioId; // la cuenta fue eliminada mientras estaba logueado
    }
  }
  req.usuario = usuario;
  res.locals.usuario = usuario;
  res.locals.esAdmin = esAdmin(usuario);
  res.locals.puedeAgregar = puedeAgregarBandas(usuario);
  res.locals.mostrarParticipar = mostrarBotonParticipar(usuario);
  next();
}

export function requerirLogin(req, res, next) {
  if (!req.usuario) {
    res.redirect('/');
    return;
  }
  next();
}

export function requerirAdmin(req, res, next) {
  if (!esAdmin(req.usuario)) {
    res.redirect('/');
    return;
  }
  next();
}

/** Cambia el id de sesión para que uno robado antes del login no sirva después. */
export function regenerarSesion(req) {
  return new Promise((resolver, rechazar) => {
    req.session.regenerate((error) => (error ? rechazar(error) : resolver()));
  });
}

/**
 * Valida usuario y contraseña. Si son correctos, inicia la sesión.
 * Se calcula un hash aunque el usuario no exista, para que el tiempo de
 * respuesta no revele si existe o no.
 */
export async function intentarLogin(req, nombreUsuario, clave) {
  const usuario = await usuarioPorNombre(nombreUsuario);
  if (!usuario) {
    await claveHash(clave);
    return false;
  }
  if (!(await claveEsCorrecta(clave, usuario.clave))) {
    return false;
  }
  if (necesitaActualizarse(usuario.clave)) {
    await usuarioActualizarClave(usuario.id, await claveHash(clave));
  }
  await regenerarSesion(req);
  req.session.usuarioId = usuario.id;
  return true;
}

export function cerrarSesion(req, res) {
  return new Promise((resolver, rechazar) => {
    req.session.destroy((error) => {
      if (error) {
        rechazar(error);
        return;
      }
      res.clearCookie('mzabeats_sesion');
      resolver();
    });
  });
}
