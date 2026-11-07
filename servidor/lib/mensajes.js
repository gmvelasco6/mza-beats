/**
 * Mensajes que sobreviven a una redirección (por ejemplo "Banda guardada").
 * El tipo es 'ok' o 'error'.
 */

export function mensajeGuardar(req, tipo, texto) {
  req.session.mensajes ??= [];
  req.session.mensajes.push({ tipo, texto });
}

/** Deja mensajes() disponible para las plantillas: al leerlos, se borran. */
export function mensajes(req, res, next) {
  res.locals.mensajesPendientes = () => {
    const pendientes = req.session.mensajes ?? [];
    delete req.session.mensajes;
    return pendientes;
  };
  next();
}
