-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2025 a las 14:54:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mzabeats`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bandas`
--

CREATE TABLE `bandas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen_principal` varchar(255) NOT NULL,
  `imagen_fondo` varchar(255) NOT NULL,
  `genero` varchar(50) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `bandas`
--

INSERT INTO `bandas` (`id`, `nombre`, `descripcion`, `imagen_principal`, `imagen_fondo`, `genero`, `creado_en`) VALUES
(5, 'Verona', 'Verona es una banda mendocina formada en 2017 en Mendoza, Argentina. Su formación incluye a Leandro Villanueva (guitarra y voz), Federico Calderón (sintetizadores y coros), Martín Villanueva (batería) y Guillermo Martelossi (bajo).\r\nSu estilo combina synth-pop, influencias de punk, new wave, post-punk, Manchester y electrónica, creando un sonido alternativo con atmósferas bailables y letras introspectivas.\r\nEn diciembre de 2021 lanzaron su álbum debut homónimo, tras publicar singles como “Vapor”, “Mantua” y “Alquimia”. La banda se ha consolidado como parte de la nueva ola de propuestas alternativas de Mendoza, construyendo un “universo de canciones” que mezcla energía y reflexión en sus presentaciones y grabaciones.', 'images/pop/02.png', 'images/pop/02-bg.png', 'pop', '2025-11-03 14:55:00'),
(6, 'Candi Viosch', 'Candi Viosch es una joven cantante, compositora y artista integral que, aunque nació en La Plata, Argentina, eligió radicarse en Mendoza para desarrollar su proyecto musical. Desde niña mostró interés por la música y las artes escénicas, estudiando música desde los 15 años y luego arte dramático, lo que le permitió construir un perfil artístico multidisciplinario.\r\nSu propuesta combina estética visual y sonora muy cuidada, mezclando pop, urbano, R&B, boleros, trap, reguetón y dancehall, lo que le permite explorar distintos géneros sin encasillarse. En su álbum debut, Agridulce Dualidad, se refleja su deseo de jugar con contrastes y vincular sonido, imagen y estilo de manera integral.\r\n\r\nEn los shows en vivo, Candi Viosch busca generar experiencias que van más allá de la música: su formación teatral se refleja en la puesta en escena, vestuario y forma de interactuar con el público. Sus lanzamientos recientes, como el single “Bandido”, muestran un registro audaz donde combina cultura pop, estética visual llamativa y un sonido que mezcla elementos latino‑urbanos con electrónica.\r\nEn resumen, Candi Viosch es una artista emergente que ha encontrado en Mendoza su base creativa, construyendo una identidad versátil que combina música, teatro y expresión visual, destacándose dentro de la escena local.', 'images/pop/03.png', 'images/pop/03-bg.png', 'pop', '2025-11-03 14:56:07'),
(7, 'Gauchito Club', 'Gauchito Club es una banda formada en Mendoza alrededor de 2015, inicialmente como un proyecto entre los hermanos Gabriel y Sasha Nazar, que luego se expandió con otros músicos para completar una formación sólida.\r\nSu estilo se caracteriza por la fusión de géneros como rock, funk, cumbia, bolero, tango, reggae, electrónica e indie tropical. Esta mezcla le ha permitido crear una identidad propia vinculada a lo festivo y popular, pero con un cuidado enfoque estético.\r\nEn cuanto a su trayectoria discográfica, destacan tres álbumes importantes: Guandanara (2018), que introduce su universo musical; El Camino de la Libertad (2021), que amplía su alcance y madurez sonora; y Vulnerable (2024), que muestra una mirada más profunda y emocional de la banda.\r\nGauchito Club también es reconocido por sus presentaciones en vivo, que han agotado entradas en Mendoza y en escenarios internacionales, convirtiendo cada show en una celebración colectiva donde la energía, el baile y la conexión con el público son protagonistas.', 'images/indie/04.png', 'images/indie/04-bg.png', 'indie', '2025-11-03 14:57:07'),
(9, 'Santo Tabú', 'Santo Tabú es una banda de rock formada en la ciudad de Mendoza, Argentina, a fines del año 2008. Sus integrantes originales se organizaron como un trío conformado por Iván Procheret en guitarra y voz, Leo Cortés en bajo y coros, y Pablo Peinado en batería y programación. Desde sus comienzos la agrupación apostó por una intensa puesta en escena y una identidad sonora cuidada.\r\nLa propuesta musical de Santo Tabú combina elementos de rock británico, pop oscuro (dark), funk y elementos experimentales. Sus letras giran en torno a lo emocional, lo social y lo introspectivo, reflejando momentos de contraste entre lo “santo” y lo “tabú” como razón de su nombre. El grupo define su música como una búsqueda por unir lo elegante y lo crudo, lo luminoso y lo oscuro.\r\nLa banda lanzó su primer álbum homónimo en torno a 2010, con un sonido potente y conceptual, dando paso luego a su segundo disco Cristal Ámbar alrededor de 2012, donde adoptaron arreglos más elaborados y una paleta sonora más amplia, incluyendo cuerdas y tonos menos estrictamente rockeros. Con estos trabajos, Santo Tabú logró posicionarse en la escena local y regional, recorriendo ciudades de Argentina y Chile.\r\nTras una pausa en su actividad por motivos personales, el grupo retornó al menos en 2016 con nueva formación ampliada y retomando su trayectoria con shows en vivo. En su regreso, buscó redefinir parte de su sonido y expandir el formato de banda trío a un conjunto más amplio, incorporando sintetizadores y nuevos matices.', 'images/rock/02.png', 'images/rock/02-bg.png', 'rock', '2025-11-03 15:16:23'),
(10, 'Chantas', 'Chantas es una banda de rock originaria de Mendoza, Argentina, cuya formación se remonta al año 2004, aunque su arranque formal como agrupación se consolida hacia el 2011. Su propuesta musical se basa en una fusión que combina el espíritu clásico del rock and roll argentino —inspirado en las raíces del barrio y del fogón— con matices de folk, balada y tango, logrando una identidad propia que se nutre tanto de lo local como de lo esencial del género.\r\nDesde sus primeros trabajos, Chantas construyó un recorrido en la escena local que incluyó la presentación de su primer álbum en 2017, titulado Hace Tiempo. A partir de allí, la banda comenzó a ganar visibilidad, participando en escenarios de Mendoza y en festivales, compartiendo cartel con otros referentes del rock y la música alternativa. Esta etapa marcó un punto de inflexión que le permitió dar saltos importantes en su trayectoria.\r\nEn los años siguientes, Chantas lanzó su segundo disco, Un Gesto, el cual consolidó su estilo: canciones con letras que evocan la vida cotidiana del barrio, la fraternidad, el paso del tiempo y las decisiones que definen los gestos personales. En este material se aprecia una mayor madurez sonora, con arreglos que van más allá del trío roca tradicional, incorporando texturas que remiten al tango o la balada sin perder la garra del rock.\r\nEn vivo, Chantas se ha destacado por generar un vínculo auténtico con su público, acercándose a los escenarios con una energía de fraternidad, de comunidad. Su sonido, basado en guitarras, coros y ritmos que recuerdan al rock callejero, se complementa con la calidez de sus letras y la identidad de barrio que los rodea, logrando que sus conciertos se sientan como reuniones de amigos donde la música es el lazo que une.', 'images/rock/03.png', 'images/rock/03-bg.png', 'rock', '2025-11-03 15:17:08'),
(11, 'Brut4l', 'Brut4l es una banda de rock originaria de Mendoza, Argentina, que se formó a finales de 2012 en formato de power trío. Está conformada por Leonardo Magri (guitarra y voz), Fernando Mansur (batería) y Mauricio Guiñazú (bajo).\r\nDesde sus comienzos, Brut4l buscó hacer un rock contundente, con influencias claras del hard rock, pero sin cerrarse en un solo estilo: la banda se describe como “licuadora” en cuanto a géneros, mezclando elementos del metal, el rock pesado, la canción directa y letras con contenido social.\r\nUno de sus primeros registros fue un EP promocional titulado Impulso (2013), seguido de un DVD en vivo llamado 50 años de Amor Brutal (2014), que permitía mostrar su energía en directo. Luego lanzaron su primer álbum de estudio importante, Siervo, con el que profundizaron en temáticas como la familia, las adicciones, la violencia de género y la trata de personas.\r\nEn sus presentaciones la banda es reconocida por su potencia y compromiso: han participado en grandes festivales en Mendoza y otras provincias, han girado por Chile y tienen presencia activa dentro de la escena del rock pesado mendocino. Su público los identifica como una de las voces jóvenes que sigue llevando el rock con actitud.', 'images/rock/04.png', 'images/rock/04-bg.png', 'rock', '2025-11-03 15:17:56'),
(12, 'Chancho Va', 'Chancho Va es una banda de rock originaria de Mendoza, Argentina, que se formó en el año 2000. Desde sus inicios se propuso construir un sonido propio dentro del rock urbano, con bases rítmicas potentes, guitarras distorsionadas y una voz que no rehúye al reclamo social. Su formación inicial estaba conformada por Leandro “Canario” Vilariño (voz y guitarra), Rubén Castagnolo (bajo) y Leonardo Grasetto (batería). A lo largo de su trayectoria, Chancho Va ha mantenido una producción independiente, lo que le ha permitido sostener control sobre su sonido y su identidad. Su música combina agresividad de guitarras y baterías con letras que remiten tanto al entorno urbano de Mendoza como a temas de justicia, identidad y crítica social. Este enfoque le ganó reconocimiento dentro de la escena local como uno de los grupos más contundentes de su provincia. En sus conciertos, Chancho Va se distingue por la energía que despliega sobre el escenario: el compromiso del cantante y la contundencia del trío original (y sus sucesivas formaciones) logran que el público participe con fuerza, generando un clima de comunión rockera. Además, la banda ha sabido adaptarse al cambio de miembros y contextos, renovándose sin perder su estilo esencial. Con más de dos décadas de existencia, Chancho Va ha editado varios discos y EPs que muestran su evolución: desde sus comienzos más crudos hasta trabajos más elaborados en producción, pero siempre fieles al espíritu urbano‑rockero que los define. En resumen, Chancho Va representa una de las voces más firmes del rock mendocino, con carácter propio, discurso y potencia sonora.', 'images/rock/05.png', 'images/rock/05-bg.png', 'rock', '2025-11-03 15:18:36'),
(29, 'opa', 'somos los opas', 'images/rock/06.png', 'images/rock/06-bg.png', 'rock', '2025-11-06 23:58:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `razon` text NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`id`, `nombre`, `razon`, `email`) VALUES
(1, 'a', 'a', 'a@a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `usuario` text NOT NULL,
  `clave` varchar(32) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `cantidad_creaciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `clave`, `estado`, `cantidad_creaciones`) VALUES
(9, 'Administrador', 'Velasco', 'Admin', '674f3c2c1a8a6f90461e8a66fb5550ba', 67, 10),
(10, 'Gonzalo', 'Velasco', 'Gonzalito', '81dc9bdb52d04dc20036dbd8313ed055', 0, 0),
(11, 'Francisco', 'Basigalup', 'Francis', 'f38fef4c0e4988792723c29a0bd3ca98', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bandas`
--
ALTER TABLE `bandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`),
  ADD UNIQUE KEY `usuario` (`usuario`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bandas`
--
ALTER TABLE `bandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
