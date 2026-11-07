/**
 * Recorre el sitio de punta a punta contra una base descartable con las cuentas
 * de pruebas/semilla.sql. Las pruebas corren en orden y algunas dependen de lo
 * que guardaron las anteriores: empezá siempre con una base nueva.
 *
 *   BASE_URL=http://localhost:8083 npm run test:integracion
 */
import assert from 'node:assert/strict';
import { describe, test } from 'node:test';
import { CLAVE, Cliente, seguir } from './cliente.js';

const visitante = () => new Cliente();

async function comoUsuario(usuario, clave = CLAVE) {
  const cliente = new Cliente();
  const { estado, cabeceras } = await cliente.entrar(usuario, clave);
  assert.equal(estado, 302, `no pudo entrar ${usuario}`);
  assert.equal(cabeceras.get('location'), '/');
  return cliente;
}

describe('páginas públicas', () => {
  test('el servidor y la base responden', async () => {
    const { estado, html } = await visitante().get('/salud');
    assert.equal(estado, 200);
    assert.equal(html, 'ok');
  });

  test('el inicio muestra los géneros y no crea sesión', async () => {
    const { estado, html, cabeceras } = await visitante().get('/');
    assert.equal(estado, 200);
    for (const texto of ['Bienvenido a MzaBeats', 'Sección Indie', 'Sección Pop', 'Sección Rock', '¡Quiero aparecer!', 'Log in']) {
      assert.ok(html.includes(texto), texto);
    }
    assert.equal(cabeceras.get('set-cookie'), null);
  });

  test('manda las cabeceras de seguridad', async () => {
    const { cabeceras } = await visitante().get('/');
    assert.match(cabeceras.get('content-security-policy'), /default-src 'self'/);
    assert.equal(cabeceras.get('x-frame-options'), 'DENY');
    assert.equal(cabeceras.get('x-content-type-options'), 'nosniff');
    assert.equal(cabeceras.get('x-powered-by'), null);
  });

  test('cada género muestra sus bandas', async () => {
    const { estado, html } = await visitante().get('/generos/rock');
    assert.equal(estado, 200);
    assert.ok(html.includes('Sección Rock'));
    assert.ok(html.includes('Chancho Va'));
    assert.ok(html.includes("--item-bg: url(&#39;/images/rock/05-bg.png&#39;);"));
    assert.ok(!html.includes('Mi Amigo Invencible</h2>'));
  });

  test('un género inexistente da 404', async () => {
    for (const ruta of ['/generos/jazz', '/generos/constructor', '/no-existe']) {
      const { estado, html } = await visitante().get(ruta);
      assert.equal(estado, 404, ruta);
      assert.ok(html.includes('No encontramos esa página'));
    }
  });

  test('busca bandas dentro del género', async () => {
    const encontrada = await visitante().get('/generos/rock?buscador=chancho');
    assert.ok(encontrada.html.includes('Chancho Va'));
    assert.ok(!encontrada.html.includes('Brut4l'));
    assert.ok(!encontrada.html.includes('Sección Rock'));

    const nada = await visitante().get('/generos/rock?buscador=no-existe');
    assert.ok(nada.html.includes('No se encontraron resultados'));

    const comodin = await visitante().get('/generos/rock?buscador=%25');
    assert.ok(comodin.html.includes('No se encontraron resultados'));

    const limpia = await visitante().get('/generos/rock?buscador=chancho&limpiar=');
    assert.ok(limpia.html.includes('Brut4l'));
  });

  test('sirve estilos, imágenes y animaciones', async () => {
    const rutas = ['/css/style.css', '/css/animaciones.css', '/js/animaciones.js', '/vendor/gsap/gsap.min.js',
      '/vendor/gsap/ScrollTrigger.min.js', '/images/logo/logoMzaBeats.png', '/fonts/Kanit-Light.ttf'];
    for (const ruta of rutas) {
      assert.equal((await visitante().get(ruta)).estado, 200, ruta);
    }
  });

  test('no se puede leer el código ni salir de la carpeta pública', async () => {
    const rutas = ['/package.json', '/servidor/config.js', '/.env', '/..%2fpackage.json', '/vendor/gsap/..%2f..%2f..%2fpackage.json'];
    for (const ruta of rutas) {
      const { estado } = await visitante().get(ruta);
      assert.ok(estado === 404 || estado === 403 || estado === 400, `${ruta} dio ${estado}`);
    }
  });

  test('las direcciones viejas redirigen a las nuevas', async () => {
    const casos = {
      '/index.php': '/',
      '/generos/rock.php': '/generos/rock',
      '/generos/rock.php?buscador=chancho': '/generos/rock?buscador=chancho',
      '/vistas/formulario.php': '/formulario',
      '/vistas/agregar_banda.php': '/agregar-banda',
      '/vistas/login-signin/login.php': '/login',
      '/vistas/login-signin/register.php': '/registro',
      '/vistas/administrar/usuario.php': '/cuenta',
      '/vistas/administrar/cambiar-clave.php': '/cuenta/clave',
      '/vistas/administrar/administrar.php': '/admin',
    };
    for (const [vieja, nueva] of Object.entries(casos)) {
      const { estado, cabeceras } = await visitante().get(vieja);
      assert.equal(estado, 301, vieja);
      assert.equal(cabeceras.get('location'), nueva);
    }
  });
});

