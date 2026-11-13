/*
 * Animaciones del sitio, hechas con GSAP.
 *
 * Reglas para que nada se rompa:
 *   - Sin JavaScript, o con "reducir movimiento" activado, la página se ve
 *     completa y quieta: acá sólo se suma movimiento.
 *   - Las plantillas marcan qué se anima con atributos:
 *       data-revelar="izquierda | derecha | abajo"  entra al aparecer en pantalla
 *       data-revelar="portada"                      entra al cargar la página
 *       data-parallax                               su fondo se mueve con el scroll
 */
(function () {
  const raiz = document.documentElement;
  const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (sinMovimiento || !window.gsap || !window.ScrollTrigger) {
    raiz.classList.remove('js');
    return;
  }

  const { gsap, ScrollTrigger } = window;
  gsap.registerPlugin(ScrollTrigger);
  raiz.classList.add('animado');

  const ES_ESCRITORIO = '(min-width: 901px)';
  const CON_PUNTERO = '(hover: hover) and (pointer: fine) and (min-width: 901px)';
  const esEscritorio = () => window.matchMedia(ES_ESCRITORIO).matches;
  const IMAGENES = '.img, .indie-img, .pop-img, .rock-img';

  /* ---------- Elementos que entran al aparecer en pantalla ---------- */

  function puntoDePartida(tipo) {
    if (!esEscritorio() || tipo === 'abajo') {
      return { x: 0, y: 40 };
    }
    return { x: tipo === 'izquierda' ? -70 : 70, y: 0 };
  }

  function revelar(contenedor) {
    contenedor.querySelectorAll('[data-revelar]:not([data-revelar="portada"]):not(.revelado)').forEach((elemento) => {
      gsap.set(elemento, { autoAlpha: 0, ...puntoDePartida(elemento.dataset.revelar) });
      elemento.classList.add('revelado');
      gsap.to(elemento, {
        autoAlpha: 1,
        x: 0,
        y: 0,
        duration: 0.9,
        ease: 'power3.out',
        clearProps: 'transform,opacity,visibility',
        scrollTrigger: { trigger: elemento, start: 'top 90%', once: true },
        onComplete: () => inclinarAlPasarElMouse(elemento),
      });
    });
  }

  /* ---------- Portada: el título entra letra por letra ---------- */

  function separarEnLetras(titulo) {
    const texto = titulo.textContent;
    titulo.setAttribute('aria-label', texto);
    titulo.textContent = '';
    texto.split(' ').forEach((palabra, posicion) => {
      if (posicion > 0) {
        titulo.append(' ');
      }
      const grupo = document.createElement('span');
      grupo.className = 'palabra';
      grupo.setAttribute('aria-hidden', 'true');
      for (const letra of palabra) {
        const caja = document.createElement('span');
        caja.className = 'letra';
        caja.textContent = letra;
        grupo.append(caja);
      }
      titulo.append(grupo);
    });
    return titulo.querySelectorAll('.letra');
  }

  function crearEcualizador(despuesDe) {
    const ecualizador = document.createElement('div');
    ecualizador.className = 'ecualizador';
    ecualizador.setAttribute('aria-hidden', 'true');
    for (let i = 0; i < 32; i += 1) {
      ecualizador.append(document.createElement('span'));
    }
    despuesDe.after(ecualizador);

    const barras = gsap.to(ecualizador.children, {
      scaleY: 'random(0.15, 1)',
      duration: 'random(0.35, 0.8)',
      ease: 'sine.inOut',
      repeat: -1,
      repeatRefresh: true,
      yoyo: true,
      stagger: { each: 0.03, from: 'random' },
    });
    ScrollTrigger.create({
      trigger: ecualizador,
      start: 'top bottom',
      end: 'bottom top',
      onToggle: ({ isActive }) => (isActive ? barras.resume() : barras.pause()),
    });
    return ecualizador;
  }

  function animarPortada() {
    const titulo = document.querySelector('.intro h1');
    const bajada = document.querySelector('.intro .bajada-portada');
    if (!titulo || !bajada) {
      return;
    }
    const letras = separarEnLetras(titulo);
    const ecualizador = crearEcualizador(bajada);
    titulo.classList.add('revelado');
    bajada.classList.add('revelado');

    gsap
      .timeline({ defaults: { ease: 'power3.out' } })
      .from(letras, { yPercent: 90, rotation: 6, autoAlpha: 0, duration: 0.7, stagger: 0.028, ease: 'back.out(1.7)' })
      .from(bajada, { y: 18, autoAlpha: 0, duration: 0.8, clearProps: 'transform,opacity,visibility' }, '-=0.3')
      .from(ecualizador, { autoAlpha: 0, scaleY: 0, duration: 0.6 }, '-=0.5');
  }

  /* ---------- Fondos con profundidad ---------- */

  function activarParallax(contenedor) {
    if (!window.matchMedia(ES_ESCRITORIO).matches) {
      return;
    }
    raiz.classList.add('con-parallax');
    contenedor.querySelectorAll('[data-parallax]').forEach((seccion) => {
      gsap.fromTo(
        seccion,
        { '--desplazo': '-7%' },
        {
          '--desplazo': '7%',
          ease: 'none',
          scrollTrigger: { trigger: seccion, start: 'top bottom', end: 'bottom top', scrub: true },
        },
      );
    });
  }

  /* ---------- Fotos que se inclinan siguiendo el mouse ---------- */

  function inclinarAlPasarElMouse(elemento) {
    const imagen = elemento.matches(IMAGENES) ? elemento : elemento.querySelector(IMAGENES);
    if (!imagen || !window.matchMedia(CON_PUNTERO).matches) {
      return;
    }
    gsap.set(imagen, { transformPerspective: 800 });
    const girarX = gsap.quickTo(imagen, 'rotationX', { duration: 0.5, ease: 'power3.out' });
    const girarY = gsap.quickTo(imagen, 'rotationY', { duration: 0.5, ease: 'power3.out' });
    const escalar = gsap.quickTo(imagen, 'scale', { duration: 0.5, ease: 'power3.out' });

    imagen.addEventListener('pointermove', (evento) => {
      const caja = imagen.getBoundingClientRect();
      girarY(((evento.clientX - caja.left) / caja.width - 0.5) * 9);
      girarX(((evento.clientY - caja.top) / caja.height - 0.5) * -9);
      escalar(1.03);
    });
    imagen.addEventListener('pointerleave', () => {
      girarX(0);
      girarY(0);
      escalar(1);
    });
  }

  /* ---------- Menú y barra de progreso ---------- */

  function animarMenu() {
    if (!document.querySelector('.logo-container')) {
      return; // las páginas de formularios tienen un menú fijo más simple
    }
    const menu = document.querySelector('nav');
    const compactar = () => menu.classList.toggle('compacta', window.scrollY > 40);
    compactar();
    window.addEventListener('scroll', compactar, { passive: true });

    const barra = document.createElement('div');
    barra.className = 'progreso';
    barra.setAttribute('aria-hidden', 'true');
    document.body.append(barra);
    gsap.to(barra, {
      scaleX: 1,
      ease: 'none',
      scrollTrigger: { start: 0, end: 'max', scrub: 0.3 },
    });

    const vieneDeOtroSitio = !document.referrer.startsWith(window.location.origin);
    if (vieneDeOtroSitio) {
      gsap.from(menu, { yPercent: -100, duration: 0.7, ease: 'power3.out', clearProps: 'transform' });
    }
  }

  /* ---------- Formularios y avisos ---------- */

  function animarFormularios() {
    document.querySelectorAll('.formulario').forEach((formulario) => {
      gsap.from(formulario.children, {
        y: 14,
        autoAlpha: 0,
        duration: 0.5,
        delay: 0.12,
        stagger: 0.035,
        ease: 'power2.out',
        clearProps: 'transform,opacity,visibility',
      });
    });
  }

  function animarAvisos() {
    document.querySelectorAll('.advertencia').forEach((aviso) => {
      gsap.to(aviso, {
        keyframes: { x: [0, -9, 8, -6, 5, -2, 0] },
        duration: 0.5,
        delay: 0.25,
        ease: 'power1.out',
        clearProps: 'transform',
      });
    });
    document.querySelectorAll('.verificado').forEach((aviso) => {
      gsap.from(aviso, {
        scale: 0.85,
        autoAlpha: 0,
        duration: 0.5,
        delay: 0.2,
        ease: 'back.out(2)',
        clearProps: 'transform,opacity,visibility',
      });
    });
  }

  /* ---------- Arranque ---------- */

  animarMenu();
  animarPortada();
  animarFormularios();
  animarAvisos();
  revelar(document);
  activarParallax(document);

  /** El buscador cambió las bandas: se animan las nuevas y se olvidan las viejas. */
  document.addEventListener('contenido:nuevo', ({ detail }) => {
    ScrollTrigger.getAll()
      .filter(({ trigger }) => trigger && !document.contains(trigger))
      .forEach((disparador) => disparador.kill());
    revelar(detail.raiz);
    activarParallax(detail.raiz);
    ScrollTrigger.refresh();
  });

  // Las fotos cambian el alto de la página al terminar de cargar
  window.addEventListener('load', () => ScrollTrigger.refresh());
})();
