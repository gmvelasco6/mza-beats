/**
 * Lectura de datos de formularios y funciones de texto.
 *
 * Un campo de formulario puede llegar repetido o con forma rara, así que
 * nunca se usa req.body directamente: siempre se pasa por estas funciones.
 */

/** Cantidad de caracteres reales (una letra con acento o un emoji cuentan como uno). */
export function largo(texto) {
  return [...texto].length;
}

/** Devuelve el valor si es texto, y '' si es cualquier otra cosa. */
export function comoTexto(valor) {
  return typeof valor === 'string' ? valor : '';
}

/** Lee un campo de texto, sin espacios al principio ni al final. */
export function campo(datos, nombre) {
  return comoTexto(datos?.[nombre]).trim();
}

/** Lee un número entero. Devuelve null si no es un entero válido. */
export function entero(datos, nombre) {
  const valor = campo(datos, nombre);
  return /^[+-]?(0|[1-9]\d*)$/.test(valor) ? Number(valor) : null;
}

export function emailEsValido(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@.]+$/.test(email);
}

/** Los comodines de LIKE (% y _) que escriba el usuario se buscan como texto. */
export function escaparLike(texto) {
  return texto.replace(/[\\%_]/g, '\\$&');
}