describe('protección de formularios', () => {
  test('rechaza un POST sin token', async () => {
    const { estado, html } = await visitante().enviar('/login', { user: 'admin-prueba', password: CLAVE });
    assert.equal(estado, 400);
    assert.ok(html.includes('El formulario venció'));
  });

  test('rechaza el token de otra sesión', async () => {
    const otro = visitante();
    const { html } = await otro.get('/login');
    const token = html.match(/name="csrf_token" value="([^"]+)"/)[1];

    const cliente = visitante();
    await cliente.get('/login');
    const { estado } = await cliente.enviar('/login', { user: 'admin-prueba', password: CLAVE, csrf_token: token });
    assert.equal(estado, 400);
  });

  test('la cookie de sesión es httpOnly y sameSite', async () => {
    const { cabeceras } = await visitante().get('/login');
    const cookie = cabeceras.get('set-cookie');
    assert.match(cookie, /^mzabeats_sesion=/);
    assert.match(cookie, /HttpOnly/);
    assert.match(cookie, /SameSite=Lax/);
  });

  test('escapa lo que escribió el usuario', async () => {
    const ataque = '"><script>alert(1)</script>';
    const { html } = await visitante().post('/formulario', { nombre: ataque, razon: ataque, email: 'mal' });
    assert.ok(html.includes('El email no es válido'));
    assert.ok(!html.includes('<script>alert(1)'));
    assert.ok(html.includes('&lt;script&gt;alert(1)'));
  });
});

describe('formulario "¡Quiero aparecer!"', () => {
  test('guarda la consulta y avisa una sola vez', async () => {
    const cliente = visitante();
    const respuesta = await cliente.post('/formulario', {
      nombre: 'Banda Consultante', razon: 'Queremos aparecer', email: 'banda@ejemplo.com',
    });
    assert.equal(respuesta.estado, 302);
    const pagina = await seguir(cliente, respuesta);
    assert.ok(pagina.html.includes('Consulta registrada correctamente'));
    assert.ok(!(await cliente.get('/formulario')).html.includes('Consulta registrada correctamente'));
  });

  test('avisa si faltan campos y conserva lo escrito', async () => {
    const { estado, html } = await visitante().post('/formulario', { nombre: 'Mi Banda', razon: '', email: '' });
    assert.equal(estado, 200);
    assert.ok(html.includes('Hay campos vacíos'));
    assert.ok(html.includes('value="Mi Banda"'));
  });
});

describe('registro', () => {
  const datos = { name: 'Nueva', user: 'nueva-cuenta', password: 'clave-nueva-123', 'password-confirm': 'clave-nueva-123' };

  test('rechaza datos inválidos', async () => {
    const casos = [
      [{ ...datos, name: '' }, 'Hay campos vacíos'],
      [{ ...datos, user: 'ab' }, 'entre 3 y 50'],
      [{ ...datos, user: 'con espacio' }, 'sólo puede tener'],
      [{ ...datos, user: 'admin-prueba' }, 'Usuario existente'],
      [{ ...datos, password: 'corta', 'password-confirm': 'corta' }, 'al menos 8'],
      [{ ...datos, 'password-confirm': 'otra-clave-123' }, 'no coinciden'],
    ];
    for (const [envio, mensaje] of casos) {
      const { estado, html } = await visitante().post('/registro', envio);
      assert.equal(estado, 200, mensaje);
      assert.ok(html.includes(mensaje), mensaje);
    }
  });

  test('crea la cuenta y deja entrar', async () => {
    const cliente = visitante();
    const respuesta = await cliente.post('/registro', datos);
    assert.equal(respuesta.estado, 302);
    assert.equal(respuesta.cabeceras.get('location'), '/login');
    assert.ok((await seguir(cliente, respuesta)).html.includes('Cuenta creada'));

    const nueva = await comoUsuario('nueva-cuenta', 'clave-nueva-123');
    const { html } = await nueva.get('/');
    assert.ok(html.includes('Hola! nueva-cuenta'));
    assert.ok(html.includes('Ver Cuenta'));
    assert.ok(!html.includes('Administrar'));
    assert.ok(!html.includes('Agregar banda'));
    assert.ok(!html.includes('¡Quiero aparecer!'));
  });
});

