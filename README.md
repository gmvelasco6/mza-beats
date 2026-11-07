# MzaBeats

MzaBeats es una aplicación web dedicada a difundir bandas y artistas musicales de la provincia de Mendoza, Argentina. Permite explorar bandas por género, registrarse, iniciar sesión y enviar propuestas para aparecer en la página.

## Funcionalidades

- Navegación por los géneros Indie, Pop y Rock.
- Búsqueda de bandas por nombre dentro de cada género, sin recargar la página.
- Registro e inicio de sesión de usuarios.
- "Mi cuenta": cambiar nombre, usuario y contraseña.
- Carga de bandas para usuarios con creaciones disponibles.
- Formulario "¡Quiero aparecer!" para que las bandas pidan sumarse.
- Panel de administración: usuarios, bandas y consultas.
- Animaciones al cargar y al hacer scroll, que respetan la opción "reducir movimiento".

## Tecnologías

- JavaScript en todo el sitio: Node.js 22 y Express 5 en el servidor.
- Plantillas EJS: el servidor arma el HTML, así que el sitio funciona sin JavaScript en el navegador.
- GSAP y ScrollTrigger para las animaciones.
- MariaDB / MySQL.
- Docker.

## Estructura del proyecto

```text
servidor/                  Código privado (no se puede abrir desde el navegador)
  index.js                 Arranca el servidor
  app.js                   Qué hace cada pedido, en orden, antes de llegar a una ruta
  config.js                Roles, géneros, límites de los campos, variables de entorno
  generar-hash.js          Genera el hash de una contraseña (para scripts de mantenimiento)
  lib/
    db.js                  Conexión y funciones para consultar la base
    sesion.js              Sesión, login y permisos
    claves.js              Generar y comprobar contraseñas
    seguridad.js           Cabeceras de seguridad y protección CSRF
    mensajes.js            Avisos que sobreviven a una redirección
    texto.js               Lectura de formularios y funciones de texto
  modelos/                 Una función por cada consulta a la base
  rutas/                   Qué responde cada dirección del sitio
    sitio.js               Inicio, géneros y "¡Quiero aparecer!"
    cuenta.js              Login, registro, "Mi cuenta" y cambio de clave
    bandas.js              Carga de bandas
    admin.js               Panel de administración
    viejas.js              Redirige las direcciones .php de la versión anterior
  vistas/                  Plantillas EJS de cada página
    partes/                Head, menús, mensajes, pie

publico/                   Lo único que ve el navegador
  css/                     style, genero-style y php-style son el diseño de cada tipo de página;
                           comun tiene lo compartido, animaciones todo lo que se mueve
                           y celular la tipografía y los espacios de pantallas angostas
  js/
    previo.js              Corre antes de pintar: evita que lo animado se vea antes de tiempo
    sitio.js               Confirmar antes de borrar y buscador sin recarga
    animaciones.js         Todas las animaciones
  fonts/ images/           Tipografías e imágenes

pruebas/
  unidad/                  Reglas que no necesitan base de datos
  integracion/             Recorren el sitio de punta a punta
  semilla.sql              Cuentas de prueba
  prueba.env               Valores de la base descartable

database/                  Estructura de la base y bandas de ejemplo
Dockerfile                 Imagen de la web
compose.yaml               Web + base de datos para desarrollo
compose.prueba.yaml        Agrega las cuentas de prueba
```

### Cómo está organizada una ruta

Cada archivo de `servidor/rutas/` sigue el mismo orden:

1. Chequeo de permisos (`requerirLogin`, `requerirAdmin`...).
2. Si llegó un formulario: leerlo con `campo()`, validar, guardar con una función de `modelos/` y redirigir.
3. Mostrar la plantilla con `res.render()`.

Las consultas SQL están sólo en `servidor/modelos/`. Las rutas nunca escriben SQL.

### Roles (columna `usuarios.estado`)

| Valor | Rol | Puede |
|---|---|---|
| 0 | Sin permisos | Iniciar sesión |
| 1 | Usuario | Cargar bandas si tiene creaciones disponibles |
| 67 | Administrador | Todo, incluido el panel de administración |

Las constantes están en `servidor/config.js` (`ESTADO_ADMIN`, etc.).

### Agregar un género nuevo

1. Agregalo en `GENEROS` en `servidor/config.js`.
2. Subí las imágenes a `publico/images/<genero>/`.
3. Agregá el fondo de su sección en `publico/css/style.css`.

El menú, las páginas, el panel de administración y el formulario de bandas lo toman solos.

### Agregar una animación

Las plantillas marcan qué se anima con atributos, sin escribir JavaScript:

| Atributo | Qué hace |
|---|---|
| `data-revelar="izquierda"`, `"derecha"` o `"abajo"` | Entra desde ese lado al aparecer en pantalla |
| `data-parallax` | Su fondo se mueve con el scroll (sólo en pantallas grandes) |

Lo demás está en `publico/js/animaciones.js`, con una función por cada efecto. Sin JavaScript o con "reducir movimiento" activado, la página se ve completa y quieta.

## Seguridad

- Contraseñas con bcrypt. Las cuentas viejas con MD5 se actualizan solas la próxima vez que entran.
- Consultas preparadas: no hay inyección SQL.
- Todo lo que muestra una plantilla con `<%= %>` se escapa. `<%- %>` se usa sólo para incluir otras plantillas.
- Token CSRF en cada formulario y cookies `HttpOnly` y `SameSite=Lax`.
- Política de contenido (CSP) que sólo permite recursos del propio sitio.
- Bloqueo temporal del login tras varios intentos fallidos.

## Cómo levantarlo

```bash
cp .env.example .env      # y cambiá las contraseñas y SESSION_SECRET
docker compose up --build
```

Abrí <http://localhost:8080>. La primera vez se crean las tablas con los archivos de `database/`. La tabla `sesiones` la crea la aplicación sola.

Para trabajar sin Docker, con una base propia: `npm ci` y `npm run dev`, con las variables `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE` y `SESSION_SECRET` definidas.

Para ver estadísticas con Umami, definí `UMAMI_WEBSITE_ID` (y `UMAMI_SCRIPT_URL` si no está en `/u/script.js`).

## Pruebas

```bash
npm test                  # reglas, sin base de datos
```

Las de integración usan una base descartable:

```bash
docker compose -p mzabeats-prueba --env-file pruebas/prueba.env -f compose.yaml -f compose.prueba.yaml up --build -d
BASE_URL=http://localhost:8083 npm run test:integracion
docker compose -p mzabeats-prueba --env-file pruebas/prueba.env -f compose.yaml -f compose.prueba.yaml down -v
```

Empezá siempre con una base nueva: algunas pruebas dependen de lo que guardaron las anteriores.
