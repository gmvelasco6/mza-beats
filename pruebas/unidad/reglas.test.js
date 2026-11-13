/**
 * Reglas que no necesitan base de datos ni servidor: contraseñas y validaciones.
 */
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import { AVATARES } from '../../servidor/config.js';
import {
  claveEsCorrecta,
  claveHash,
  esMd5Vieja,
  necesitaActualizarse,
  validarClaveNueva,
} from '../../servidor/lib/claves.js';
import { campo, comoTexto, emailEsValido, entero, escaparLike, largo } from '../../servidor/lib/texto.js';
import { generoEsValido, imagenDeBanda, rutaImagenEsValida, validarBanda } from '../../servidor/modelos/bandas.js';
import { crearRecurso, webpSiConviene } from '../../servidor/lib/estaticos.js';
import { Limite } from '../../servidor/lib/limite.js';
import { validarConsulta } from '../../servidor/modelos/consultas.js';
import { avatarEsValido, rutaAvatar, validarFormatoDeCuenta } from '../../servidor/modelos/usuarios.js';

const CLAVE = 'clave-de-prueba';
const HASH_DE_PHP = '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO';
const HASH_MD5 = '9168d0f4dd7f22dea897d6ad28498529';

test('acepta los hashes que generó PHP', async () => {
  assert.equal(await claveEsCorrecta(CLAVE, HASH_DE_PHP), true);
  assert.equal(await claveEsCorrecta('otra-clave', HASH_DE_PHP), false);
  assert.equal(necesitaActualizarse(HASH_DE_PHP), false);
});

test('acepta MD5 viejo y pide actualizarlo', async () => {
  assert.equal(esMd5Vieja(HASH_MD5), true);
  assert.equal(await claveEsCorrecta(CLAVE, HASH_MD5), true);
  assert.equal(await claveEsCorrecta('otra-clave', HASH_MD5), false);
  assert.equal(necesitaActualizarse(HASH_MD5), true);
});

test('los hashes nuevos tienen el mismo formato que los de PHP', async () => {
  const hash = await claveHash(CLAVE);
  assert.match(hash, /^\$2y\$10\$/);
  assert.equal(hash.length, HASH_DE_PHP.length);
  assert.equal(await claveEsCorrecta(CLAVE, hash), true);
  assert.equal(necesitaActualizarse(hash), false);
});

test('valida la clave nueva', () => {
  assert.match(validarClaveNueva('corta', 'corta'), /al menos 8/);
  assert.match(validarClaveNueva('clave-larga', 'clave-distinta'), /no coinciden/);
  assert.equal(validarClaveNueva('clave-larga', 'clave-larga'), null);
});

test('lee los campos de un formulario sin confiar en su forma', () => {
  assert.equal(campo({ a: '  hola  ' }, 'a'), 'hola');
  assert.equal(campo({ a: ['x', 'y'] }, 'a'), '');
  assert.equal(campo(undefined, 'a'), '');
  assert.equal(comoTexto({ b: 1 }), '');
});

test('reconoce enteros como lo hacía PHP', () => {
  for (const [texto, esperado] of [['0', 0], ['67', 67], ['-3', -3], ['+5', 5], [' 8 ', 8]]) {
    assert.equal(entero({ n: texto }, 'n'), esperado);
  }
  for (const texto of ['', 'abc', '1.5', '007', '1e3', '0x10']) {
    assert.equal(entero({ n: texto }, 'n'), null);
  }
});

test('cuenta caracteres reales y no bytes', () => {
  assert.equal(largo('canción'), 7);
  assert.equal(largo('🎸🎸'), 2);
});

test('los comodines de LIKE se buscan como texto', () => {
  assert.equal(escaparLike('100%_a\\b'), '100\\%\\_a\\\\b');
});

test('sólo acepta rutas de imagen dentro de images/', () => {
  for (const buena of ['', 'images/rock/02.png', 'images/a_b-c/x.webp', 'images/x.jpeg']) {
    assert.equal(rutaImagenEsValida(buena), true, buena);
  }
  const malas = [
    'https://otro.sitio/x.png',
    'images/../../secreto.png',
    "images/x.png');background:url('y",
    'images/x.svg',
    '/images/x.png',
  ];
  for (const mala of malas) {
    assert.equal(rutaImagenEsValida(mala), false, mala);
  }
});

test('valida los géneros sin confundirse con propiedades de JavaScript', () => {
  assert.equal(generoEsValido('rock'), true);
  assert.equal(generoEsValido('jazz'), false);
  assert.equal(generoEsValido('constructor'), false);
  assert.equal(generoEsValido('__proto__'), false);
});

