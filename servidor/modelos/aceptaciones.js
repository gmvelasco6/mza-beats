/**
 * Acceso a la tabla `aceptaciones`: qué versión de los términos y de la
 * política de privacidad aceptó cada cuenta, y cuándo.
 *
 * Sólo se agregan filas. Si los documentos cambian, se sube
 * VERSION_LEGALES en config.js y las aceptaciones viejas quedan como prueba
 * de lo que valía antes.
 */
import { VERSION_LEGALES } from '../config.js';
import { ejecutar, filas } from '../lib/db.js';

export function aceptacionRegistrar(usuarioId, conexion) {
  return ejecutar(
    'INSERT INTO aceptaciones (usuario_id, version) VALUES (?, ?)',
    [usuarioId, VERSION_LEGALES],
    conexion,
  );
}

export function aceptacionesDe(usuarioId) {
  return filas('SELECT version, creado_en FROM aceptaciones WHERE usuario_id = ? ORDER BY id ASC', [usuarioId]);
}
