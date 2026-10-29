# MzaBeats

MzaBeats es una aplicacion web dedicada a difundir bandas y artistas musicales de la provincia de Mendoza, Argentina. Permite explorar bandas por genero, registrarse, iniciar sesion y enviar propuestas para aparecer en la pagina.

## Funcionalidades

- Navegacion por los generos Indie, Pop y Rock.
- Busqueda de bandas por nombre dentro de cada genero.
- Registro e inicio de sesion de usuarios.
- "Mi cuenta": cambiar nombre, usuario y contraseña.
- Carga de bandas para usuarios con creaciones disponibles.
- Formulario "¡Quiero aparecer!" para que las bandas pidan sumarse.
- Panel de administracion: usuarios, bandas y consultas.

## Tecnologias

- PHP 8 (sin frameworks)
- Apache
- MariaDB / MySQL
- HTML y CSS
- Docker

## Estructura del proyecto

```text
frontend/                  Carpeta publica (lo unico que ve el navegador)
  index.php                Pagina principal
  generos/                 Una pagina por genero (todas usan la misma vista)
  vistas/                  Formularios, login, registro y administracion
  sesion_control/          Cierre de sesion
  css/ images/ fonts/      Estilos, imagenes y tipografias

backend/                   Codigo privado (no se puede abrir desde el navegador)
  bootstrap.php            Se incluye al principio de cada pagina: carga todo,
                           inicia la sesion y verifica el token CSRF
  config/app.php           Configuracion: roles, generos, limites de los campos
  lib/
    db.php                 Conexion y funciones para consultar la base
    auth.php               Login, sesion y permisos
    csrf.php               Proteccion de formularios
    helpers.php            Funciones generales: e(), url(), redirigir(), vista()...
  modelos/                 Una funcion por cada consulta a la base
    bandas.php
    usuarios.php
    consultas.php
  vistas/                  HTML reutilizable
    partes/                Head, menus, mensajes
    genero.php             Pagina de un genero

database/                  Estructura de la base y bandas de ejemplo
Dockerfile                 Imagen de la web (Apache + PHP)
compose.yaml               Web + base de datos para desarrollo
```

### Como esta organizada una pagina

Cada archivo de `frontend/` sigue el mismo orden:

1. `require` de `backend/bootstrap.php`.
2. Chequeo de permisos (`requerir_login()`, `requerir_admin()`...).
3. Si llego un formulario (`es_post()`): validar, guardar con una funcion de `modelos/` y redirigir.
4. Mostrar el HTML.

Las consultas SQL estan sólo en `backend/modelos/`. Las paginas nunca escriben SQL.

### Roles (columna `usuarios.estado`)

| Valor | Rol | Puede |
|---|---|---|
| 0 | Sin permisos | Iniciar sesion |
| 1 | Usuario | Cargar bandas si tiene creaciones disponibles |
| 67 | Administrador | Todo, incluido el panel de administracion |

Las constantes estan en `backend/config/app.php` (`ESTADO_ADMIN`, etc.).

### Agregar un genero nuevo

1. Agregalo en `GENEROS` en `backend/config/app.php`.
2. Copia `frontend/generos/rock.php` con el nombre del genero y cambia `$genero`.
3. Subi las imagenes a `frontend/images/<genero>/`.

El menu, el panel de administracion y el formulario de bandas lo toman solos.

## Seguridad

- Todas las consultas usan sentencias preparadas (sin inyeccion SQL).
- Todo lo que se muestra pasa por `e()` (sin XSS).
- Contraseñas con `password_hash()`. Las cuentas viejas en MD5 se actualizan solas la proxima vez que inician sesion.
- Token CSRF en todos los formularios POST.
- Bloqueo de 15 minutos después de 5 logins fallidos para un mismo usuario (o 20 desde una misma IP). En una base existente, importá `database/03_intentos_login.sql`.
- Cookies de sesion `HttpOnly` y `SameSite=Lax`; el id de sesion se renueva al iniciar sesion.
- Los permisos se verifican en el servidor, no solo escondiendo botones.
- Los errores internos van al log del servidor; el visitante ve un aviso generico. Para verlos en pantalla mientras programas, usa `APP_DEBUG=1`.

## Ejecucion con Docker Compose (recomendado)

```bash
cp .env.example .env      # y cambia las contraseñas
docker compose up --build
```

Abri `http://localhost:8080/`. La primera vez se crean las tablas y se cargan las bandas de ejemplo desde `database/`.

## Ejecucion local con XAMPP

1. Clona el proyecto dentro de `htdocs`.
2. Inicia Apache y MySQL desde el panel de XAMPP.
3. En phpMyAdmin crea la base `mzabeats` e importa `database/01_esquema.sql` y `database/02_bandas.sql`.
4. Abri la carpeta `frontend/` del proyecto, por ejemplo `http://localhost/Mzbeats/ProyectoMza/frontend/`.

Sin variables de entorno, la conexion usa:

```text
MYSQLHOST=localhost
MYSQLUSER=root
MYSQLPASSWORD=
MYSQLDATABASE=mzabeats
MYSQLPORT=3306
```

## Estadísticas de visitas (opcional)

Si definís la variable `UMAMI_WEBSITE_ID` (el id del sitio en [Umami](https://umami.is)), cada página incluye el script de estadísticas. Por defecto se carga desde `/u/script.js`; se puede cambiar con `UMAMI_SCRIPT_URL`. Sin esas variables no se agrega nada.

## Despliegue en Render u otro servidor con Docker

Construi la imagen con el `Dockerfile` y configura las variables `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE` y `MYSQLPORT`.

No subas contraseñas ni otros datos sensibles al repositorio.

## Licencia

Este proyecto se desarrolla con fines educativos y de difusion cultural.
