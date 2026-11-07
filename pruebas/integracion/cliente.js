/**
 * Un navegador mínimo para las pruebas: guarda la cookie de sesión y manda el
 * token CSRF de la última página con formulario.
 */
export const BASE = process.env.BASE_URL || 'http://localhost:8083';
export const CLAVE = 'clave-de-prueba';

export class Cliente {
  cookie = '';

  async pedir(ruta, opciones = {}) {
    const respuesta = await fetch(BASE + ruta, {
      redirect: 'manual',
      ...opciones,
      headers: { ...(this.cookie ? { cookie: this.cookie } : {}), ...opciones.headers },
    });
    const nueva = respuesta.headers.get('set-cookie');
    if (nueva) {
      this.cookie = nueva.split(';')[0];
    }
    return { estado: respuesta.status, cabeceras: respuesta.headers, html: await respuesta.text() };
  }

  get(ruta) {
    return this.pedir(ruta);
  }

  /** Envía un formulario sin token. */
  enviar(ruta, datos) {
    return this.pedir(ruta, {
      method: 'POST',
      headers: { 'content-type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(datos).toString(),
    });
  }

  /** Abre la página, toma el token de su formulario y lo envía. */
  async post(ruta, datos, paginaDelFormulario = ruta) {
    const { html } = await this.get(paginaDelFormulario);
    const token = html.match(/name="csrf_token" value="([^"]+)"/)?.[1] ?? '';
    return this.enviar(ruta, { ...datos, csrf_token: token });
  }

  async entrar(usuario, clave = CLAVE) {
    return this.post('/login', { user: usuario, password: clave });
  }
}

/** Sigue una redirección y devuelve la página de destino. */
export function seguir(cliente, { cabeceras }) {
  return cliente.get(cabeceras.get('location'));
}
