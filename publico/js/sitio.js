/*
 * Comportamiento del sitio que no es animación: confirmar antes de borrar y
 * buscar bandas sin recargar. Sin JavaScript, el buscador igual funciona como
 * un formulario común.
 */

/** Los botones con data-confirmar preguntan antes de enviar el formulario. */
document.addEventListener('click', (evento) => {
  const boton = evento.target.closest('[data-confirmar]');
  if (boton && !window.confirm(boton.dataset.confirmar)) {
    evento.preventDefault();
  }
});

/** Al elegir un dibujo, la foto de arriba muestra cómo va a quedar. */
document.addEventListener('change', ({ target }) => {
  const vista = document.querySelector('[data-avatar-vista]');
  if (vista && target.matches('[data-avatar-ruta]')) {
    vista.src = target.dataset.avatarRuta;
  }
});

/** Al volver a una banda del panel (#banda-12), se abre para seguir editándola. */
function abrirBandaDeLaDireccion() {
  const destino = window.location.hash && document.querySelector(window.location.hash);
  if (destino instanceof HTMLDetailsElement) {
    destino.open = true;
    destino.scrollIntoView({ block: 'nearest' });
  }
}

abrirBandaDeLaDireccion();

function iniciarBuscador() {
  const formulario = document.querySelector('.buscador-container');
  const resultados = document.getElementById('resultados');
  if (!formulario || !resultados) {
    return;
  }
  const entrada = formulario.elements.buscador;
  let pedido = null;
  let espera = null;

  async function buscar(texto) {
    pedido?.abort();
    pedido = new AbortController();
    const direccion = new URL(window.location.pathname, window.location.origin);
    if (texto !== '') {
      direccion.searchParams.set('buscador', texto);
    }
    const respuesta = await fetch(direccion, { signal: pedido.signal });
    const pagina = new DOMParser().parseFromString(await respuesta.text(), 'text/html');
    resultados.replaceChildren(...pagina.getElementById('resultados').children);
    window.history.replaceState(null, '', direccion);
    document.dispatchEvent(new CustomEvent('contenido:nuevo', { detail: { raiz: resultados } }));
  }

  /** Si algo falla, se hace la búsqueda de la forma común. */
  function buscarOFormularioComun(texto) {
    buscar(texto).catch((error) => {
      if (error.name !== 'AbortError') {
        formulario.submit();
      }
    });
  }

  formulario.addEventListener('submit', (evento) => {
    evento.preventDefault();
    window.clearTimeout(espera);
    if (evento.submitter?.name === 'limpiar') {
      entrada.value = '';
    }
    buscarOFormularioComun(entrada.value.trim());
  });

  entrada.addEventListener('input', () => {
    window.clearTimeout(espera);
    espera = window.setTimeout(() => buscarOFormularioComun(entrada.value.trim()), 300);
  });
}

iniciarBuscador();
