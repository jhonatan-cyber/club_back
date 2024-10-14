-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2024 a las 14:47:49
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
  `hora_asistencia` time NOT NULL DEFAULT current_timestamp(),
  `fercha_asistencia` date NOT NULL DEFAULT current_timestamp(),
  `usuario_id` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `hora_asistencia`, `fercha_asistencia`, `usuario_id`) VALUES
(1, '09:11:50', '2024-10-12', '3'),
(2, '09:18:45', '2024-10-12', '3'),
(3, '09:54:02', '2024-10-12', '3'),
(4, '02:20:54', '2024-10-13', '8'),
(5, '08:22:27', '2024-10-14', '3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_apertura` int(11) NOT NULL,
  `monto_apertura` int(10) NOT NULL,
  `monto_caja` int(10) NOT NULL,
  `monto_trasferencia` int(10) DEFAULT NULL,
  `usuario_id_cierre` int(11) DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `estado` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Wiski', 'Todas las masrcas', 1, '2024-09-26 22:53:16', NULL, NULL),
(4, 'Ron', 'Todas las mascar', 1, '2024-09-29 23:21:44', NULL, NULL),
(5, 'Champañas', 'Todas la masrcas', 1, '2024-09-29 23:48:10', NULL, NULL);

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
(21, '7191', b'0', '2024-09-27 07:00:12'),
(22, '8270', b'0', '2024-09-27 10:53:25'),
(23, '5233', b'0', '2024-09-27 10:54:05'),
(24, '7480', b'0', '2024-09-27 18:59:16'),
(25, '8332', b'0', '2024-09-28 02:59:39'),
(26, '7503', b'0', '2024-09-29 23:16:58'),
(27, '6182', b'0', '2024-10-02 19:55:58'),
(28, '1379', b'0', '2024-10-04 03:38:32'),
(29, '1234', b'0', '2024-10-04 11:48:33'),
(30, '3128', b'0', '2024-10-10 20:56:01'),
(31, '3267', b'0', '2024-10-11 16:23:08'),
(32, '1293', b'0', '2024-10-12 08:37:54'),
(33, '5696', b'0', '2024-10-12 09:09:07'),
(34, '6852', b'0', '2024-10-12 09:10:24'),
(35, '5328', b'0', '2024-10-12 19:00:03'),
(36, '1864', b'0', '2024-10-13 02:42:56'),
(37, '4733', b'0', '2024-10-13 02:47:31'),
(38, '8806', b'0', '2024-10-13 22:31:32'),
(39, '8007', b'0', '2024-10-14 06:43:48'),
(40, '2127', b'1', '2024-10-14 06:46:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id_comision` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comisiones`
--

INSERT INTO `comisiones` (`id_comision`, `usuario_id`, `venta_id`, `monto`, `estado`, `fecha_crea`, `fecha_mod`) VALUES
(1, 3, 2, 20000, 1, '2024-10-14 06:47:10', NULL),
(2, 8, 2, 20000, 1, '2024-10-14 06:47:10', NULL),
(3, 8, 3, 8000, 1, '2024-10-14 06:48:57', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `sueldo` int(10) NOT NULL,
  `fonasa` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedidos`
--

CREATE TABLE `detalle_pedidos` (
  `id_detalle_pedido` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio` int(10) NOT NULL,
  `comision` int(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedidos`
--

INSERT INTO `detalle_pedidos` (`id_detalle_pedido`, `pedido_id`, `producto_id`, `precio`, `comision`, `cantidad`, `subtotal`, `fecha_crea`) VALUES
(1, 1, 2, 20000, 8000, 2, 40000, '2024-10-14 03:23:05'),
(2, 2, 2, 20000, 8000, 2, 40000, '2024-10-14 03:29:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id_detalle_venta` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio` int(10) NOT NULL,
  `comision` int(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `sub_total` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id_detalle_venta`, `venta_id`, `producto_id`, `precio`, `comision`, `cantidad`, `sub_total`, `fecha_crea`) VALUES
(1, 1, 2, 20000, 8000, 2, 40000, '2024-10-14 03:53:27'),
(2, 2, 1, 120000, 40000, 1, 120000, '2024-10-14 06:47:10'),
(3, 3, 2, 20000, 8000, 1, 20000, '2024-10-14 06:48:57');

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
(1, 3, '2024-10-12 09:11:40', 1),
(2, 8, '2024-10-13 02:20:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `total_comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `codigo`, `usuario_id`, `cliente_id`, `subtotal`, `total`, `total_comision`, `fecha_crea`, `estado`) VALUES
(1, 'NXZNHZVN', 8, 3, 40000, 40000, 16000, '2024-10-14 03:23:05', 0),
(2, 'Y7Y7PSZV', 8, 1, 40000, 40000, 16000, '2024-10-14 03:29:59', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio` int(10) NOT NULL,
  `comision` int(10) NOT NULL,
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

INSERT INTO `productos` (`id_producto`, `codigo`, `nombre`, `categoria_id`, `precio`, `comision`, `descripcion`, `fecha_crea`, `fecha_mod`, `fecha_baja`, `estado`, `foto`) VALUES
(1, 'JQ12HVBK', 'prueva', 5, 120000, 40000, 'asdmsdkasdlakjsd', '2024-10-11 17:07:42', NULL, NULL, 1, 'default.png'),
(2, 'TON1KWDP', 'asdasd', 1, 20000, 8000, 'asd', '2024-10-11 17:08:05', NULL, NULL, 1, 'default.png');

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
(3, '152565565', 'Pepe', 'Castro', 'Av/ Colon', '75412563', 'pepe@gmail.com', '$2y$10$/R3qJnU.mHMiZC0tWLBPCuty6OC.0xL1ceNp7X/4VpLu.Cpqz/fba', 4, '66f4e4d9cbb42.webp', 1, '2024-09-14 02:50:47', '2024-09-26 00:36:41', NULL),
(8, '52525252', 'Maria', 'Perez', 'Av/colon', '75415252', 'maria@gmail.com', '$2y$10$hiCFhdi1ofDkGAZQDI76ZenytawuGjDk4xJ2X3zOD4wgKkEAJb3a2', 4, 'default.png', 1, '2024-10-13 02:20:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_venta`
--

CREATE TABLE `usuario_venta` (
  `id_usuario_venta` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_venta`
--

INSERT INTO `usuario_venta` (`id_usuario_venta`, `usuario_id`, `venta_id`, `fecha_crea`) VALUES
(1, 8, 1, '2024-10-14 03:59:56'),
(2, 3, 2, '2024-10-14 06:47:10'),
(3, 8, 2, '2024-10-14 06:47:10'),
(4, 8, 3, '2024-10-14 06:48:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `total` int(10) NOT NULL DEFAULT current_timestamp(),
  `total_comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `codigo`, `cliente_id`, `metodo_pago`, `total`, `total_comision`, `fecha_crea`, `estado`) VALUES
(1, 'Y7Y7PSZV', 1, 'Efectivo', 40000, 16000, '2024-10-14 03:53:27', 1),
(2, 'PZRCFY36', 1, 'Efectivo', 120000, 40000, '2024-10-14 06:47:10', 1),
(3, 'COEVX4OO', 1, 'Efectivo', 20000, 8000, '2024-10-14 06:48:57', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id_caja`);

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
-- Indices de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  ADD PRIMARY KEY (`id_comision`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id_detalle_pedido`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle_venta`);

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
-- Indices de la tabla `usuario_venta`
--
ALTER TABLE `usuario_venta`
  ADD PRIMARY KEY (`id_usuario_venta`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `codigos`
--
ALTER TABLE `codigos`
  MODIFY `id_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id_comision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario_venta`
--
ALTER TABLE `usuario_venta`
  MODIFY `id_usuario_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
