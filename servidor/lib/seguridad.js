/**
 * Cabeceras de seguridad y protección CSRF.
 *
 * CSRF evita que otra página web envíe formularios a MzaBeats en nombre de un
 * usuario logueado. Cada formulario POST lleva un token secreto de la sesión
 * y cualquier POST que no lo traiga se rechaza.
 */
import { randomBytes, timingSafeEqual } from 'node:crypto';
import { ENTORNO } from '../config.js';
import { comoTexto } from './texto.js';

function origenDeUmami() {
  try {
    return new URL(ENTORNO.umamiScript).origin;
  } catch {
    return ''; // ruta del mismo sitio, como /u/script.js
  }
}

const umami = origenDeUmami();
const POLITICA = [
  "default-src 'self'",
  `script-src 'self' ${umami}`.trim(),
  `connect-src 'self' ${umami}`.trim(),
  "img-src 'self' data:",
  "style-src 'self'",
  "style-src-attr 'unsafe-inline'", // el fondo de cada banda va en un atributo style
  "object-src 'none'",
  "base-uri 'self'",
  "form-action 'self'",
  "frame-ancestors 'none'",
].join('; ');

export function cabeceras(_req, res, next) {
  res.set({
    'Content-Security-Policy': POLITICA,
    'X-Content-Type-Options': 'nosniff',
    'X-Frame-Options': 'DENY',
    'Referrer-Policy': 'strict-origin-when-cross-origin',
  });
  next();
}

function tokenCsrf(req) {
  req.session.csrfToken ??= randomBytes(32).toString('hex');
  return req.session.csrfToken;
}

function sonIguales(a, b) {
  const primero = Buffer.from(a);
  const segundo = Buffer.from(b);
  return primero.length === segundo.length && timingSafeEqual(primero, segundo);
}

/**
 * Deja disponible token() para las plantillas y rechaza los POST sin token.
 * La sesión sólo se crea cuando una página con formulario pide el token.
 */
export function csrf(req, res, next) {
  res.locals.csrfToken = () => tokenCsrf(req);

  if (req.method === 'POST') {
    const enviado = comoTexto(req.body?.csrf_token);
    const guardado = req.session.csrfToken ?? '';
    if (guardado === '' || !sonIguales(guardado, enviado)) {
      res
        .status(400)
        .type('text/plain')
        .send('El formulario venció o no es válido. Volvé atrás, recargá la página y probá de nuevo.');
      return;
    }
  }
  next();
}