describe('inicio y cierre de sesión', () => {
  test('entra una cuenta creada por la versión en PHP', async () => {
    const cliente = await comoUsuario('admin-prueba');
    assert.ok((await cliente.get('/')).html.includes('Hola! admin-prueba'));
  });

  test('entra una cuenta con MD5 y sigue entrando después de actualizarla', async () => {
    await comoUsuario('viejo');
    await comoUsuario('viejo');
  });

  test('cambia el id de sesión al entrar', async () => {
    const cliente = visitante();
    await cliente.get('/login');
    const antes = cliente.cookie;
    await cliente.entrar('admin-prueba');
    assert.notEqual(cliente.cookie, antes);
  });

  test('rechaza datos incorrectos con el mismo mensaje', async () => {
    const casos = [['admin-prueba', 'clave-incorrecta'], ['no-existe', 'clave-incorrecta']];
    for (const [user, password] of casos) {
      const { estado, html } = await visitante().post('/login', { user, password });
      assert.equal(estado, 200);
      assert.ok(html.includes('Acceso denegado'));
    }
    const vacio = await visitante().post('/login', { user: '', password: '' });
    assert.ok(vacio.html.includes('Hay campos vacíos'));
  });

  test('bloquea tras 5 intentos fallidos, aun con la clave correcta', async () => {
    for (let i = 0; i < 5; i += 1) {
      const { html } = await visitante().post('/login', { user: 'bloqueable', password: 'clave-incorrecta' });
      assert.ok(html.includes('Acceso denegado'));
    }
    const { estado, html } = await visitante().post('/login', { user: 'bloqueable', password: CLAVE });
    assert.equal(estado, 200);
    assert.ok(html.includes('Demasiados intentos fallidos'));
  });

  test('cerrar sesión exige el formulario y la termina', async () => {
    const cliente = await comoUsuario('sin-permisos');
    const conEnlaceViejo = await cliente.get('/sesion_control/control_close_sesion.php');
    assert.equal(conEnlaceViejo.estado, 302);
    assert.ok((await cliente.get('/')).html.includes('Hola! sin-permisos'));

    const salida = await cliente.post('/salir', {}, '/');
    assert.equal(salida.estado, 302);
    assert.equal(salida.cabeceras.get('location'), '/login');
    assert.ok((await cliente.get('/')).html.includes('Log in'));
  });

  test('quien ya entró no ve login ni registro', async () => {
    const cliente = await comoUsuario('sin-permisos');
    for (const ruta of ['/login', '/registro']) {
      const { estado, cabeceras } = await cliente.get(ruta);
      assert.equal(estado, 302, ruta);
      assert.equal(cabeceras.get('location'), '/');
    }
  });
});

describe('permisos', () => {
  test('un visitante no entra a las páginas privadas', async () => {
    for (const ruta of ['/cuenta', '/cuenta/clave', '/admin', '/agregar-banda']) {
      const { estado, cabeceras } = await visitante().get(ruta);
      assert.equal(estado, 302, ruta);
      assert.equal(cabeceras.get('location'), '/');
    }
  });

  test('un usuario común no entra al panel, ni por GET ni por POST', async () => {
    const cliente = await comoUsuario('sin-permisos');
    const pagina = await cliente.get('/admin');
    assert.equal(pagina.estado, 302);
    const envio = await cliente.post('/admin', { accion: 'eliminar-banda', id: '12' }, '/');
    assert.equal(envio.estado, 302);
    assert.equal(envio.cabeceras.get('location'), '/');
    assert.ok((await visitante().get('/generos/rock')).html.includes('Chancho Va'));
  });

  test('una cuenta sin permisos no carga bandas aunque tenga creaciones', async () => {
    const cliente = await comoUsuario('sin-permisos');
    assert.equal((await cliente.get('/agregar-banda')).estado, 302);
    assert.ok((await cliente.get('/')).html.includes('¡Quiero aparecer!'));
  });
});

