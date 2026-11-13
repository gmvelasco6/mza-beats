/**
 * Cuentas: inicio y cierre de sesión, registro, "Mi cuenta" y cambio de clave.
 * Siempre se edita la cuenta de la sesión, nunca un id que venga del formulario.
 */
import { Router } from 'express';
import { AVATARES, BLOQUEO_LOGIN, ESTADO_ADMIN, ESTADOS, LIMITES, VERSION_LEGALES } from '../config.js';
import { transaccion } from '../lib/db.js';
import { aceptacionesDe, aceptacionRegistrar } from '../modelos/aceptaciones.js';
import { campo, comoTexto } from '../lib/texto.js';
import { mensajeGuardar } from '../lib/mensajes.js';
import { claveEsCorrecta, claveHash, validarClaveNueva } from '../lib/claves.js';
import { limiteRegistro } from '../lib/limite.js';
import { cerrarSesion, iniciarSesionDe, intentarLogin, regenerarSesion, requerirLogin } from '../lib/sesion.js';
import { loginBloqueado, loginLimpiarFallos, loginRegistrarFallo } from '../modelos/intentos-login.js';
import {
  avatarEsValido,
  esElUnicoAdmin,
  usuarioActualizarAvatar,
  usuarioActualizarClave,
  usuarioActualizarPerfil,
  usuarioCrear,
  usuarioEliminar,
  validarDatosDeCuenta,
} from '../modelos/usuarios.js';

export const rutasCuenta = Router();

function soloVisitantes(req, res, next) {
  if (req.usuario) {
    res.redirect('/');
    return;
  }
  next();
}

/* ---------- Iniciar sesión ---------- */

function mostrarLogin(res, error = null, usuario = '') {
  res.render('login', {
    titulo: 'Inicio de Sesión',
    hojaDeEstilo: 'php-style',
    sinIndexar: true,
    error,
    valorUsuario: usuario,
  });
}

rutasCuenta.get('/login', soloVisitantes, (_req, res) => mostrarLogin(res));

rutasCuenta.post('/login', soloVisitantes, async (req, res) => {
  const usuario = campo(req.body, 'user');
  const clave = comoTexto(req.body?.password);

  if (usuario === '' || clave === '') {
    mostrarLogin(res, 'Hay campos vacíos', usuario);
  } else if (await loginBloqueado(usuario, req.ip)) {
    mostrarLogin(res, `Demasiados intentos fallidos. Probá de nuevo en ${BLOQUEO_LOGIN.minutos} minutos.`, usuario);
  } else if (await intentarLogin(req, usuario, clave)) {
    await loginLimpiarFallos(usuario);
    res.redirect('/');
  } else {
    await loginRegistrarFallo(usuario, req.ip);
    mostrarLogin(res, 'Acceso denegado', usuario);
  }
});

/* ---------- Cerrar sesión ---------- */

rutasCuenta.post('/salir', async (req, res) => {
  await cerrarSesion(req, res);
  res.redirect('/login');
});

/* ---------- Registro ---------- */

function mostrarRegistro(res, error = null, valores = {}) {
  res.render('registro', {
    titulo: 'Registro',
    hojaDeEstilo: 'php-style',
    sinIndexar: true,
    error,
    valores,
    limites: LIMITES,
    versionLegales: VERSION_LEGALES,
  });
}

rutasCuenta.get('/registro', soloVisitantes, (_req, res) => mostrarRegistro(res));

rutasCuenta.post('/registro', soloVisitantes, async (req, res) => {
  const valores = { nombre: campo(req.body, 'name'), usuario: campo(req.body, 'user') };
  // Cuenta todos los envíos, también los que fallan: si no, el registro
  // serviría para probar nombres de usuario uno tras otro
  if (limiteRegistro.superado(req.ip)) {
    const minutos = limiteRegistro.minutosDeEspera(req.ip);
    res.status(429);
    mostrarRegistro(res, `Hiciste demasiados intentos. Probá de nuevo en ${minutos} minutos.`, valores);
    return;
  }
  limiteRegistro.registrar(req.ip);
  const clave = comoTexto(req.body?.password);
  const confirmacion = comoTexto(req.body?.['password-confirm']);

  const error =
    (await validarDatosDeCuenta(valores.nombre, valores.usuario)) ??
    validarClaveNueva(clave, confirmacion) ??
    (campo(req.body, 'acepto') === 'si'
      ? null
      : 'Para crear la cuenta tenés que aceptar los términos y la política de privacidad');
  if (error) {
    mostrarRegistro(res, error, valores);
    return;
  }
  const hash = await claveHash(clave);
  // La cuenta y su aceptación se guardan juntas: no queda ninguna sin la otra
  await transaccion(async (conexion) => {
    const { insertId } = await usuarioCrear(valores.nombre, valores.usuario, hash, conexion);
    await aceptacionRegistrar(insertId, conexion);
  });
  mensajeGuardar(req, 'ok', 'Cuenta creada. Ya podés iniciar sesión.');
  res.redirect('/login');
});

/* ---------- Mi cuenta ---------- */

function mostrarCuenta(res, error, nombre, usuario) {
  res.render('cuenta', {
    titulo: 'Mi cuenta',
    hojaDeEstilo: 'panel',
    sinIndexar: true,
    error,
    nombreMostrado: nombre,
    usuarioMostrado: usuario,
    limites: LIMITES,
    avatares: AVATARES,
    rol: ESTADOS.get(res.locals.usuario.estado) ?? 'Usuario',
  });
}

