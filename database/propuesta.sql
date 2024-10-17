-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 16-10-2024 a las 00:32:13
-- Versión del servidor: 8.0.39-0ubuntu0.24.04.2
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `propuesta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `apellidos`, `telefono`) VALUES
(8, 'Oscar Cruz', 'Chavez', '7441092837'),
(9, 'Apolinar', 'Tornes Diaz', '7412931928'),
(10, 'Fernando', 'Navarrete Cortes', '7412931928'),
(11, 'Lizbet', 'Yareth', '7412931928'),
(12, 'Oscar Cruz', 'Chavez', '7441201827'),
(13, 'Yadira', 'Cruz Chavez', '74418201927'),
(15, 'Rigoberto', 'Villazana', '7412931928'),
(17, 'Elsa', 'Villazana', '7441201827'),
(18, 'Lizbet', 'Yareth', '7441201827'),
(19, 'Julian', 'Tornez Galvez', '7441031739'),
(20, 'Juana', 'Vazquez', '7451022761'),
(21, 'Yaritza', 'Cuevas', '5516181029'),
(22, 'Lizbet', 'Carrillo', '7411004371'),
(23, 'Shakira', 'Morales', '7451946513'),
(24, 'Josefa', 'Ortiz', '5518297253'),
(25, 'Benito', 'Juarez', '7335620384'),
(26, 'Rigoberto', 'Villazana', '7451043813'),
(27, 'Alma', 'Silva', '5516181029'),
(28, 'Jasmín', 'Reyes', '7441281615'),
(29, 'Camila', 'Diaz', '7331256172'),
(30, 'César', 'Cruz Chavez', '5516181029'),
(31, 'Paola', 'Shakira', '7451043812'),
(32, 'Benito', 'Jaarez', '7441027151'),
(33, 'Angelica', 'Ramirrez', '4543543536'),
(34, 'Sandra', 'Vazquez', '1231231231'),
(35, 'Juana', 'Vazquez', '1231231232'),
(36, 'Berna', 'Villazana', '5527351235'),
(37, 'Oscar Cruz', 'Chavez', '7412931928');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_habitacion`
--

CREATE TABLE `estado_habitacion` (
  `id` int NOT NULL,
  `estado` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_habitacion`
--

INSERT INTO `estado_habitacion` (`id`, `estado`) VALUES
(1, 'Disponible'),
(2, 'Ocupado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id` int NOT NULL,
  `fecha_evento` date DEFAULT NULL,
  `num_personas` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitacion`
--

CREATE TABLE `habitacion` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio_noche` decimal(10,2) DEFAULT NULL,
  `precio_renta` decimal(10,2) DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `id_tipo_habitacion` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitacion`
--

INSERT INTO `habitacion` (`id`, `nombre`, `precio_noche`, `precio_renta`, `id_estado`, `id_tipo_habitacion`) VALUES
(1, 'Habitación 1', 300.00, 1800.00, 1, 1),
(2, 'Habitación 2', 300.00, 1800.00, 1, 1),
(3, 'Habitación 3', 300.00, 1800.00, 1, 1),
(4, 'Habitación 4', 300.00, 1800.00, 1, 1),
(5, 'Habitación 5', 300.00, 1800.00, 1, 1),
(6, 'Habitación 6', 300.00, 1800.00, 1, 1),
(7, 'Habitación 7', 300.00, 1800.00, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_habitacion` int DEFAULT NULL,
  `fecha_llegada` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `total_adultos` int DEFAULT NULL,
  `total_ninos` int DEFAULT NULL,
  `total_pagar` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `id_cliente`, `id_habitacion`, `fecha_llegada`, `fecha_salida`, `total_adultos`, `total_ninos`, `total_pagar`) VALUES
