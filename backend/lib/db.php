<?php
/**
 * Conexión a la base de datos y funciones para hacer consultas.
 *
 * Todas las consultas pasan por db_consulta(), que usa sentencias preparadas:
 * los valores van aparte del SQL, así que no hay riesgo de inyección SQL.
 *
 *   db_filas('SELECT * FROM bandas WHERE genero = ?', 's', [$genero]);
 *
 * El segundo parámetro indica el tipo de cada valor: s = texto, i = entero.
 */

/**
 * Lee una variable de entorno. Los nombres son los mismos que usaba
 * conexion_bd.php, así que la configuración de Docker/Render no cambia.
 */
function variable_entorno(string $nombre, string $porDefecto): string
{
    $valor = $_ENV[$nombre] ?? $_SERVER[$nombre] ?? getenv($nombre);
    return ($valor === false || $valor === null || $valor === '') ? $porDefecto : (string) $valor;
}

/** Devuelve la conexión, creándola la primera vez que se usa. */
function db(): mysqli
{
    static $conexion = null;

    if ($conexion === null) {
        // Cualquier error de MySQL se convierte en excepción (no se ignora en silencio)
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $conexion = new mysqli(
            variable_entorno('MYSQLHOST', 'localhost'),
            variable_entorno('MYSQLUSER', 'root'),
            variable_entorno('MYSQLPASSWORD', ''),
            variable_entorno('MYSQLDATABASE', 'mzabeats'),
            (int) variable_entorno('MYSQLPORT', '3306')
        );
        $conexion->set_charset('utf8mb4');
    }

    return $conexion;
}

/** Prepara y ejecuta una consulta con sus parámetros. */
function db_consulta(string $sql, string $tipos = '', array $parametros = []): mysqli_stmt
{
    $sentencia = db()->prepare($sql);
    if ($tipos !== '') {
        $sentencia->bind_param($tipos, ...$parametros);
    }
    $sentencia->execute();
    return $sentencia;
}

/** Devuelve todas las filas del resultado como arrays asociativos. */
function db_filas(string $sql, string $tipos = '', array $parametros = []): array
{
    return db_consulta($sql, $tipos, $parametros)->get_result()->fetch_all(MYSQLI_ASSOC);
}

/** Devuelve la primera fila del resultado, o null si no hay ninguna. */
function db_fila(string $sql, string $tipos = '', array $parametros = []): ?array
{
    return db_consulta($sql, $tipos, $parametros)->get_result()->fetch_assoc();
}

/** Ejecuta un INSERT / UPDATE / DELETE y devuelve cuántas filas cambiaron. */
function db_ejecutar(string $sql, string $tipos = '', array $parametros = []): int
{
    return db_consulta($sql, $tipos, $parametros)->affected_rows;
}

/** Ejecuta varias operaciones juntas: o se guardan todas, o ninguna. */
function db_transaccion(callable $operaciones)
{
    db()->begin_transaction();
    try {
        $resultado = $operaciones();
        db()->commit();
        return $resultado;
    } catch (Throwable $error) {
        db()->rollback();
        throw $error;
    }
}