describe('carga de bandas', () => {
  test('valida los datos y no gasta la creación', async () => {
    const cliente = await comoUsuario('cargador');
    const { estado, html } = await cliente.post('/agregar-banda', { nombre: 'X', descripcion: 'Y', genero: 'jazz' });
    assert.equal(estado, 200);
    assert.ok(html.includes('Elegí un género válido'));
    assert.equal((await cliente.get('/agregar-banda')).estado, 200);
  });

  test('guarda la banda y descuenta la única creación', async () => {
    const cliente = await comoUsuario('cargador');
    assert.ok((await cliente.get('/')).html.includes('Agregar banda'));

    const respuesta = await cliente.post('/agregar-banda', {
      nombre: 'Banda Cargada', descripcion: 'Descripción cargada', genero: 'pop',
    });
    assert.equal(respuesta.estado, 302);
    assert.equal(respuesta.cabeceras.get('location'), '/generos/pop');

    const genero = await seguir(cliente, respuesta);
    assert.ok(genero.html.includes('Banda Cargada'));
    assert.ok(genero.html.includes('src="/images/logo/logoMzaBeats.png"'));

    assert.equal((await cliente.get('/agregar-banda')).estado, 302);
    assert.ok(!(await cliente.get('/')).html.includes('Agregar banda'));
  });
});

describe('mi cuenta', () => {
  test('rechaza un usuario ya tomado y conserva lo escrito', async () => {
    const cliente = await comoUsuario('cambiante');
    const { html } = await cliente.post('/cuenta', { nombre: 'Nuevo Nombre', usuario: 'admin-prueba' });
    assert.ok(html.includes('Usuario existente'));
    assert.ok(html.includes('value="Nuevo Nombre"'));
  });

  test('cambia nombre y usuario', async () => {
    const cliente = await comoUsuario('cambiante');
    const respuesta = await cliente.post('/cuenta', { nombre: 'Nombre Cambiado', usuario: 'cambiante2' });
    assert.equal(respuesta.estado, 302);
    const pagina = await seguir(cliente, respuesta);
    assert.ok(pagina.html.includes('Datos actualizados'));
    assert.ok(pagina.html.includes('value="Nombre Cambiado"'));
    assert.ok((await cliente.get('/')).html.includes('Hola! cambiante2'));
  });

  test('cambiar la clave pide la actual', async () => {
    const cliente = await comoUsuario('cambiante2');
    const casos = [
      [{ 'clave-actual': '', password: 'x', 'new-password': 'x' }, 'Hay campos vacios'],
      [{ 'clave-actual': 'incorrecta', password: 'clave-final-123', 'new-password': 'clave-final-123' }, 'no es correcta'],
      [{ 'clave-actual': CLAVE, password: 'corta', 'new-password': 'corta' }, 'al menos 8'],
      [{ 'clave-actual': CLAVE, password: 'clave-final-123', 'new-password': 'otra' }, 'no coinciden'],
    ];
    for (const [envio, mensaje] of casos) {
      const { html } = await cliente.post('/cuenta/clave', envio);
      assert.ok(html.includes(mensaje), mensaje);
    }
  });

  test('la clave nueva sirve, la vieja no, y la sesión sigue abierta', async () => {
    const cliente = await comoUsuario('cambiante2');
    const respuesta = await cliente.post('/cuenta/clave', {
      'clave-actual': CLAVE, password: 'clave-final-123', 'new-password': 'clave-final-123',
    });
    assert.equal(respuesta.estado, 302);
    assert.ok((await seguir(cliente, respuesta)).html.includes('Contraseña actualizada'));
    await comoUsuario('cambiante2', 'clave-final-123');
    const conLaVieja = await visitante().entrar('cambiante2');
    assert.ok(conLaVieja.html.includes('Acceso denegado'));
  });
});

