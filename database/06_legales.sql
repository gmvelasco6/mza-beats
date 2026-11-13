-- Términos y privacidad.
--
-- aceptaciones: qué versión de los términos y la política de privacidad
-- aceptó cada cuenta y cuándo. La aplicación sólo agrega filas, nunca las
-- cambia. Se borran junto con la cuenta.
--
-- consultas.creado_en: los pedidos de "¡Quiero aparecer!" guardan un email,
-- y la política dice que se borran a los 12 meses. Sin la fecha no había
-- forma de saber cuáles vencieron. Los que ya existían quedan con la fecha
-- de hoy.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `aceptaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `version` varchar(20) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `aceptaciones_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `consultas` ADD COLUMN IF NOT EXISTS `creado_en` timestamp NOT NULL DEFAULT current_timestamp();
