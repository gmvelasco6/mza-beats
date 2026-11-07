/**
 * Acceso a la tabla `consultas` (pedidos de bandas para aparecer en el sitio).
 */
import { LIMITES } from '../config.js';
import { ejecutar, filas } from '../lib/db.js';
import { emailEsValido, largo } from '../lib/texto.js';

export function consultaCrear(nombre, razon, email) {
  return ejecutar('INSERT INTO consultas (nombre, razon, email) VALUES (?, ?, ?)', [nombre, razon, email]);
}

export function consultasTodas() {
  return filas('SELECT * FROM consultas ORDER BY id ASC');
}

export function consultaEliminar(id) {
  return ejecutar('DELETE FROM consultas WHERE id = ?', [id]);
}

/** Valida una consulta. Devuelve el primer error encontrado, o null. */
export function validarConsulta(nombre, razon, email) {
  if (nombre === '' || razon === '' || email === '') {
    return 'Hay campos vacíos';
  }
  if (largo(nombre) > LIMITES.bandaNombreMaximo) {
    return 'El nombre es demasiado largo';
  }
  if (largo(razon) > LIMITES.consultaRazonMaximo) {
    return `La razón es demasiado larga (máximo ${LIMITES.consultaRazonMaximo} caracteres)`;
  }
  if (!emailEsValido(email)) {
    return 'El email no es válido';
  }
  return null;
}
