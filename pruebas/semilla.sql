-- Cuentas de prueba. Todas tienen la clave "clave-de-prueba".
-- El hash $2y$ lo generó PHP y el de "viejo" es MD5: prueban que las cuentas
-- creadas con la versión anterior del sitio siguen pudiendo entrar.
SET NAMES utf8mb4;

INSERT INTO `usuarios` (`nombre`, `usuario`, `clave`, `estado`, `cantidad_creaciones`) VALUES
('Admin de prueba', 'admin-prueba', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 67, 0),
('Cuenta vieja', 'viejo', '9168d0f4dd7f22dea897d6ad28498529', 1, 0),
('Sin permisos', 'sin-permisos', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 0, 3),
('Puede cargar', 'cargador', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 1, 1),
('Para bloquear', 'bloqueable', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 1, 0),
('Para cambiar', 'cambiante', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 1, 0),
('Para eliminar', 'descartable', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 1, 0),
('Se va sola', 'se-va', '$2y$10$XLwadZPmEKgg5lcj8f6LqughG8jnHk5HeYRC/tR1UwGHJSeS.FDmO', 1, 0);