describe('panel de administración', () => {
  const idEn = (html, usuario) => {
    const tramo = html.slice(html.indexOf(`value="${usuario}" readonly`));
    return tramo.match(/form-usuario-(\d+)/)[1];
  };

  test('muestra usuarios, bandas y consultas, pero nunca las claves', async () => {
    const cliente = await comoUsuario('admin-prueba');
    const { estado, html } = await cliente.get('/admin');
    assert.equal(estado, 200);
    for (const texto of ['Bandas Indie', 'Bandas Pop', 'Bandas Rock', 'admin-prueba', 'Chancho Va', 'Banda Consultante', '67 = Administrador']) {
      assert.ok(html.includes(texto), texto);
    }
    assert.ok(!html.includes('$2y$'));
  });

  test('actualiza permisos y rechaza valores inválidos', async () => {
    const cliente = await comoUsuario('admin-prueba');
    const id = idEn((await cliente.get('/admin')).html, 'descartable');
    const casos = [
      [{ estado: '5', cantidad_creaciones: '0' }, 'Estado inválido'],
      [{ estado: '1', cantidad_creaciones: '-1' }, 'mayor o igual a 0'],
      [{ estado: '1', cantidad_creaciones: 'x' }, 'mayor o igual a 0'],
      [{ estado: '1', cantidad_creaciones: '2' }, 'Usuario actualizado'],
    ];
    for (const [envio, mensaje] of casos) {
      const respuesta = await cliente.post('/admin', { accion: 'actualizar-usuario', id, ...envio }, '/admin');
      assert.equal(respuesta.cabeceras.get('location'), '/admin#tabla-usuarios');
      const pagina = await cliente.get('/admin');
      assert.ok(pagina.html.includes(mensaje), mensaje);
    }
    const permitido = await comoUsuario('descartable');
    assert.equal((await permitido.get('/agregar-banda')).estado, 200);
  });

  test('un administrador no puede quitarse el rol ni eliminarse', async () => {
    const cliente = await comoUsuario('admin-prueba');
    const id = idEn((await cliente.get('/admin')).html, 'admin-prueba');

    await cliente.post('/admin', { accion: 'actualizar-usuario', id, estado: '1', cantidad_creaciones: '0' }, '/admin');
    assert.ok((await cliente.get('/admin')).html.includes('No podés quitarte el rol'));

    await cliente.post('/admin', { accion: 'eliminar-usuario', id }, '/admin');
    assert.ok((await cliente.get('/admin')).html.includes('No podés eliminar tu propia cuenta'));
  });

  test('eliminar un usuario cierra su sesión abierta', async () => {
    const descartable = await comoUsuario('descartable');
    const cliente = await comoUsuario('admin-prueba');
    const id = idEn((await cliente.get('/admin')).html, 'descartable');
    await cliente.post('/admin', { accion: 'eliminar-usuario', id }, '/admin');
    assert.ok((await cliente.get('/admin')).html.includes('Usuario eliminado'));
    assert.ok((await descartable.get('/')).html.includes('Log in'));
  });

  test('edita una banda y valida sus rutas de imagen', async () => {
    const cliente = await comoUsuario('admin-prueba');
    const { html } = await cliente.get('/admin');
    const id = html.slice(html.indexOf('value="Banda Cargada"')).match(/form-banda-(\d+)/)[1];
    const banda = { accion: 'actualizar-banda', id, nombre: 'Banda Editada', descripcion: 'Texto', genero: 'indie' };

    await cliente.post('/admin', { ...banda, imagen_principal: 'https://mal.com/x.png', imagen_fondo: '' }, '/admin');
    assert.ok((await cliente.get('/admin')).html.includes('Las imágenes tienen que ser rutas'));

    const bien = await cliente.post('/admin', { ...banda, imagen_principal: 'images/indie/01.png', imagen_fondo: 'images/indie/01-bg.png' }, '/admin');
    assert.equal(bien.cabeceras.get('location'), '/admin#tabla-indie');
    assert.ok((await cliente.get('/admin')).html.includes('Banda &#34;Banda Editada&#34; actualizada'));

    const genero = await visitante().get('/generos/indie');
    assert.ok(genero.html.includes('Banda Editada'));
    assert.ok(genero.html.includes('src="/images/indie/01.png"'));
  });

  test('elimina una banda y una consulta', async () => {
    const cliente = await comoUsuario('admin-prueba');
    let { html } = await cliente.get('/admin');
    const idBanda = html.slice(html.indexOf('value="Banda Editada"')).match(/form-banda-(\d+)/)[1];
    await cliente.post('/admin', { accion: 'eliminar-banda', id: idBanda }, '/admin');
    assert.ok((await cliente.get('/admin')).html.includes('Banda eliminada'));
    assert.ok(!(await visitante().get('/generos/indie')).html.includes('Banda Editada'));

    ({ html } = await cliente.get('/admin'));
    const tramo = html.slice(html.indexOf('value="Banda Consultante"'));
    const idConsulta = tramo.match(/name="id" value="(\d+)"/)[1];
    const respuesta = await cliente.post('/admin', { accion: 'eliminar-consulta', id: idConsulta }, '/admin');
    assert.equal(respuesta.cabeceras.get('location'), '/admin#tabla-consultas');
    const final = await cliente.get('/admin');
    assert.ok(final.html.includes('Consulta eliminada'));
    assert.ok(!final.html.includes('Banda Consultante'));
  });

  test('una acción desconocida no hace nada', async () => {
    const cliente = await comoUsuario('admin-prueba');
    for (const accion of ['no-existe', 'constructor', '__proto__']) {
      const { estado, cabeceras } = await cliente.post('/admin', { accion, id: '1' }, '/admin');
      assert.equal(estado, 302, accion);
      assert.equal(cabeceras.get('location'), '/admin');
    }
  });
});
