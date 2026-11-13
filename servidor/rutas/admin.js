/**
 * Panel de administración. Cada sección es su propia página:
 *
 *   /admin            Resumen: pedidos pendientes y cartelera de bandas
 *   /admin/usuarios   Permisos de cada cuenta
 *   /admin/bandas     Bandas de un género, con su formulario de edición
 *   /admin/pedidos    Bandas que pidieron aparecer en el sitio
 *
 * Todos los formularios van a POST /admin con un campo "accion" que dice
 * exactamente qué hacer. Después de guardar se redirige, así F5 no repite la acción.
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
  imagenDeBanda,
  rutaImagenEsValida,
  validarBanda,
} from '../modelos/bandas.js';
import { consultaEliminar, consultasTodas } from '../modelos/consultas.js';
import { usuarioActualizarPermisos, usuarioEliminar, usuariosTodos } from '../modelos/usuarios.js';

export const rutasAdmin = Router();
rutasAdmin.use('/admin', requerirAdmin);

const CLAVES_DE_GENERO = Object.keys(GENEROS);

/** Las bandas de cada género, en el orden del sitio. */
async function bandasDeTodosLosGeneros() {
  const listas = await Promise.all(CLAVES_DE_GENERO.map((clave) => bandasPorGenero(clave)));
  return Object.fromEntries(CLAVES_DE_GENERO.map((clave, posicion) => [clave, listas[posicion]]));
}

/** Lo que necesita la barra del panel en todas sus páginas. */
async function datosDelPanel(req, res, next) {
  const consultas = await consultasTodas();
  res.locals.consultas = consultas;
  res.locals.pedidosPendientes = consultas.length;
  res.locals.imagenDeBanda = imagenDeBanda;
  next();
}

rutasAdmin.use('/admin', datosDelPanel);

function vistaDelPanel(res, vista, titulo, seccion, datos = {}) {
  res.render(`admin/${vista}`, { titulo, hojaDeEstilo: 'panel', sinIndexar: true, seccion, ...datos });
}

rutasAdmin.get('/admin', async (_req, res) => {
  const [usuarios, bandasDeCadaGenero] = await Promise.all([usuariosTodos(), bandasDeTodosLosGeneros()]);
  const totalBandas = Object.values(bandasDeCadaGenero).reduce((total, lista) => total + lista.length, 0);
  const sinFoto = Object.values(bandasDeCadaGenero).flat().filter((banda) => banda.imagen_principal === '').length;
  vistaDelPanel(res, 'resumen', 'Panel de MzaBeats', 'resumen', { usuarios, bandasDeCadaGenero, totalBandas, sinFoto });
});

rutasAdmin.get('/admin/usuarios', async (_req, res) => {
  vistaDelPanel(res, 'usuarios', 'Usuarios', 'usuarios', {
    usuarios: await usuariosTodos(),
    estados: [...ESTADOS],
    estadoAdmin: ESTADO_ADMIN,
  });
});

rutasAdmin.get('/admin/bandas', async (req, res) => {
  const bandasDeCadaGenero = await bandasDeTodosLosGeneros();
  const pedido = campo(req.query, 'genero');
  vistaDelPanel(res, 'bandas', 'Bandas', 'bandas', {
    bandasDeCadaGenero,
    generoActivo: generoEsValido(pedido) ? pedido : CLAVES_DE_GENERO[0],
  });
});

rutasAdmin.get('/admin/pedidos', (_req, res) => {
  vistaDelPanel(res, 'pedidos', 'Pedidos', 'pedidos');
});

const paginaDeBandas = (genero) => (generoEsValido(genero) ? `/admin/bandas?genero=${genero}` : '/admin/bandas');

/** Cada acción hace lo suyo y devuelve la dirección a la que volver. */
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
      return `${paginaDeBandas(campo(req.body, 'genero_actual'))}#banda-${id}`;
    }
    await bandaActualizar(id, datos);
    mensajeGuardar(req, 'ok', `Banda "${datos.nombre}" actualizada`);
    return `${paginaDeBandas(datos.genero)}#banda-${id}`;
  },

  async 'eliminar-banda'(req, id) {
    await bandaEliminar(id);
    mensajeGuardar(req, 'ok', 'Banda eliminada');
    return paginaDeBandas(campo(req.body, 'genero_actual'));
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
    return '/admin/usuarios';
  },

  async 'eliminar-usuario'(req, id) {
    if (id === req.usuario.id) {
      mensajeGuardar(req, 'error', 'No podés eliminar tu propia cuenta desde el panel');
    } else {
      await usuarioEliminar(id);
      mensajeGuardar(req, 'ok', 'Usuario eliminado');
    }
    return '/admin/usuarios';
  },

  async 'eliminar-consulta'(req, id) {
    await consultaEliminar(id);
    mensajeGuardar(req, 'ok', 'Pedido eliminado');
    return '/admin/pedidos';
  },
};

rutasAdmin.post('/admin', async (req, res) => {
  const nombreAccion = campo(req.body, 'accion');
  const destino = Object.hasOwn(ACCIONES, nombreAccion)
    ? await ACCIONES[nombreAccion](req, entero(req.body, 'id') ?? 0)
    : '/admin';
  res.redirect(destino);
});
