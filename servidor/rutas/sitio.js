/**
 * Páginas públicas: inicio, géneros y formulario "¡Quiero aparecer!".
 */
import { Router } from 'express';
import { GENEROS, IMAGEN_BANDA_POR_DEFECTO, LIMITES } from '../config.js';
import { campo } from '../lib/texto.js';
import { mensajeGuardar } from '../lib/mensajes.js';
import { bandasPorGenero, generoEsValido, rutaImagenEsValida } from '../modelos/bandas.js';
import { consultaCrear, validarConsulta } from '../modelos/consultas.js';

export const rutasSitio = Router();

rutasSitio.get('/', (_req, res) => {
  res.render('inicio', { titulo: 'MzaBeats', hojaDeEstilo: 'style' });
});

/** Datos de una banda listos para mostrar, sin rutas de imagen inválidas. */
function bandaParaMostrar(banda) {
  const imagen = banda.imagen_principal !== '' && rutaImagenEsValida(banda.imagen_principal)
    ? banda.imagen_principal
    : IMAGEN_BANDA_POR_DEFECTO;
  const fondo = banda.imagen_fondo !== '' && rutaImagenEsValida(banda.imagen_fondo)
    ? `--item-bg: url('/${banda.imagen_fondo}');`
    : '';
  return { nombre: banda.nombre, descripcion: banda.descripcion, imagen, fondo };
}

rutasSitio.get('/generos/:genero', async (req, res, next) => {
  const { genero } = req.params;
  if (!generoEsValido(genero)) {
    next();
    return;
  }
  const busqueda = 'limpiar' in req.query ? '' : campo(req.query, 'buscador');
  const bandas = await bandasPorGenero(genero, busqueda);
  res.render('genero', {
    titulo: GENEROS[genero].nombre,
    hojaDeEstilo: 'genero-style',
    genero,
    info: GENEROS[genero],
    busqueda,
    bandas: bandas.map(bandaParaMostrar),
  });
});

function mostrarFormulario(res, { error = null, valores = {} } = {}) {
  res.render('formulario', {
    titulo: '¡Quiero aparecer!',
    hojaDeEstilo: 'php-style',
    error,
    valores,
    limites: LIMITES,
  });
}

rutasSitio.get('/formulario', (_req, res) => mostrarFormulario(res));

rutasSitio.post('/formulario', async (req, res) => {
  const valores = {
    nombre: campo(req.body, 'nombre'),
    razon: campo(req.body, 'razon'),
    email: campo(req.body, 'email'),
  };
  const error = validarConsulta(valores.nombre, valores.razon, valores.email);
  if (error) {
    mostrarFormulario(res, { error, valores });
    return;
  }
  await consultaCrear(valores.nombre, valores.razon, valores.email);
  mensajeGuardar(req, 'ok', 'Consulta registrada correctamente');
  res.redirect('/formulario');
});
