-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-08-2025 a las 12:31:11
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `huellitasdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$v68vu7A/ihN79V1D/G1DLOIs0.kr6XQH8XLMite7jrdk.MQ6d3Yp.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos_perros`
--

CREATE TABLE `fotos_perros` (
  `id` int NOT NULL,
  `perro_id` int DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perros`
--

CREATE TABLE `perros` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `edad` int DEFAULT NULL,
  `raza` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tamano` enum('pequeño','mediano','grande') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('disponible','adoptado') COLLATE utf8mb4_general_ci DEFAULT 'disponible',
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int NOT NULL,
  `perro_id` int DEFAULT NULL,
  `nombre_usuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_general_ci,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','en_proceso','finalizada') COLLATE utf8mb4_general_ci DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `fotos_perros`
--
ALTER TABLE `fotos_perros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perro_id` (`perro_id`);

--
-- Indices de la tabla `perros`
--
ALTER TABLE `perros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perro_id` (`perro_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `fotos_perros`
--
ALTER TABLE `fotos_perros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perros`
--
ALTER TABLE `perros`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `fotos_perros`
--
ALTER TABLE `fotos_perros`
  ADD CONSTRAINT `fotos_perros_ibfk_1` FOREIGN KEY (`perro_id`) REFERENCES `perros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`perro_id`) REFERENCES `perros` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
