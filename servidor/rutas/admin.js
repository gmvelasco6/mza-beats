/**
 * Panel de administración: usuarios, bandas por género y consultas.
 *
 * Cada botón manda un campo "accion" que dice exactamente qué hacer. Después
 * de guardar se recarga la página, así F5 no repite la acción.
 */
import { Router } from 'express';
import { ESTADO_ADMIN, ESTADOS, GENEROS } from '../config.js';
import { mensajeGuardar } from '../lib/mensajes.js';
import { requerirAdmin } from '../lib/sesion.js';
import { campo, entero } from '../lib/texto.js';
import {
  bandaActualizar,
  bandaEliminar,
  bandasPorGenero,
  generoEsValido,
  rutaImagenEsValida,
  validarBanda,
} from '../modelos/bandas.js';
import { consultaEliminar, consultasTodas } from '../modelos/consultas.js';
import { usuarioActualizarPermisos, usuarioEliminar, usuariosTodos } from '../modelos/usuarios.js';

export const rutasAdmin = Router();
rutasAdmin.use('/admin', requerirAdmin);

rutasAdmin.get('/admin', async (_req, res) => {
  const bandasDeCadaGenero = {};
  for (const clave of Object.keys(GENEROS)) {
    bandasDeCadaGenero[clave] = await bandasPorGenero(clave);
  }
  res.render('admin', {
    titulo: 'Administrar Bandas',
    hojaDeEstilo: 'php-style',
    estados: [...ESTADOS],
    usuarios: await usuariosTodos(),
    bandasDeCadaGenero,
    consultas: await consultasTodas(),
  });
});

/** Cada acción hace lo suyo y devuelve la sección a la que volver. */
const ACCIONES = {
  async 'actualizar-banda'(req, id) {
    const datos = {
      nombre: campo(req.body, 'nombre'),
      descripcion: campo(req.body, 'descripcion'),
      genero: campo(req.body, 'genero'),
      imagenPrincipal: campo(req.body, 'imagen_principal'),
      imagenFondo: campo(req.body, 'imagen_fondo'),
    };
    let error = validarBanda(datos.nombre, datos.descripcion, datos.genero);
    if (!error && (!rutaImagenEsValida(datos.imagenPrincipal) || !rutaImagenEsValida(datos.imagenFondo))) {
      error = 'Las imágenes tienen que ser rutas como images/rock/02.png';
    }
    if (error) {
      mensajeGuardar(req, 'error', error);
    } else {
      await bandaActualizar(id, datos);
      mensajeGuardar(req, 'ok', `Banda "${datos.nombre}" actualizada`);
    }
    return generoEsValido(datos.genero) ? datos.genero : 'usuarios';
  },

  async 'eliminar-banda'(req, id) {
    await bandaEliminar(id);
    mensajeGuardar(req, 'ok', 'Banda eliminada');
    return null;
  },

  async 'actualizar-usuario'(req, id) {
    const estado = entero(req.body, 'estado');
    const creaciones = entero(req.body, 'cantidad_creaciones');

    if (estado === null || !ESTADOS.has(estado)) {
      const opciones = [...ESTADOS].map(([valor, texto]) => `${valor} (${texto})`).join(', ');
      mensajeGuardar(req, 'error', `Estado inválido. Usá ${opciones}`);
    } else if (creaciones === null || creaciones < 0) {
      mensajeGuardar(req, 'error', 'La cantidad de creaciones tiene que ser un número mayor o igual a 0');
    } else if (id === req.usuario.id && estado !== ESTADO_ADMIN) {
      mensajeGuardar(req, 'error', 'No podés quitarte el rol de administrador a vos mismo');
    } else {
      await usuarioActualizarPermisos(id, estado, creaciones);
      mensajeGuardar(req, 'ok', 'Usuario actualizado');
    }
    return 'usuarios';
  },

  async 'eliminar-usuario'(req, id) {
    if (id === req.usuario.id) {
      mensajeGuardar(req, 'error', 'No podés eliminar tu propia cuenta desde el panel');
    } else {
      await usuarioEliminar(id);
      mensajeGuardar(req, 'ok', 'Usuario eliminado');
    }
    return 'usuarios';
  },

  async 'eliminar-consulta'(req, id) {
    await consultaEliminar(id);
    mensajeGuardar(req, 'ok', 'Consulta eliminada');
    return 'consultas';
  },
};

rutasAdmin.post('/admin', async (req, res) => {
  const nombreAccion = campo(req.body, 'accion');
  const seccion = Object.hasOwn(ACCIONES, nombreAccion)
    ? await ACCIONES[nombreAccion](req, entero(req.body, 'id') ?? 0)
    : null;
  res.redirect(seccion ? `/admin#tabla-${seccion}` : '/admin');
});