rutasCuenta.get('/cuenta', requerirLogin, (req, res) => {
  mostrarCuenta(res, null, req.usuario.nombre, req.usuario.usuario);
});

rutasCuenta.post('/cuenta', requerirLogin, async (req, res) => {
  const nombre = campo(req.body, 'nombre');
  const usuario = campo(req.body, 'usuario');

  const error = await validarDatosDeCuenta(nombre, usuario, req.usuario.id);
  if (error) {
    mostrarCuenta(res, error, nombre, usuario); // se vuelve a mostrar lo que escribió
    return;
  }
  await usuarioActualizarPerfil(req.usuario.id, nombre, usuario);
  mensajeGuardar(req, 'ok', 'Datos actualizados');
  res.redirect('/cuenta');
});

/* ---------- Foto de perfil ---------- */

rutasCuenta.post('/cuenta/foto', requerirLogin, async (req, res) => {
  const avatar = campo(req.body, 'avatar');
  if (avatarEsValido(avatar)) {
    await usuarioActualizarAvatar(req.usuario.id, avatar === '' ? null : avatar);
    mensajeGuardar(req, 'ok', 'Foto de perfil actualizada');
  } else {
    mensajeGuardar(req, 'error', 'Elegí uno de los dibujos de la lista');
  }
  res.redirect('/cuenta');
});

/* ---------- Mis datos: descargar y eliminar la cuenta ---------- */

/** Todo lo que el sitio guarda de la cuenta, en un archivo que se puede descargar. */
rutasCuenta.get('/cuenta/datos', requerirLogin, async (req, res) => {
  const { usuario } = req;
  const datos = {
    generado_en: new Date().toISOString(),
    sitio: 'MzaBeats',
    cuenta: {
      nombre: usuario.nombre,
      usuario: usuario.usuario,
      rol: ESTADOS.get(usuario.estado) ?? 'Usuario',
      creaciones_disponibles: usuario.cantidad_creaciones,
      foto_de_perfil: usuario.avatar ?? null,
    },
    terminos_aceptados: await aceptacionesDe(usuario.id),
    aclaraciones: [
      'La contraseña no se incluye: se guarda cifrada (bcrypt) y nadie puede leerla.',
      'Las bandas que cargaste no quedan asociadas a tu cuenta, por eso no aparecen acá.',
      'Los intentos fallidos de inicio de sesión (usuario e IP) se borran a las 24 horas.',
    ],
  };
  res
    .type('application/json')
    .set('Cache-Control', 'no-store')
    .attachment(`mzabeats-${usuario.usuario}.json`)
    .send(JSON.stringify(datos, null, 2));
});

rutasCuenta.post('/cuenta/eliminar', requerirLogin, async (req, res) => {
  const clave = comoTexto(req.body?.['clave-actual']);
  if (clave === '' || !(await claveEsCorrecta(clave, req.usuario.clave))) {
    mensajeGuardar(req, 'error', 'Para eliminar la cuenta escribí tu contraseña actual');
    res.redirect('/cuenta#eliminar');
    return;
  }
  if (req.usuario.estado === ESTADO_ADMIN && (await esElUnicoAdmin(req.usuario.id))) {
    mensajeGuardar(req, 'error', 'Sos el único administrador: nombrá a otro antes de eliminar tu cuenta');
    res.redirect('/cuenta#eliminar');
    return;
  }
  await usuarioEliminar(req.usuario.id);
  // Una sesión nueva y vacía, sólo para poder mostrar el aviso
  await regenerarSesion(req);
  mensajeGuardar(req, 'ok', 'Tu cuenta y sus datos fueron eliminados.');
  res.redirect('/login');
});

/* ---------- Cambiar clave ---------- */

function mostrarCambioDeClave(res, error = null) {
  res.render('cambiar-clave', { titulo: 'Cambiar contraseña', hojaDeEstilo: 'panel', sinIndexar: true, error, limites: LIMITES });
}

rutasCuenta.get('/cuenta/clave', requerirLogin, (_req, res) => mostrarCambioDeClave(res));

/** Pide la contraseña actual para que nadie pueda cambiarla desde una sesión abierta ajena. */
rutasCuenta.post('/cuenta/clave', requerirLogin, async (req, res) => {
  const actual = comoTexto(req.body?.['clave-actual']);
  const nueva = comoTexto(req.body?.password);
  const confirmacion = comoTexto(req.body?.['new-password']);

  let error = null;
  if (actual === '' || nueva === '' || confirmacion === '') {
    error = 'Hay campos vacios';
  } else if (!(await claveEsCorrecta(actual, req.usuario.clave))) {
    error = 'La contraseña actual no es correcta';
  } else {
    error = validarClaveNueva(nueva, confirmacion);
  }
  if (error) {
    mostrarCambioDeClave(res, error);
    return;
  }

  const hash = await claveHash(nueva);
  await usuarioActualizarClave(req.usuario.id, hash);
  const { id } = req.usuario;
  // Esta sesión sigue abierta con la clave nueva; las demás se cierran solas
  await regenerarSesion(req);
  iniciarSesionDe(req, id, hash);
  mensajeGuardar(req, 'ok', 'Contraseña actualizada. Se cerraron tus otras sesiones abiertas.');
  res.redirect('/cuenta');
});
