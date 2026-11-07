/**
 * Arma la aplicación: qué hace cada pedido, en orden, antes de llegar a una ruta.
 */
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import compression from 'compression';
import express from 'express';
import { ENTORNO, GENEROS } from './config.js';
import { fila } from './lib/db.js';
import { mensajes } from './lib/mensajes.js';
import { cabeceras, csrf } from './lib/seguridad.js';
import { cargarUsuario, crearSesiones } from './lib/sesion.js';
import { rutasAdmin } from './rutas/admin.js';
import { rutasBandas } from './rutas/bandas.js';
import { rutasCuenta } from './rutas/cuenta.js';
import { rutasSitio } from './rutas/sitio.js';
import { rutasViejas } from './rutas/viejas.js';
import { rutaAvatar } from './modelos/usuarios.js';

const CARPETA = path.dirname(fileURLToPath(import.meta.url));
const RAIZ = path.dirname(CARPETA);

export function crearApp() {
  const app = express();

  app.disable('x-powered-by');
  app.set('view engine', 'ejs');
  app.set('views', path.join(CARPETA, 'vistas'));
  // El sitio llega por un proxy de la red interna (Docker, Tailscale Funnel):
  // la IP real y si es HTTPS los informa ese proxy
  app.set('trust proxy', 'loopback, linklocal, uniquelocal');

  app.locals.generos = GENEROS;
  app.locals.rutaAvatar = rutaAvatar;
  app.locals.umamiId = ENTORNO.umamiId;
  app.locals.umamiScript = ENTORNO.umamiScript;

  app.use(compression());
  app.use(cabeceras);

  // Lo público no necesita sesión ni base de datos
  const estaticos = { maxAge: '1h', index: false };
  app.use(express.static(path.join(RAIZ, 'publico'), estaticos));
  app.use('/vendor/gsap', express.static(path.join(RAIZ, 'node_modules/gsap/dist'), estaticos));

  app.get('/salud', async (_req, res) => {
    await fila('SELECT 1 AS ok');
    res.type('text/plain').send('ok');
  });

  app.use(express.urlencoded({ extended: false, limit: '100kb' }));
  app.use(crearSesiones());
  app.use(cargarUsuario);
  app.use(csrf);
  app.use(mensajes);

  app.use(rutasViejas);
  app.use(rutasSitio);
  app.use(rutasCuenta);
  app.use(rutasBandas);
  app.use(rutasAdmin);

  app.use((_req, res) => {
    res.status(404).render('error', {
      titulo: 'Página no encontrada',
      hojaDeEstilo: 'php-style',
      mensaje: 'No encontramos esa página.',
    });
  });

  // Nunca mostramos errores internos al visitante: van al registro del servidor
  // eslint-disable-next-line no-unused-vars
  app.use((error, _req, res, _next) => {
    console.error(error);
    if (res.headersSent) {
      res.end();
      return;
    }
    const estado = error.status >= 400 && error.status < 500 ? error.status : 500;
    res.status(estado).render('error', {
      titulo: 'Error',
      hojaDeEstilo: 'php-style',
      mensaje: ENTORNO.depuracion ? error.stack : 'Ocurrió un error inesperado. Probá de nuevo en unos minutos.',
    });
  });

  return app;
}
