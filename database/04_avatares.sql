-- Foto de perfil de cada usuario: la clave de uno de los dibujos de
-- servidor/config.js (AVATARES). Vacía significa la imagen por defecto.
ALTER TABLE `usuarios` ADD COLUMN IF NOT EXISTS `avatar` varchar(32) DEFAULT NULL;
