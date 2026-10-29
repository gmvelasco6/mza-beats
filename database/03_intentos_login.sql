-- Intentos de login fallidos (para bloquear a quien prueba contraseñas).
-- En una base que ya existe, importá este archivo una vez.

CREATE TABLE IF NOT EXISTS `intentos_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_fecha` (`usuario`, `creado_en`),
  KEY `ip_fecha` (`ip`, `creado_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
