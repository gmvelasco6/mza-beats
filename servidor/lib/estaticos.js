/**
 * Archivos públicos: imágenes en WebP cuando conviene, y cuánto tiempo los
 * guarda el navegador.
 *
 * WebP: cada PNG o JPG puede tener al lado su copia .webp (la genera
 * `npm run imagenes`). Si el navegador avisa que la entiende y la copia pesa
 * menos, se entrega esa; si no, el original. La dirección no cambia, así que
 * la base, el CSS y las plantillas siguen usando las rutas .png.
 *
 * Caché: el CSS y el JS se piden con ?v=<huella del contenido> (ver
 * recurso()). Esa dirección cambia sola cuando cambia el archivo, así que el
 * navegador puede guardarla un año sin preguntar. Lo que se pide sin huella
 * (las imágenes y fuentes que nombra el CSS) se guarda un mes; el resto, una
 * hora.
 */
import { createHash } from 'node:crypto';
import fs from 'node:fs';
import path from 'node:path';

const UN_ANIO = 'public, max-age=31536000, immutable';
const UN_MES = 'public, max-age=2592000';
const UNA_HORA = 'public, max-age=3600';
const MEDIOS = /\.(png|jpe?g|webp|svg|gif|ico|woff2?|ttf)$/i;
const CON_COPIA = /\.(png|jpe?g)$/i;

function todosLosArchivos(carpeta) {
  return fs.readdirSync(carpeta, { withFileTypes: true }).flatMap((entrada) => {
    const ruta = path.join(carpeta, entrada.name);
    return entrada.isDirectory() ? todosLosArchivos(ruta) : [ruta];
  });
}

/**
 * Las direcciones (como /images/rock/02.png) que tienen una copia WebP más
 * liviana. Se arma una sola vez al arrancar: así ningún pedido toca el disco
 * para decidir, y sólo se pueden elegir archivos que existen.
 */
function copiasMasLivianas(carpetaPublica) {
  const imagenes = path.join(carpetaPublica, 'images');
  const conCopia = new Set();
  for (const original of todosLosArchivos(imagenes).filter((r) => CON_COPIA.test(r))) {
    const copia = original.replace(CON_COPIA, '.webp');
    if (fs.existsSync(copia) && fs.statSync(copia).size < fs.statSync(original).size) {
      conCopia.add(`/${path.relative(carpetaPublica, original).split(path.sep).join('/')}`);
    }
  }
  return conCopia;
}

/** Middleware que cambia el pedido de un PNG o JPG por su WebP cuando conviene. */
export function webpSiConviene(carpetaPublica) {
  const conCopia = copiasMasLivianas(carpetaPublica);
  return (req, res, next) => {
    if ((req.method === 'GET' || req.method === 'HEAD') && CON_COPIA.test(req.path)) {
      // La misma dirección da un archivo u otro según el navegador
      res.vary('Accept');
      if (conCopia.has(req.path) && (req.get('accept') ?? '').includes('image/webp')) {
        req.url = req.url.replace(/\.(png|jpe?g)(?=$|\?)/i, '.webp');
      }
    }
    next();
  };
}

/** Para express.static: cuánto puede guardar el navegador cada archivo. */
export function cacheSegunArchivo(res, ruta) {
  if (res.req.query.v !== undefined) {
    res.set('Cache-Control', UN_ANIO);
  } else if (MEDIOS.test(ruta)) {
    res.set('Cache-Control', UN_MES);
  } else {
    res.set('Cache-Control', UNA_HORA);
  }
}

/**
 * Devuelve la función recurso('/css/style.css') → '/css/style.css?v=3f2a…'
 * para las plantillas. La huella se calcula la primera vez que se pide cada
 * archivo y queda guardada: el contenido no cambia mientras el proceso corre.
 */
export function crearRecurso(carpetas) {
  const huellas = new Map();
  return (direccion) => {
    if (!huellas.has(direccion)) {
      const archivo = carpetas
        .map(([prefijo, carpeta]) =>
          direccion.startsWith(prefijo) ? path.join(carpeta, direccion.slice(prefijo.length)) : null,
        )
        .find((ruta) => ruta && fs.existsSync(ruta));
      const huella = archivo
        ? createHash('sha256').update(fs.readFileSync(archivo)).digest('hex').slice(0, 10)
        : null;
      huellas.set(direccion, huella ? `${direccion}?v=${huella}` : direccion);
    }
    return huellas.get(direccion);
  };
}
