/**
 * Páginas públicas: inicio, géneros y formulario "¡Quiero aparecer!".
 */
import { Router } from 'express';
import { GENEROS, LIMITES, MESES_DE_PEDIDOS, RESPONSABLE, VERSION_LEGALES } from '../config.js';
import { campo } from '../lib/texto.js';
import { limitePedidos } from '../lib/limite.js';
import { mensajeGuardar } from '../lib/mensajes.js';
import { bandasPorGenero, generoEsValido, imagenDeBanda, rutaImagenEsValida } from '../modelos/bandas.js';
import { consultaCrear, validarConsulta } from '../modelos/consultas.js';

export const rutasSitio = Router();

rutasSitio.get('/', (_req, res) => {
  res.render('inicio', {
    titulo: 'MzaBeats',
    hojaDeEstilo: 'style',
    // El fondo de la primera sección es lo más grande que se ve al entrar:
    // pedirlo antes de que el CSS lo descubra adelanta la carga
    precargar: '/images/indie/00-bg.png',
  });
});

/* ---------- Términos y privacidad ---------- */

for (const [ruta, vista, titulo] of [
  ['/privacidad', 'privacidad', 'Política de privacidad'],
  ['/terminos', 'terminos', 'Términos y condiciones'],
]) {
  rutasSitio.get(ruta, (_req, res) => {
    res.render(`legales/${vista}`, {
      titulo,
      hojaDeEstilo: 'php-style',
      descripcion: `${titulo} de MzaBeats.`,
      version: VERSION_LEGALES,
      responsable: RESPONSABLE,
      mesesDePedidos: MESES_DE_PEDIDOS,
    });
  });
}

/** Datos de una banda listos para mostrar, sin rutas de imagen inválidas. */
function bandaParaMostrar(banda) {
  const imagen = imagenDeBanda(banda);
  const fondo = banda.imagen_fondo !== '' && rutaImagenEsValida(banda.imagen_fondo)
    ? `--item-bg: url('/${banda.imagen_fondo}');`
    : '';
  // Las biografías se cargan con saltos de línea: cada uno es un párrafo
  const parrafos = banda.descripcion.split(/\s*\n\s*/).filter((parrafo) => parrafo.trim() !== '');
  return { nombre: banda.nombre, parrafos, imagen, fondo };
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
    descripcion: `Bandas y artistas de ${GENEROS[genero].nombre.toLowerCase()} de Mendoza, Argentina.`,
    precargar: `/${GENEROS[genero].imagen}`,
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
    descripcion: 'Pedí que tu banda o proyecto mendocino aparezca en MzaBeats.',
    mesesDePedidos: MESES_DE_PEDIDOS,
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
  if (limitePedidos.superado(req.ip)) {
    const minutos = limitePedidos.minutosDeEspera(req.ip);
    res.status(429);
    mostrarFormulario(res, { error: `Ya recibimos varios pedidos tuyos. Probá de nuevo en ${minutos} minutos.`, valores });
    return;
  }
  const error = validarConsulta(valores.nombre, valores.razon, valores.email);
  if (error) {
    mostrarFormulario(res, { error, valores });
    return;
  }
  // Sólo cuentan los pedidos guardados: equivocarse en un campo no gasta envíos
  limitePedidos.registrar(req.ip);
  await consultaCrear(valores.nombre, valores.razon, valores.email);
  mensajeGuardar(req, 'ok', 'Consulta registrada correctamente');
  res.redirect('/formulario');
});
