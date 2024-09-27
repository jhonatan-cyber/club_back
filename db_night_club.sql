-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-09-2024 a las 13:34:53
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
-- Base de datos: `db_night_club`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getUsuarios` ()   SELECT U.*, R.nombre AS rol
FROM usuarios AS U
JOIN roles AS R ON U.rol_id = R.id_rol WHERE U.estado = 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login` (IN `correo` VARCHAR(255))   SELECT U.id_usuario, U.run, U.nombre, U.apellido, U.correo, U.password, U.foto, U.estado, R.nombre AS rol
    FROM usuarios AS U
    JOIN roles AS R ON U.rol_id = R.id_rol 
    WHERE U.correo = correo AND U.estado = 1
    LIMIT 1$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `fercha_asistencia` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `fercha_asistencia`, `usuario_id`) VALUES
(1, '2024-09-27 04:38:06', '3'),
(2, '2024-09-27 05:53:16', '3'),
(3, '2024-09-27 07:01:54', '6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `estado`, `fecha_crea`, `fecha_mod`, `fecha_baja`) VALUES
(1, 'Cervezas', 'Todas las marcas ', 1, '2024-09-14 20:42:30', '2024-09-23 13:51:22', NULL),
(2, 'Tequilas', 'Todas las marcas', 1, '2024-09-23 13:37:24', NULL, NULL),
(3, 'Wiski', 'Todas las masrcas', 1, '2024-09-26 22:53:16', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `run` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_elim` datetime DEFAULT NULL,
  `estado` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `run`, `nombre`, `apellido`, `telefono`, `fecha_crea`, `fecha_mod`, `fecha_elim`, `estado`) VALUES
(1, 1111111111, 'Cliente', 'Generico', '7777777', '2024-09-11 15:39:09', '2024-09-13 18:09:47', NULL, b'1'),
(2, 15263625, 'Pepe', 'Perez', '754122536', '2024-09-13 17:07:04', NULL, NULL, b'1'),
(3, 564654654, 'Asdfasdf', 'Asdfasdf', '234234234', '2024-09-13 17:25:55', NULL, NULL, b'1'),
(4, 65465251, 'Asdgsdfg', 'Sfdgsdfg', '8447', '2024-09-13 17:30:15', NULL, NULL, b'1'),
(5, 567567, 'Fghdfghdfg', 'Fdghjfghj', '67678678', '2024-09-13 17:47:51', NULL, '2024-09-13 18:09:06', b'0'),
(6, 2147483647, 'Rgfcgdfgdfghdfghdfgh', 'Sdfgsdfgsd', '546456456', '2024-09-13 18:04:25', '2024-09-13 18:09:27', NULL, b'1'),
(7, 456456, 'Dfdfghdfgh', 'Dfghdfgh', '56676', '2024-09-13 18:06:39', NULL, NULL, b'1'),
(8, 353453453, 'Dfasdf', 'Asdfasdf', '5675675687', '2024-09-14 22:14:30', NULL, NULL, b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `id_codigo` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `estado` bit(1) NOT NULL DEFAULT b'1',
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos`
--

INSERT INTO `codigos` (`id_codigo`, `codigo`, `estado`, `fecha_crea`) VALUES
(1, '4774', b'0', '2024-09-11 18:21:42'),
(2, '4853', b'0', '2024-09-14 16:12:04'),
(3, '9831', b'0', '2024-09-14 16:14:08'),
(4, '2432', b'0', '2024-09-14 22:14:03'),
(5, '6455', b'0', '2024-09-14 22:18:26'),
(6, '8336', b'0', '2024-09-14 22:53:15'),
(7, '9810', b'0', '2024-09-14 22:59:19'),
(8, '6214', b'0', '2024-09-23 12:17:27'),
(9, '8288', b'0', '2024-09-23 13:17:03'),
(10, '9134', b'0', '2024-09-23 14:12:10'),
(11, '3057', b'0', '2024-09-23 17:54:14'),
(12, '8343', b'0', '2024-09-24 17:26:56'),
(13, '9969', b'0', '2024-09-24 18:17:37'),
(14, '5797', b'0', '2024-09-24 18:17:38'),
(15, '5275', b'0', '2024-09-25 02:26:53'),
(16, '2755', b'0', '2024-09-25 03:47:59'),
(17, '8332', b'0', '2024-09-25 20:28:00'),
(18, '3007', b'0', '2024-09-26 12:59:44'),
(19, '6619', b'0', '2024-09-26 21:39:57'),
(20, '8377', b'0', '2024-09-27 04:36:35'),
(21, '7191', b'1', '2024-09-27 07:00:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id_detalle_pedido` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `comision` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id_detalle_pedido`, `pedido_id`, `producto_id`, `precio`, `comision`, `cantidad`, `subtotal`, `fecha_crea`) VALUES
(1, 1, 1, 30000.00, 10000.00, 1, 30000.00, '2024-09-27 07:03:19'),
(2, 2, 6, 20000.00, 8000.00, 1, 20000.00, '2024-09-27 07:05:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logins`
--

CREATE TABLE `logins` (
  `id_login` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logins`
--

INSERT INTO `logins` (`id_login`, `usuario_id`, `last_login`, `estado`) VALUES
(1, 3, '2024-09-27 04:37:23', 1),
(2, 1, '2024-09-27 07:00:11', 0),
(3, 6, '2024-09-27 07:01:32', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `total_comision` decimal(10,2) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `codigo`, `usuario_id`, `cliente_id`, `subtotal`, `total`, `total_comision`, `fecha_crea`, `estado`) VALUES
(1, '3CFRRG00', 6, 4, 30000.00, 30000.00, 10000.00, '2024-09-27 07:03:19', 1),
(2, 'C51M2IQM', 3, 7, 20000.00, 20000.00, 8000.00, '2024-09-27 07:05:27', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `foto` varchar(255) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `codigo`, `nombre`, `categoria_id`, `precio`, `descripcion`, `fecha_crea`, `fecha_mod`, `fecha_baja`, `estado`, `foto`) VALUES
(1, 'sgsdfg', 'dfg', 1, 30000.00, 'sfgsfdg', '2024-09-25 02:30:21', '2024-09-27 05:14:36', '2024-09-25 04:04:51', 1, 'default.png'),
(2, 'Y5RCLGEL', 'martini', 1, 30000.00, 'sadasd', '2024-09-25 23:57:10', '2024-09-27 05:14:46', NULL, 1, 'default.png'),
(3, '41OQOI6Q', 'asdasd', 2, 30000.00, 'asdasd', '2024-09-27 00:43:06', '2024-09-27 05:15:19', NULL, 1, 'default.png'),
(4, '1C3806MW', 'asdsd', 3, 20000.00, 'asdasdasd', '2024-09-27 00:45:39', '2024-09-27 05:15:34', NULL, 1, 'default.png'),
(5, 'YG5WWSBZ', 'asdasdty', 3, 30000.00, 'asdasd', '2024-09-27 00:46:11', '2024-09-27 05:15:48', NULL, 1, 'default.png'),
(6, 'DD3E6FXV', 'asfsdfsdf', 1, 20000.00, 'asdasd', '2024-09-27 00:46:25', '2024-09-27 05:14:55', NULL, 1, 'default.png'),
(7, '20JLO4N4', 'dfghdfg', 1, 20000.00, 'dfgdfg', '2024-09-27 00:46:32', '2024-09-27 05:15:04', NULL, 1, 'default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `estado`, `fecha_crea`, `fecha_mod`, `fecha_baja`) VALUES
(1, 'Administrador', 1, '2024-08-27 12:37:09', NULL, NULL),
(2, 'Cajero', 1, '2024-09-13 18:20:29', NULL, NULL),
(3, 'Mesero', 1, '2024-09-13 18:20:40', '2024-09-13 18:31:56', NULL),
(4, 'Chicas', 1, '2024-09-14 22:14:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `run` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'default.png',
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `run`, `nombre`, `apellido`, `direccion`, `telefono`, `correo`, `password`, `rol_id`, `foto`, `estado`, `fecha_crea`, `fecha_mod`, `fecha_baja`) VALUES
(1, '10571705', 'Jhonatan', 'Ancasi Flores', 'Av/ colon', '72419112', 'admin@gmail.com', '$2y$10$6KuhGZzt7rIU2A3DEZvP3.DW3liysraITQfSDOOLp4hPkoBJNuTSC', 1, 'default.png', 1, '2024-09-14 02:13:35', NULL, NULL),
(2, '10575425', 'Carlos', 'Flores', 'Av/ Potosi', '74526332', 'carlos@gmail.com', '$2y$10$/Y6zk6C8gegGHiAYJ2oNe.vdTsFIfJO6pqncHD5wmQ3ZqUPhZQEmS', 2, '66e52c09ea03c.webp', 0, '2024-09-14 02:24:10', NULL, '2024-09-14 02:51:34'),
(3, '152565565', 'Pepe', 'Castro', 'Av/ Colon', '75412563', 'pepe@gmail.com', '$2y$10$/R3qJnU.mHMiZC0tWLBPCuty6OC.0xL1ceNp7X/4VpLu.Cpqz/fba', 4, '66f4e4d9cbb42.webp', 1, '2024-09-14 02:50:47', '2024-09-26 00:36:41', NULL),
(4, '1552633', 'Asdasd', 'Asdasd', 'Asdasd', '32234234', 'algo@gmail.com', '$2y$10$aWAzBRVVJ6yXRzz398X2JuIlR9ywEM9X2029qnq2p0it2rKMoT/1a', 3, '66e533bd7aed2.webp', 1, '2024-09-14 02:52:17', '2024-09-14 02:57:01', NULL),
(5, '321321321', 'Asdasdasd', 'Asdadfasd', 'Asdfasdfasdf', '32534534', 'chica@gmail.com', '$2y$10$iFqtojCPDXMsliHLOSlTAulQ/rXNNvyZaq6arAu/IybHLJuX2HZ.K', 4, '66e6434b8dd15.webp', 1, '2024-09-14 22:15:39', NULL, NULL),
(6, '6546546', 'Maria', 'Perez', 'Asdasd', '324234', 'maria@gmail.com', '$2y$10$rrDitdVMJJ6BUF/OMS5LKO/yngwVGPAb9sRjEU15gKPdUUTK36S0S', 4, '66f6907398cca.webp', 1, '2024-09-27 07:01:07', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `codigos`
--
ALTER TABLE `codigos`
  ADD PRIMARY KEY (`id_codigo`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id_detalle_pedido`);

--
-- Indices de la tabla `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id_login`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `codigos`
--
ALTER TABLE `codigos`
  MODIFY `id_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
