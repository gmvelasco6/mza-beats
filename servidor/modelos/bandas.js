/**
 * Acceso a la tabla `bandas`.
 */
import { GENEROS, LIMITES } from '../config.js';
import { ejecutar, filas } from '../lib/db.js';
import { escaparLike, largo } from '../lib/texto.js';

export function generoEsValido(genero) {
  return Object.hasOwn(GENEROS, genero);
}

/** Bandas de un género, opcionalmente filtradas por nombre. */
export function bandasPorGenero(genero, busqueda = '') {
  if (busqueda === '') {
    return filas('SELECT * FROM bandas WHERE genero = ? ORDER BY id ASC', [genero]);
  }
  return filas('SELECT * FROM bandas WHERE genero = ? AND nombre LIKE ? ORDER BY id ASC', [
    genero,
    `%${escaparLike(busqueda)}%`,
  ]);
}

/** Las bandas nuevas se crean sin imágenes; las agrega un administrador después. */
export function bandaCrear(nombre, descripcion, genero, conexion) {
  return ejecutar(
    "INSERT INTO bandas (nombre, descripcion, imagen_principal, imagen_fondo, genero) VALUES (?, ?, '', '', ?)",
    [nombre, descripcion, genero],
    conexion,
  );
}

export function bandaActualizar(id, { nombre, descripcion, imagenPrincipal, imagenFondo, genero }) {
  return ejecutar(
    'UPDATE bandas SET nombre = ?, descripcion = ?, imagen_principal = ?, imagen_fondo = ?, genero = ? WHERE id = ?',
    [nombre, descripcion, imagenPrincipal, imagenFondo, genero, id],
  );
}

export function bandaEliminar(id) {
  return ejecutar('DELETE FROM bandas WHERE id = ?', [id]);
}

/**
 * Las rutas de imagen tienen que apuntar a un archivo dentro de images/
 * (por ejemplo images/rock/02.png). Así nadie puede meter una URL externa
 * o código en el atributo style de la página.
 */
export function rutaImagenEsValida(ruta) {
  return (
    ruta === '' ||
    (/^images\/[A-Za-z0-9_\-/]+\.(png|jpe?g|webp|gif)$/.test(ruta) && !ruta.includes('..'))
  );
}

/** Valida los datos de una banda. Devuelve el primer error encontrado, o null. */
export function validarBanda(nombre, descripcion, genero) {
  if (nombre === '' || descripcion === '') {
    return 'Hay campos vacíos';
  }
  if (largo(nombre) > LIMITES.bandaNombreMaximo) {
    return 'El nombre es demasiado largo';
  }
  if (largo(descripcion) > LIMITES.textoMaximo) {
    return 'La descripción es demasiado larga';
  }
  if (!generoEsValido(genero)) {
    return 'Elegí un género válido';
  }
  return null;
}
