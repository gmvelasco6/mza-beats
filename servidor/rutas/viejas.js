/**
 * Direcciones de la versión anterior (en PHP): redirigen a las nuevas, para
 * que no se rompan los enlaces guardados ni los buscadores.
 */
import { Router } from 'express';
import { GENEROS } from '../config.js';

export const rutasViejas = Router();

const EQUIVALENCIAS = {
  '/index.php': '/',
  '/vistas/formulario.php': '/formulario',
  '/vistas/agregar_banda.php': '/agregar-banda',
  '/vistas/login-signin/login.php': '/login',
  '/vistas/login-signin/register.php': '/registro',
  '/vistas/administrar/usuario.php': '/cuenta',
  '/vistas/administrar/cambiar-clave.php': '/cuenta/clave',
  '/vistas/administrar/administrar.php': '/admin',
  ...Object.fromEntries(Object.keys(GENEROS).map((clave) => [`/generos/${clave}.php`, `/generos/${clave}`])),
};

for (const [vieja, nueva] of Object.entries(EQUIVALENCIAS)) {
  rutasViejas.get(vieja, (req, res) => {
    const consulta = new URL(req.originalUrl, 'http://local').search;
    res.redirect(301, nueva + consulta);
  });
}

/** Cerrar sesión ahora se hace con un formulario: un enlace no alcanza. */
rutasViejas.get('/sesion_control/control_close_sesion.php', (_req, res) => res.redirect('/'));