test('valida una banda', () => {
  assert.equal(validarBanda('', 'texto', 'rock'), 'Hay campos vacíos');
  assert.equal(validarBanda('x'.repeat(256), 'texto', 'rock'), 'El nombre es demasiado largo');
  assert.equal(validarBanda('Banda', 'x'.repeat(10001), 'rock'), 'La descripción es demasiado larga');
  assert.equal(validarBanda('Banda', 'texto', 'jazz'), 'Elegí un género válido');
  assert.equal(validarBanda('Banda', 'texto', 'rock'), null);
});

test('valida una consulta', () => {
  assert.equal(validarConsulta('', 'r', 'a@b.co'), 'Hay campos vacíos');
  assert.match(validarConsulta('n', 'x'.repeat(2001), 'a@b.co'), /demasiado larga/);
  assert.equal(validarConsulta('n', 'r', 'no-es-email'), 'El email no es válido');
  assert.equal(validarConsulta('n', 'r', 'a@b.co'), null);
  assert.equal(emailEsValido('a b@c.co'), false);
});

test('valida el formato de una cuenta', () => {
  assert.equal(validarFormatoDeCuenta('', 'usuario'), 'Hay campos vacíos');
  assert.match(validarFormatoDeCuenta('N', 'ab'), /entre 3 y 50/);
  assert.match(validarFormatoDeCuenta('N', 'con espacio'), /sólo puede tener/);
  assert.equal(validarFormatoDeCuenta('N', 'user.name-1_x'), null);
});

test('sólo acepta los dibujos del sitio como foto de perfil', () => {
  assert.equal(avatarEsValido(''), true);
  assert.equal(avatarEsValido('guitarra'), true);
  for (const malo of ['otro', '../../x', 'https://mal.com/x.png', 'guitarra.svg', 'GUITARRA']) {
    assert.equal(avatarEsValido(malo), false, malo);
  }
  assert.equal(rutaAvatar({ avatar: 'copa' }), '/images/avatares/copa.svg');
  for (const usuario of [{ avatar: null }, { avatar: '' }, { avatar: '../x' }, null]) {
    assert.equal(rutaAvatar(usuario), '/images/inicio/usuario.png');
  }
});

test('todos los dibujos de la lista existen', () => {
  for (const { clave } of AVATARES) {
    const dibujo = readFileSync(new URL(`../../publico/images/avatares/${clave}.svg`, import.meta.url), 'utf8');
    assert.match(dibujo, /^<svg /, clave);
    assert.ok(!/<script|href=|on[a-z]+=/i.test(dibujo), clave);
  }
});

test('una banda sin foto o con una ruta inválida usa el logo', () => {
  const logo = 'images/logo/logoMzaBeats.png';
  assert.equal(imagenDeBanda({ imagen_principal: '' }), logo);
  assert.equal(imagenDeBanda({ imagen_principal: 'https://mal.com/x.png' }), logo);
  assert.equal(imagenDeBanda({ imagen_principal: 'images/rock/02.png' }), 'images/rock/02.png');
});

/* ---------- Límite de envíos ---------- */

test('el límite deja pasar hasta el máximo y se libera con el tiempo', () => {
  let ahora = 1_000_000;
  const limite = new Limite(3, 60_000, () => ahora);
  for (let vez = 0; vez < 3; vez += 1) {
    assert.equal(limite.superado('1.2.3.4'), false);
    limite.registrar('1.2.3.4');
  }
  assert.equal(limite.superado('1.2.3.4'), true);
  assert.equal(limite.superado('5.6.7.8'), false, 'cada IP cuenta aparte');
  assert.equal(limite.minutosDeEspera('1.2.3.4'), 1);
  ahora += 60_001;
  assert.equal(limite.superado('1.2.3.4'), false);
  assert.equal(limite.envios.size, 0, 'no guarda lo vencido');
});

/* ---------- Archivos públicos ---------- */

test('recurso() agrega la huella del contenido y la recuerda', () => {
  const recurso = crearRecurso([['/', 'publico']]);
  const conHuella = recurso('/css/style.css');
  assert.match(conHuella, /^\/css\/style\.css\?v=[0-9a-f]{10}$/);
  assert.equal(recurso('/css/style.css'), conHuella);
  assert.equal(recurso('/css/no-existe.css'), '/css/no-existe.css');
});

test('sólo cambia por WebP las imágenes que tienen una copia más liviana', () => {
  const cambiar = webpSiConviene('publico');
  const pedir = (ruta, accept) => {
    const req = { method: 'GET', path: ruta, url: ruta, get: () => accept };
    const res = { vary() {} };
    cambiar(req, res, () => {});
    return req.url;
  };
  assert.equal(pedir('/images/indie/00.png', 'image/webp,*/*'), '/images/indie/00.webp');
  assert.equal(pedir('/images/indie/00.png', 'image/png'), '/images/indie/00.png');
  assert.equal(pedir('/images/no-existe.png', 'image/webp'), '/images/no-existe.png');
  assert.equal(pedir('/css/style.css', 'image/webp'), '/css/style.css');
});
