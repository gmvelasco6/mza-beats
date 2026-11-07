/**
 * Configuración general de MzaBeats.
 *
 * Todo lo que es "dato fijo" de la aplicación vive acá, para no tener
 * números mágicos ni textos repetidos desparramados por el código.
 */

/** Valores de la columna usuarios.estado */
export const ESTADO_SIN_PERMISOS = 0; // puede entrar, pero no cargar bandas
export const ESTADO_USUARIO = 1; // usuario común (valor al registrarse)
export const ESTADO_ADMIN = 67; // acceso al panel de administración

export const ESTADOS = new Map([
  [ESTADO_SIN_PERMISOS, 'Sin permisos'],
  [ESTADO_USUARIO, 'Usuario'],
  [ESTADO_ADMIN, 'Administrador'],
]);

/** Reglas para los datos que cargan los usuarios */
export const LIMITES = {
  claveMinimo: 8,
  usuarioMinimo: 3,
  usuarioMaximo: 50,
  nombreMaximo: 100,
  bandaNombreMaximo: 255,
  textoMaximo: 10000,
  consultaRazonMaximo: 2000,
};

/**
 * Bloqueo de login: después de tantos intentos fallidos en `minutos`,
 * hay que esperar. Se cuenta por usuario y por IP.
 */
export const BLOQUEO_LOGIN = {
  maximoPorUsuario: 5,
  maximoPorIp: 20,
  minutos: 15,
};

/** Imagen que se muestra cuando una banda todavía no tiene foto cargada */
export const IMAGEN_BANDA_POR_DEFECTO = 'images/logo/logoMzaBeats.png';

/**
 * Géneros del sitio.
 *
 * Para agregar un género nuevo:
 *   1. Sumá una entrada acá (la clave es la que se guarda en bandas.genero).
 *   2. Agregá las imágenes en publico/images/<clave>/.
 *   3. Agregá el fondo de su sección en publico/css/style.css.
 * El menú, las páginas, el panel de administración y el formulario de bandas
 * lo toman solos.
 */
export const GENEROS = {
  indie: {
    nombre: 'Indie',
    imagen: 'images/indie/00.png',
    imagenInicio: 'images/inicio/indie.png',
    intro: `Mendoza ha sido un semillero de música alternativa y emergente,
      con una rica escena indie que ha crecido a lo largo de los años.
      La ciudad ha visto surgir a bandas como Mi Amigo Invencible,
      que se ha consolidado como una de las más representativas,
      fusionando elementos del indie rock con un sonido melódico y profundo.
      Otros grupos como Pasado Verde y Usted Señalemelo
      también han dejado una huella en la escena local e incluso
      han ganado reconocimiento a nivel nacional.
      La escena indie mendocina se caracteriza por su autenticidad,
      con bandas que experimentan con diferentes estilos y sonidos,
      desde el folk más suave hasta el rock más experimental.
      Los bares y teatros de la ciudad se llenan de jóvenes
      que disfrutan de la libertad creativa que esta movida representa,
      convirtiendo a Mendoza en uno de los lugares más vibrantes para
      el indie en Argentina.`,
  },
  pop: {
    nombre: 'Pop',
    imagen: 'images/pop/00.png',
    imagenInicio: 'images/inicio/pop.png',
    intro: `La escena pop de Mendoza ha crecido con fuerza en los últimos años,
      dando lugar a propuestas frescas y originales que combinan
      lo melódico con lo experimental.
      Artistas como Mariana Päraway han llevado el pop mendocino a un nivel
      más íntimo y sofisticado, fusionando sintetizadores,
      sonidos andinos y letras cargadas de emoción.
      También destacan bandas como Spaghetti Western,
      que incorporan elementos electrónicos y una estética retro que los
      hace únicos dentro del género. El pop mendocino no se limita a lo comercial,
      sino que explora distintas sonoridades con identidad propia,
      influenciado por la diversidad cultural y el espíritu artístico de la región.
      Estos proyectos suelen presentarse en espacios culturales independientes,
      festivales locales y ciclos acústicos, fortaleciendo una comunidad
      creativa en constante evolución.`,
  },
  rock: {
    nombre: 'Rock',
    imagen: 'images/rock/00.png',
    imagenInicio: 'images/inicio/rock.png',
    intro: `El rock mendocino tiene una historia sólida y vibrante,
      con bandas que han sabido combinar la potencia del género con un
      fuerte arraigo local. Desde los años 90,
      grupos como Chancho Va comenzaron a marcar el rumbo con un estilo directo y
      letras comprometidas, mientras que bandas como Monos en Bolas
      y La Skandalosa Tripulación sumaron nuevas capas al sonido rockero,
      mezclando funk, ska y reggae.
      La escena actual mantiene viva la energía del rock con propuestas
      que van desde lo alternativo hasta el hard rock,
      consolidándose en escenarios locales, festivales provinciales y
      encuentros de música independiente.
      El espíritu del rock en Mendoza está marcado por la autogestión,
      la pasión por el vivo y la conexión genuina con el público,
      convirtiéndolo en uno de los géneros más influyentes de la provincia.`,
  },
};

/** Lo que viene del entorno (Docker Compose o .env) */
export const ENTORNO = {
  puerto: Number(process.env.PORT || 3000),
  secretoSesion: process.env.SESSION_SECRET || '',
  depuracion: process.env.APP_DEBUG === '1',
  umamiId: process.env.UMAMI_WEBSITE_ID || '',
  umamiScript: process.env.UMAMI_SCRIPT_URL || '/u/script.js',
  db: {
    host: process.env.MYSQLHOST || 'localhost',
    port: Number(process.env.MYSQLPORT || 3306),
    user: process.env.MYSQLUSER || 'root',
    password: process.env.MYSQLPASSWORD || '',
    database: process.env.MYSQLDATABASE || 'mzabeats',
  },
};
