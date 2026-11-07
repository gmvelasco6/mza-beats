/**
 * Cuentas: inicio y cierre de sesión, registro, "Mi cuenta" y cambio de clave.
 * Siempre se edita la cuenta de la sesión, nunca un id que venga del formulario.
 */
import { Router } from 'express';
import { BLOQUEO_LOGIN, LIMITES } from '../config.js';
import { campo, comoTexto } from '../lib/texto.js';
import { mensajeGuardar } from '../lib/mensajes.js';
import { claveEsCorrecta, claveHash, validarClaveNueva } from '../lib/claves.js';
import { cerrarSesion, intentarLogin, regenerarSesion, requerirLogin } from '../lib/sesion.js';
import { loginBloqueado, loginLimpiarFallos, loginRegistrarFallo } from '../modelos/intentos-login.js';
import {
  usuarioActualizarClave,
  usuarioActualizarPerfil,
  usuarioCrear,
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
  res.render('login', { titulo: 'Inicio de Sesión', hojaDeEstilo: 'php-style', error, valorUsuario: usuario });
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
  res.render('registro', { titulo: 'Registro', hojaDeEstilo: 'php-style', error, valores, limites: LIMITES });
}

rutasCuenta.get('/registro', soloVisitantes, (_req, res) => mostrarRegistro(res));

rutasCuenta.post('/registro', soloVisitantes, async (req, res) => {
  const valores = { nombre: campo(req.body, 'name'), usuario: campo(req.body, 'user') };
  const clave = comoTexto(req.body?.password);
  const confirmacion = comoTexto(req.body?.['password-confirm']);

  const error = (await validarDatosDeCuenta(valores.nombre, valores.usuario)) ?? validarClaveNueva(clave, confirmacion);
  if (error) {
    mostrarRegistro(res, error, valores);
    return;
  }
  await usuarioCrear(valores.nombre, valores.usuario, await claveHash(clave));
  mensajeGuardar(req, 'ok', 'Cuenta creada. Ya podés iniciar sesión.');
  res.redirect('/login');
});

/* ---------- Mi cuenta ---------- */

function mostrarCuenta(res, error, nombre, usuario) {
  res.render('cuenta', {
    titulo: 'Mi cuenta',
    hojaDeEstilo: 'php-style',
    error,
    nombreMostrado: nombre,
    usuarioMostrado: usuario,
    limites: LIMITES,
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

/* ---------- Cambiar clave ---------- */

function mostrarCambioDeClave(res, error = null) {
  res.render('cambiar-clave', { titulo: 'Cambiar Clave', hojaDeEstilo: 'php-style', error, limites: LIMITES });
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

  await usuarioActualizarClave(req.usuario.id, await claveHash(nueva));
  const { id } = req.usuario;
  await regenerarSesion(req);
  req.session.usuarioId = id;
  mensajeGuardar(req, 'ok', 'Contraseña actualizada');
  res.redirect('/cuenta');
});
