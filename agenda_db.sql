-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2024 a las 02:57:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Activo','Vencido','Cancelado') DEFAULT 'Activo',
  `realizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `descripcion`, `fecha`, `hora`, `usuario_id`, `fecha_creacion`, `estado`, `realizado`) VALUES
(1, 'Cien años de soledad', 'leer mañana tempro', '2024-12-01', '03:19:00', 2, '2024-11-30 22:20:03', 'Cancelado', 0),
(3, 'Cien años de soledad', 'hacer la tarea', '2024-11-30', '20:05:00', 2, '2024-12-01 00:06:01', 'Activo', 1),
(4, 'El alquimista', 'leer', '2024-12-01', '23:13:00', 2, '2024-12-01 00:13:27', 'Activo', 0),
(5, 'El alquimista', 'kcnalfl', '2024-11-30', '20:14:00', 2, '2024-12-01 00:14:21', 'Activo', 0),
(6, 'El alquimista', 'hjfvjkfkgvj', '2024-11-30', '21:16:00', 2, '2024-12-01 00:16:19', 'Activo', 0),
(7, 'Cien años de soledad', 'vsdgvsgvs&lt;dg&lt;sdgdfg', '2024-11-30', '19:20:00', 2, '2024-12-01 00:23:29', 'Activo', 0),
(8, 'Cien años de soledad', 'vbsfhbzxhbdzfjhndfjhn&lt;dzn', '2024-11-30', '17:25:00', 2, '2024-12-01 00:25:53', 'Activo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`) VALUES
(1, 'lalla', 'pushi@gmail.com', '$2y$10$u3jElMdE7HjX2idRp0Z5q.tM3AXIdU.bQ8KoQbuCaTAmbMkymkKfa'),
(2, 'Eliazar', 'eliaz@gamil.com', '$2y$10$8Izc4rJ/xrHVB.P.xCHJP.6jLAfRNiX1umFaLPON3cZFQeLZn.iFS'),
(3, 'alcides', 'alicha@gmail.com', '$2y$10$.WJ5HtUcwkMFKUHawCsIm.mq7KWVvupygiOEuD4iyvKiOu0qGyhva'),
(4, 'buho real', 'hola@gmail.com', '$2y$10$CEEldsSTMPb3dwtJOQuSCe6znxctce/VBLAePIO4X3idxw9uv7phS');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
