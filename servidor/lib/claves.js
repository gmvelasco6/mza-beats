/**
 * Contraseñas: generar, comprobar y actualizar hashes.
 *
 * Los hashes guardados vienen de PHP (bcrypt con prefijo $2y$), y algunas
 * cuentas muy viejas todavía tienen MD5. Las dos formas se pueden comprobar;
 * cuando una cuenta con MD5 entra bien, su clave se vuelve a guardar con bcrypt.
 */
import { createHash, timingSafeEqual } from 'node:crypto';
import bcrypt from 'bcryptjs';
import { LIMITES } from '../config.js';
import { largo } from './texto.js';

const COSTO = 10;

/**
 * Bcrypt genera el prefijo $2b$, equivalente a $2y$. Se guarda $2y$ para que
 * los hashes sigan siendo iguales a los que ya están en la base.
 */
export async function claveHash(clave) {
  const hash = await bcrypt.hash(clave, COSTO);
  return hash.replace(/^\$2[ab]\$/, '$2y$');
}

export function esMd5Vieja(hash) {
  return /^[a-f0-9]{32}$/.test(hash);
}

export async function claveEsCorrecta(clave, hash) {
  if (esMd5Vieja(hash)) {
    const calculado = createHash('md5').update(clave).digest();
    return timingSafeEqual(calculado, Buffer.from(hash, 'hex'));
  }
  return bcrypt.compare(clave, hash);
}

export function necesitaActualizarse(hash) {
  return esMd5Vieja(hash) || bcrypt.getRounds(hash) !== COSTO;
}

/** Devuelve un mensaje de error si la clave nueva no cumple las reglas, o null si está bien. */
export function validarClaveNueva(clave, confirmacion) {
  if (largo(clave) < LIMITES.claveMinimo) {
    return `La contraseña tiene que tener al menos ${LIMITES.claveMinimo} caracteres`;
  }
  if (clave !== confirmacion) {
    return 'Las contraseñas no coinciden';
  }
  return null;
}
