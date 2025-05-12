-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-05-2025 a las 20:29:07
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cifrado_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje` varchar(35) NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `mensaje`, `estado`, `fecha`) VALUES
(1, 'DEF', 1, '2025-05-06 11:34:55'),
(2, 'DEF', 1, '2025-05-06 11:35:48'),
(3, 'DEF', 1, '2025-05-06 11:37:19'),
(4, 'DEF', 1, '2025-05-06 11:37:29'),
(5, 'KROD', 1, '2025-05-06 11:37:36'),
(6, 'ABC', 2, '2025-05-06 11:38:03'),
(7, 'ABC', 2, '2025-05-06 11:38:13'),
(8, 'HOLA', 2, '2025-05-06 11:38:38'),
(9, 'KROD', 1, '2025-05-06 11:40:08'),
(10, 'KROD', 1, '2025-05-06 11:40:13'),
(11, 'KROD', 1, '2025-05-06 11:42:01'),
(12, 'KROD', 1, '2025-05-06 11:42:09'),
(13, 'DEF', 1, '2025-05-06 11:42:19'),
(14, 'ABC', 2, '2025-05-06 11:42:34'),
(15, 'KROD', 1, '2025-05-06 11:42:47'),
(16, 'KROD', 1, '2025-05-06 12:20:39'),
(17, 'KROD FRPR HVWDV', 1, '2025-05-06 12:20:49'),
(18, 'HOLA COMO ESTAS', 2, '2025-05-06 12:21:05'),
(19, 'doha', 1, '2025-05-12 14:27:39'),
(20, 'alex', 2, '2025-05-12 14:28:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
