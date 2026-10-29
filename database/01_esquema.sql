-- Estructura de la base de datos de MzaBeats (MariaDB).
-- Docker Compose la carga sola la primera vez. En XAMPP: creá la base
-- `mzabeats` en phpMyAdmin e importá este archivo y después 02_bandas.sql.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `bandas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen_principal` varchar(255) NOT NULL,
  `imagen_fondo` varchar(255) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `consultas` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `razon` text NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `usuario` text NOT NULL,
  `clave` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `cantidad_creaciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

ALTER TABLE `bandas`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`) USING HASH;
ALTER TABLE `bandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
