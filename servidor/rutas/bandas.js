/**
 * Carga de una banda nueva.
 * Sólo para usuarios con permisos y creaciones disponibles; cada banda
 * cargada descuenta una creación.
 */
import { Router } from 'express';
import { LIMITES } from '../config.js';
import { transaccion } from '../lib/db.js';
import { puedeAgregarBandas } from '../lib/sesion.js';
import { campo } from '../lib/texto.js';
import { bandaCrear, validarBanda } from '../modelos/bandas.js';
import { usuarioDescontarCreacion } from '../modelos/usuarios.js';

export const rutasBandas = Router();

function soloConCreaciones(req, res, next) {
  if (!puedeAgregarBandas(req.usuario)) {
    res.redirect('/');
    return;
  }
  next();
}

function mostrarFormulario(res, error = null, valores = {}) {
  res.render('agregar-banda', { titulo: 'Agregar Banda', hojaDeEstilo: 'php-style', sinIndexar: true, error, valores, limites: LIMITES });
}

rutasBandas.get('/agregar-banda', soloConCreaciones, (_req, res) => mostrarFormulario(res));

rutasBandas.post('/agregar-banda', soloConCreaciones, async (req, res) => {
  const valores = {
    nombre: campo(req.body, 'nombre'),
    descripcion: campo(req.body, 'descripcion'),
    genero: campo(req.body, 'genero'),
  };
  const error = validarBanda(valores.nombre, valores.descripcion, valores.genero);
  if (error) {
    mostrarFormulario(res, error, valores);
    return;
  }

  // La banda se guarda sólo si se pudo descontar la creación (y viceversa)
  const guardada = await transaccion(async (conexion) => {
    if (!(await usuarioDescontarCreacion(req.usuario.id, conexion))) {
      return false;
    }
    await bandaCrear(valores.nombre, valores.descripcion, valores.genero, conexion);
    return true;
  });

  if (guardada) {
    res.redirect(`/generos/${valores.genero}`);
    return;
  }
  mostrarFormulario(res, 'No te quedan creaciones disponibles', valores);
});
