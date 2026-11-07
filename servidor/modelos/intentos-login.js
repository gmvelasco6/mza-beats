/**
 * Acceso a la tabla `intentos_login`: registra los logins fallidos para
 * bloquear temporalmente a quien prueba contraseñas sin parar.
 */
import { BLOQUEO_LOGIN } from '../config.js';
import { ejecutar, fila } from '../lib/db.js';

const RECIENTES = `creado_en > NOW() - INTERVAL ${Number(BLOQUEO_LOGIN.minutos)} MINUTE`;

/** ¿Hay demasiados intentos fallidos recientes para este usuario o esta IP? */
export async function loginBloqueado(nombreUsuario, ip) {
  const porUsuario = await fila(
    `SELECT COUNT(*) AS total FROM intentos_login WHERE usuario = ? AND ${RECIENTES}`,
    [nombreUsuario],
  );
  const porIp = await fila(`SELECT COUNT(*) AS total FROM intentos_login WHERE ip = ? AND ${RECIENTES}`, [ip]);

  return porUsuario.total >= BLOQUEO_LOGIN.maximoPorUsuario || porIp.total >= BLOQUEO_LOGIN.maximoPorIp;
}

export async function loginRegistrarFallo(nombreUsuario, ip) {
  await ejecutar('INSERT INTO intentos_login (usuario, ip) VALUES (?, ?)', [nombreUsuario, ip]);
  // Los registros viejos ya no sirven para nada
  await ejecutar('DELETE FROM intentos_login WHERE creado_en < NOW() - INTERVAL 1 DAY');
}

export function loginLimpiarFallos(nombreUsuario) {
  return ejecutar('DELETE FROM intentos_login WHERE usuario = ?', [nombreUsuario]);
}
