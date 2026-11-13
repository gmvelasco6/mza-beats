/**
 * Genera una copia WebP de cada PNG o JPG de publico/images, al lado del
 * original (rock/02.png → rock/02.webp).
 *
 * El servidor entrega la WebP a los navegadores que la aceptan y el original
 * al resto (servidor/lib/estaticos.js), así que la base sigue guardando las
 * rutas .png de siempre. Las más anchas que ANCHO_MAXIMO se achican: ninguna
 * pantalla las muestra más grandes.
 *
 * Correrlo después de agregar o cambiar una imagen:
 *   npm run imagenes
 * Sólo procesa las que no tienen copia o cuyo original es más nuevo.
 */
import fs from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import sharp from 'sharp';

const RAIZ = path.join(path.dirname(fileURLToPath(import.meta.url)), '..', 'publico', 'images');
const ANCHO_MAXIMO = 1600;
const CALIDAD = 80;

async function archivos(carpeta) {
  const entradas = await fs.readdir(carpeta, { withFileTypes: true });
  const listas = await Promise.all(
    entradas.map((e) => (e.isDirectory() ? archivos(path.join(carpeta, e.name)) : [path.join(carpeta, e.name)])),
  );
  return listas.flat();
}

async function fechaDe(ruta) {
  try {
    return (await fs.stat(ruta)).mtimeMs;
  } catch {
    return 0;
  }
}

let antes = 0;
let despues = 0;
let hechas = 0;
for (const original of (await archivos(RAIZ)).filter((r) => /\.(png|jpe?g)$/i.test(r))) {
  const copia = original.replace(/\.(png|jpe?g)$/i, '.webp');
  if ((await fechaDe(copia)) >= (await fechaDe(original))) {
    continue;
  }
  await sharp(original)
    .resize({ width: ANCHO_MAXIMO, withoutEnlargement: true })
    .webp({ quality: CALIDAD, alphaQuality: 90, effort: 6 })
    .toFile(copia);
  const [a, d] = await Promise.all([fs.stat(original), fs.stat(copia)]);
  antes += a.size;
  despues += d.size;
  hechas += 1;
  console.log(`${path.relative(RAIZ, original)}: ${Math.round(a.size / 1024)} KB → ${Math.round(d.size / 1024)} KB`);
}
console.log(
  hechas === 0
    ? 'Todas las imágenes ya tenían su WebP.'
    : `${hechas} imágenes: ${(antes / 1048576).toFixed(1)} MB → ${(despues / 1048576).toFixed(1)} MB`,
);
