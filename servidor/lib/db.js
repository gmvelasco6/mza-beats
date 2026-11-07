/**
 * Conexión a la base de datos y funciones para hacer consultas.
 *
 * Todas las consultas usan sentencias preparadas: los valores van aparte del
 * SQL, así que no hay riesgo de inyección SQL.
 *
 *   await filas('SELECT * FROM bandas WHERE genero = ?', [genero]);
 *
 * Cada función acepta una conexión al final, para usarla dentro de una
 * transacción. Si no se pasa ninguna, usa el grupo de conexiones.
 */
import mysql from 'mysql2/promise';
import { ENTORNO } from '../config.js';

export const grupo = mysql.createPool({
  ...ENTORNO.db,
  charset: 'utf8mb4',
  connectionLimit: 10,
  waitForConnections: true,
  dateStrings: true,
});

/** Devuelve todas las filas del resultado. */
export async function filas(sql, parametros = [], conexion = grupo) {
  const [resultado] = await conexion.execute(sql, parametros);
  return resultado;
}

/** Devuelve la primera fila del resultado, o null si no hay ninguna. */
export async function fila(sql, parametros = [], conexion = grupo) {
  const resultado = await filas(sql, parametros, conexion);
  return resultado[0] ?? null;
}

/** Ejecuta un INSERT / UPDATE / DELETE y devuelve { affectedRows, insertId }. */
export async function ejecutar(sql, parametros = [], conexion = grupo) {
  const [resultado] = await conexion.execute(sql, parametros);
  return resultado;
}

/** Ejecuta varias operaciones juntas: o se guardan todas, o ninguna. */
export async function transaccion(operaciones) {
  const conexion = await grupo.getConnection();
  try {
    await conexion.beginTransaction();
    const resultado = await operaciones(conexion);
    await conexion.commit();
    return resultado;
  } catch (error) {
    await conexion.rollback();
    throw error;
  } finally {
    conexion.release();
  }
}
