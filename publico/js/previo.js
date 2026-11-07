/*
 * Corre antes de pintar la página. Marca que hay JavaScript, para que lo que
 * va a entrar animado no se vea un instante antes de empezar.
 * Si las animaciones no llegan a arrancar, se muestra todo igual.
 */
(function () {
  var raiz = document.documentElement;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }
  raiz.classList.add('js');
  window.setTimeout(function () {
    if (!raiz.classList.contains('animado')) {
      raiz.classList.remove('js');
    }
  }, 2500);
})();
