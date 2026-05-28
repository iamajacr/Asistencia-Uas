-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2026 a las 21:41:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ceneval_2026`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aspirante2`
--

CREATE TABLE `aspirante2` (
  `id` int(200) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `aula` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `asiento` varchar(100) NOT NULL,
  `folio` varchar(100) NOT NULL,
  `activo` varchar(100) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `version` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aspirante2`
--

INSERT INTO `aspirante2` (`id`, `nombre`, `aula`, `correo`, `asiento`, `folio`, `activo`, `carrera`, `version`) VALUES
(1, 'ARELLANO PARENTE JAN ABEL', '3', 'jan.abel.parente@ENT.com', '1', '6425175951', '1', 'li', '1'),
(1, 'ARELLANO PARENTE JAN ABEL', '8', 'jan.abel.parente@ENT.com', '1', '2160195424', '1', 'lisi', '1'),
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `m_registro_asistencias`
--

CREATE TABLE `m_registro_asistencias` (
  `id` int(11) NOT NULL,
  `folio` varchar(100) NOT NULL,
  `m_aula` varchar(100) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aspirante2`
--
ALTER TABLE `aspirante2`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `m_registro_asistencias`
--
ALTER TABLE `m_registro_asistencias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aspirante2`
--
ALTER TABLE `aspirante2`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=600;

--
-- AUTO_INCREMENT de la tabla `m_registro_asistencias`
--
ALTER TABLE `m_registro_asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
