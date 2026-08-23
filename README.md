# MzaBeats

MzaBeats es una aplicacion web dedicada a difundir bandas y artistas musicales de la provincia de Mendoza, Argentina. Permite explorar bandas por genero, registrarse, iniciar sesion y enviar propuestas para aparecer en la pagina.

## Funcionalidades

- Navegacion por los generos Indie, Pop y Rock.
- Registro e inicio de sesion de usuarios.
- Visualizacion y administracion de la cuenta.
- Carga de bandas para usuarios autorizados.
- Panel de administracion para usuarios con permisos.
- Busqueda de bandas por genero.
- Cierre de sesion.

## Tecnologias

- PHP 8.2
- Apache
- MySQL/MariaDB
- HTML y CSS
- Docker

## Estructura del proyecto

```text
backend/
  bd/                  Conexion a la base de datos
  generos/             Controladores de los generos
  login-signin/        Registro e inicio de sesion
  administrar/         Funciones de administracion
frontend/
  index.php            Pagina principal
  generos/             Paginas de cada genero
  vistas/              Formularios y vistas de usuario
  css/                 Hojas de estilos
  images/              Imagenes del sitio
Dockerfile             Configuracion para ejecutar con Docker
```

## Ejecucion local con XAMPP

1. Instala XAMPP con Apache, PHP y MySQL.
2. Clona o copia el proyecto dentro de `htdocs`.
3. Inicia Apache y MySQL desde el panel de XAMPP.
4. Crea una base de datos llamada `mzabeats`.
5. Revisa las tablas necesarias para usuarios, bandas y generos.
6. Abre la aplicacion en:

```text
http://localhost/Mzbeats/ProyectoMza/frontend/
```

La conexion local usa estos valores por defecto:

```text
MYSQLHOST=localhost
MYSQLUSER=root
MYSQLPASSWORD=
MYSQLDATABASE=mzabeats
MYSQLPORT=3306
```

## Ejecucion con Docker

Construye la imagen desde la raiz del proyecto:

```bash
docker build -t mzabeats .
```

Ejecuta el contenedor configurando las variables de conexion:

```bash
docker run --name mzabeats -p 8080:80 \
  -e MYSQLHOST=tu-host \
  -e MYSQLUSER=tu-usuario \
  -e MYSQLPASSWORD=tu-contrasena \
  -e MYSQLDATABASE=mzabeats \
  -e MYSQLPORT=3306 \
  mzabeats
```

Luego visita `http://localhost:8080/`.

## Despliegue en Render

El proyecto incluye un `Dockerfile` que configura Apache con `frontend/` como raiz publica. Para desplegarlo en Render:

1. Conecta el repositorio de GitHub.
2. Selecciona el entorno Docker.
3. Configura las variables de entorno de MySQL:
   `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE` y `MYSQLPORT`.
4. Inicia el despliegue.

No subas contrasenas ni otros datos sensibles al repositorio. Configuralos como variables de entorno en Render.

## Licencia

Este proyecto se desarrolla con fines educativos y de difusion cultural.