(6, 8, 1, '2024-09-17', '2024-09-19', 3, 2, 600.00),
(7, 9, 1, '2024-09-18', '2024-09-20', 3, 2, 650.00),
(8, 10, 2, '2024-09-18', '2024-09-19', 3, 1, 350.00),
(9, 11, 3, '2024-09-18', '2024-09-19', 3, 1, 350.00),
(10, 12, 4, '2024-09-18', '2024-10-19', 3, 1, 1850.00),
(11, 13, 7, '2024-09-18', '2024-09-20', 3, 1, 650.00),
(13, 15, 1, '2024-09-27', '2024-09-29', 1, 1, 600.00),
(15, 17, 7, '2024-09-29', '2024-09-30', 1, 1, 300.00),
(16, 18, 7, '2024-10-18', '2024-10-19', 1, 1, 300.00),
(17, 19, 7, '2024-10-01', '2024-10-02', 1, 0, 300.00),
(18, 20, 6, '2024-10-05', '2024-10-07', 1, 2, 600.00),
(19, 21, 3, '2024-09-25', '2024-09-26', 2, 2, 300.00),
(20, 22, 3, '2024-09-20', '2024-09-22', 3, 2, 650.00),
(21, 23, 1, '2024-10-10', '2024-10-11', 2, 2, 300.00),
(22, 24, 2, '2024-10-09', '2024-10-11', 3, 1, 650.00),
(23, 25, 7, '2024-10-10', '2024-10-14', 2, 0, 1800.00),
(24, 26, 2, '2024-10-30', '2024-10-31', 1, 1, 300.00),
(25, 27, 5, '2024-10-28', '2024-10-29', 1, 1, 300.00),
(26, 28, 2, '2024-10-21', '2024-10-22', 1, 1, 300.00),
(27, 29, 3, '2024-10-18', '2024-10-20', 1, 1, 600.00),
(28, 30, 5, '2024-10-10', '2024-10-12', 3, 1, 650.00),
(29, 31, 2, '2024-10-19', '2024-10-20', 3, 2, 350.00),
(30, 32, 1, '2024-10-13', '2024-10-14', 3, 1, 350.00),
(31, 33, 5, '2024-10-13', '2024-10-14', 3, 1, 350.00),
(32, 34, 2, '2024-10-13', '2024-10-14', 3, 1, 350.00),
(33, 35, 6, '2024-10-13', '2024-10-14', 1, 2, 300.00),
(34, 36, 1, '2024-10-20', '2024-10-21', 1, 0, 300.00),
(35, 37, 4, '2024-10-20', '2024-10-22', 11, 2, 1050.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(1, 'Recepcionista'),
(2, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_habitacion`
--

CREATE TABLE `tipo_habitacion` (
  `id` int NOT NULL,
  `tipo` enum('renta','noches') COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_habitacion`
--

INSERT INTO `tipo_habitacion` (`id`, `tipo`) VALUES
(1, 'renta'),
(2, 'noches');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_rol` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `id_rol`) VALUES
(1, 'Lizbet', 'lizbet@gmail.com', '$2y$10$KU7BiKVsCuUbO6KJ5GBrNONltAgPQk.OPwctwfaj1hhXsRXil/u4e', 1),
(3, 'Oscar', 'oscar@gmail.com', '$2y$10$Mdu0NzU/7uXnYsrnKSpty.EG1KD9ilNvIVK3cFjChTBw3wZDIUxCO', 2),
(4, 'Lizbet6798', 'carrillo@gmail.com', '$2y$10$JA4qEFNsnfxL0/VnWBW73Obxo.NK4WyXvPmBMyymR488gIQEBkbeW', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_habitacion`
--
ALTER TABLE `estado_habitacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_ibfk_1` (`id_cliente`);

--
-- Indices de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_tipo_habitacion` (`id_tipo_habitacion`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_habitacion` (`id_habitacion`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_habitacion`
--
ALTER TABLE `tipo_habitacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `estado_habitacion`
--
ALTER TABLE `estado_habitacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `habitacion`
--
ALTER TABLE `habitacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_habitacion`
--
ALTER TABLE `tipo_habitacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`);

--
-- Filtros para la tabla `habitacion`
--
ALTER TABLE `habitacion`
  ADD CONSTRAINT `habitacion_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado_habitacion` (`id`),
  ADD CONSTRAINT `habitacion_ibfk_2` FOREIGN KEY (`id_tipo_habitacion`) REFERENCES `tipo_habitacion` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_habitacion`) REFERENCES `habitacion` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
