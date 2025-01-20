-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2025 a las 17:04:17
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anticipos`
--

CREATE TABLE `anticipos` (
  `id_anticipo` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anticipos`
--

INSERT INTO `anticipos` (`id_anticipo`, `usuario_id`, `monto`, `fecha_crea`, `fecha_mod`, `estado`) VALUES
(1, 3, 500, '2025-01-18 19:56:58', '2025-01-18 19:56:58', 0),
(2, 3, 600, '2025-01-18 20:02:45', '2025-01-18 20:02:45', 0),
(3, 2, 500, '2025-01-18 20:09:58', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int(11) NOT NULL,
  `hora_asistencia` time NOT NULL,
  `fercha_asistencia` date NOT NULL,
  `usuario_id` varchar(25) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id_asistencia`, `hora_asistencia`, `fercha_asistencia`, `usuario_id`, `estado`) VALUES
(1, '15:56:41', '2025-01-18', '2', 1),
(2, '17:07:16', '2025-01-18', '3', 1),
(3, '20:45:40', '2025-01-17', '3', 1),
(4, '21:45:40', '2025-01-16', '3', 1),
(5, '02:05:39', '2025-01-19', '3', 1),
(6, '02:18:53', '2025-01-19', '2', 1),
(7, '01:52:25', '2025-01-20', '4', 1),
(8, '01:54:08', '2025-01-20', '5', 1),
(9, '02:39:17', '2025-01-20', '3', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_apertura` int(11) NOT NULL,
  `monto_apertura` int(10) NOT NULL,
  `ventas_realizadas` int(11) NOT NULL DEFAULT 0,
  `monto_cierre` int(10) NOT NULL DEFAULT 0,
  `monto_trasferencia` int(10) NOT NULL DEFAULT 0,
  `usuario_id_cierre` int(11) NOT NULL DEFAULT 0,
  `fecha_cierre` datetime DEFAULT NULL,
  `estado` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id_caja`, `fecha_apertura`, `usuario_id_apertura`, `monto_apertura`, `ventas_realizadas`, `monto_cierre`, `monto_trasferencia`, `usuario_id_cierre`, `fecha_cierre`, `estado`) VALUES
(1, '2025-01-19 21:34:11', 1, 500, 2, 5600, 0, 0, NULL, 1);

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
(1, 'Champaña', 'Vino espumoso ', 1, '2025-01-16 08:54:59', '2025-01-16 08:55:13', '2025-01-16 08:55:09'),
(2, 'Tequila', 'Licor destilado', 1, '2025-01-16 08:57:25', NULL, NULL);

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
(1, 1010101010, 'Cliente', 'Genérico', '77777777', '2025-01-16 08:13:08', NULL, NULL, b'1'),
(2, 10541058, 'Calos', 'Flores', '67909084', '2025-01-16 08:25:13', '2025-01-16 08:26:54', '2025-01-16 08:26:46', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `id_codigo` int(11) NOT NULL DEFAULT 1,
  `codigo` varchar(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos`
--

INSERT INTO `codigos` (`id_codigo`, `codigo`, `fecha_crea`, `estado`) VALUES
(1, '4299', '2025-01-13 10:47:53', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisiones`
--

CREATE TABLE `comisiones` (
  `id_comision` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL DEFAULT 0,
  `servicio_id` int(11) NOT NULL DEFAULT 0,
  `monto` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comisiones`
--

INSERT INTO `comisiones` (`id_comision`, `venta_id`, `servicio_id`, `monto`, `estado`, `fecha_crea`, `fecha_mod`) VALUES
(1, 3, 0, 5000, 1, '2025-01-19 21:37:10', NULL),
(2, 4, 0, 5000, 1, '2025-01-19 21:47:45', NULL),
(3, 5, 0, 5000, 1, '2025-01-20 01:55:17', NULL),
(4, 6, 0, 5000, 1, '2025-01-20 01:59:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id_cuenta` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `servicio_id` int(11) DEFAULT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `total_comision` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_comisiones`
--

CREATE TABLE `detalle_comisiones` (
  `id_detalle_comision` int(11) NOT NULL,
  `comision_id` int(11) NOT NULL,
  `chica_id` int(11) NOT NULL,
  `comision` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_comisiones`
--

INSERT INTO `detalle_comisiones` (`id_detalle_comision`, `comision_id`, `chica_id`, `comision`, `estado`) VALUES
(1, 1, 3, 5000, 1),
(2, 2, 3, 5000, 1),
(3, 3, 3, 5000, 1),
(4, 4, 2, 5000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_cuentas`
--

CREATE TABLE `detalle_cuentas` (
  `id_detalle_cuenta` int(11) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio` int(10) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_devoluciones`
--

CREATE TABLE `detalle_devoluciones` (
  `id_detalle_devolucion` int(11) NOT NULL,
  `devolucion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `monto` int(11) NOT NULL
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_propinas`
--

CREATE TABLE `detalle_propinas` (
  `id_detalle_propina` int(11) NOT NULL,
  `propina_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_propinas`
--

INSERT INTO `detalle_propinas` (`id_detalle_propina`, `propina_id`, `usuario_id`, `monto`, `fecha_crea`, `estado`) VALUES
(1, 1, 4, 400, '2025-01-20 01:55:17', 1),
(2, 1, 5, 400, '2025-01-20 01:55:17', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_servicios`
--

CREATE TABLE `detalle_servicios` (
  `id_detalle_servicio` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_servicios`
--

INSERT INTO `detalle_servicios` (`id_detalle_servicio`, `usuario_id`, `servicio_id`, `fecha_crea`) VALUES
(1, 3, 1, '2025-01-18 19:34:56'),
(2, 3, 2, '2025-01-19 21:15:49');

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
(1, 1, 2, 2000, 5000, 1, 2000, '2025-01-19 21:34:59'),
(2, 2, 2, 2000, 5000, 1, 2000, '2025-01-19 21:35:15'),
(3, 3, 2, 2000, 5000, 1, 2000, '2025-01-19 21:37:10'),
(4, 4, 2, 2000, 5000, 1, 2000, '2025-01-19 21:47:45'),
(5, 5, 2, 2000, 5000, 1, 2000, '2025-01-20 01:55:17'),
(6, 6, 2, 2000, 5000, 1, 2000, '2025-01-20 01:59:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id_devolucion` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `pieza_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones_ventas`
--

CREATE TABLE `devoluciones_ventas` (
  `id_devolucion_venta` int(11) NOT NULL,
  `chica_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas_extras`
--

CREATE TABLE `horas_extras` (
  `id_hora_extra` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `hora` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horas_extras`
--

INSERT INTO `horas_extras` (`id_hora_extra`, `usuario_id`, `hora`, `monto`, `fecha_crea`, `estado`) VALUES
(1, 3, 5, 600, '2025-01-18 17:10:03', 1);

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
(1, 2, '2025-01-18 15:56:41', 1),
(2, 3, '2025-01-18 17:07:16', 0),
(3, 4, '2025-01-20 01:52:25', 1),
(4, 5, '2025-01-20 01:54:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `codigo` varchar(15) NOT NULL,
  `mesero_id` int(11) NOT NULL,
  `chica_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `subtotal` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `total_comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piezas`
--

CREATE TABLE `piezas` (
  `id_pieza` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` int(11) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_mod` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `piezas`
--

INSERT INTO `piezas` (`id_pieza`, `nombre`, `precio`, `estado`, `fecha_crea`, `fecha_mod`) VALUES
(1, 'Pieza 1', 600, 1, '2025-01-18 19:32:31', '2025-01-18 19:32:42'),
(2, 'Pieza 2', 400, 1, '2025-01-18 19:33:47', NULL);

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
(1, 'YZ7V6SW8', 'asdasd', 1, 6000, 8000, 'asdasd', '2025-01-19 21:32:31', NULL, NULL, 1, 'default.png'),
(2, 'XZC0DLCS', 'yughj', 2, 2000, 5000, 'sdf', '2025-01-19 21:32:49', NULL, NULL, 1, 'default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propinas`
--

CREATE TABLE `propinas` (
  `id_propina` int(11) NOT NULL,
  `propina` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `fecha_mod` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propinas`
--

INSERT INTO `propinas` (`id_propina`, `propina`, `fecha`, `estado`, `fecha_mod`) VALUES
(1, 800, '2025-01-20', 1, NULL);

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
(1, 'Administrador', 1, '2025-01-13 08:05:35', NULL, NULL),
(2, 'Cajero', 1, '2025-01-13 08:05:35', NULL, NULL),
(3, 'Chica', 1, '2025-01-13 10:45:44', NULL, NULL),
(4, 'Mesero', 1, '2025-01-16 08:36:11', '2025-01-16 09:38:19', '2025-01-16 09:38:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `pieza_id` int(11) NOT NULL,
  `precio_pieza` int(10) NOT NULL,
  `precio_servicio` int(10) NOT NULL,
  `iva` int(10) NOT NULL,
  `sub_total` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `tiempo` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `codigo`, `cliente_id`, `pieza_id`, `precio_pieza`, `precio_servicio`, `iva`, `sub_total`, `total`, `tiempo`, `metodo_pago`, `fecha_crea`, `estado`) VALUES
(1, '1GM4I4XA', 2, 1, 600, 6000, 0, 6000, 6600, 2, 'Efectivo', '2025-01-18 19:34:56', 0),
(2, 'CCC0A4SL', 2, 2, 400, 10000, 0, 10000, 10400, 1, 'Efectivo', '2025-01-19 21:15:49', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `run` varchar(50) NOT NULL,
  `nick` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `estado_civil` varchar(50) NOT NULL,
  `afp` varchar(100) NOT NULL,
  `aporte` int(11) NOT NULL,
  `sueldo` int(11) NOT NULL,
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

INSERT INTO `usuarios` (`id_usuario`, `run`, `nick`, `nombre`, `apellido`, `direccion`, `telefono`, `estado_civil`, `afp`, `aporte`, `sueldo`, `correo`, `password`, `rol_id`, `foto`, `estado`, `fecha_crea`, `fecha_mod`, `fecha_baja`) VALUES
(1, '10571705', 'Administrador', 'Jhonatan', 'Ancasi Flores', 'S/N', '72419112', 'Soltero', 'AFP', 0, 0, 'admin@gmail.com', '$2y$10$A5lXeGNg5ZO2aIV1BoNYZeuW/lkAtCeBozgsOmIX4NAFTwhLG/qIG', 1, 'default.png', 1, '2025-01-13 08:15:50', NULL, NULL),
(2, '1525363', 'Jamin', 'Jasmin', 'Perez', 'S/n', '7541412', 'Casada', 'Afp', 800, 8000, 'jasmin@gmail.com', '$2y$10$nb4YOC8MZ7LA1DGiC63bO.pMSIERYF0v/Nhlyl7ZNY0qGkGyKmeeC', 3, 'default.png', 1, '2025-01-13 10:46:53', '2025-01-16 08:50:27', '2025-01-16 08:50:21'),
(3, '654654654', 'Tamara', 'Tamara', 'Perez', 'Asdasd', '654613213', 'Casdasd', 'Fsdfsd', 800, 8000, 'tamara@gmail.com', '$2y$10$0v4hKX.TlaB8S3DQ3SlOYOexGq0O66YksKFQOqIHWg2OEVnoDTDmK', 3, 'default.png', 1, '2025-01-18 17:06:53', NULL, NULL),
(4, '41414525', 'Pepe', 'Pedro', 'Perez', 'Asdasd', '6846513', 'Casado', 'Afp', 800, 8000, 'cajero@gmail.com', '$2y$10$hZ2tRTMyIEwt8PM/IaKtMujW1AQI2PM.WCtoYM98hCuSMRlkDFx7S', 2, 'default.png', 1, '2025-01-20 01:52:00', NULL, NULL),
(5, '654654', 'Mesero', 'Juan', 'Lopez', 'Asdasd', '4654654', 'Soltero', 'Afp', 900, 9000, 'mesero@gmail.com', '$2y$10$DGoke9dTAUgu7yMH2eZPHOCVYB6W9KFLBRpRTyLLTmvxSyRw.t98S', 4, 'default.png', 1, '2025-01-20 01:53:44', NULL, NULL);

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
(1, 3, 3, '2025-01-19 21:37:10'),
(2, 3, 4, '2025-01-19 21:47:45'),
(3, 3, 5, '2025-01-20 01:55:17'),
(4, 2, 6, '2025-01-20 01:59:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `pieza_id` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `total` int(10) NOT NULL,
  `total_comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `codigo`, `cliente_id`, `pieza_id`, `metodo_pago`, `total`, `total_comision`, `fecha_crea`, `estado`) VALUES
(1, 'SHHTXAXW', 1, 0, 'Efectivo', 2400, 5000, '2025-01-19 21:34:59', 1),
(2, '8A14ATFR', 1, 0, 'Efectivo', 2400, 5000, '2025-01-19 21:35:15', 1),
(3, '88G16VH3', 1, 0, 'Efectivo', 2400, 5000, '2025-01-19 21:37:10', 1),
(4, '6971ARIR', 1, 0, 'Efectivo', 2200, 5000, '2025-01-19 21:47:45', 1),
(5, 'FR6P78ZD', 1, 0, 'Efectivo', 2600, 5000, '2025-01-20 01:55:17', 1),
(6, 'AABT6VR0', 1, 0, 'Efectivo', 3000, 5000, '2025-01-20 01:59:30', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anticipos`
--
ALTER TABLE `anticipos`
  ADD PRIMARY KEY (`id_anticipo`);

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
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id_cuenta`);

--
-- Indices de la tabla `detalle_comisiones`
--
ALTER TABLE `detalle_comisiones`
  ADD PRIMARY KEY (`id_detalle_comision`);

--
-- Indices de la tabla `detalle_cuentas`
--
ALTER TABLE `detalle_cuentas`
  ADD PRIMARY KEY (`id_detalle_cuenta`);

--
-- Indices de la tabla `detalle_devoluciones`
--
ALTER TABLE `detalle_devoluciones`
  ADD PRIMARY KEY (`id_detalle_devolucion`);

--
-- Indices de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  ADD PRIMARY KEY (`id_detalle_pedido`);

--
-- Indices de la tabla `detalle_propinas`
--
ALTER TABLE `detalle_propinas`
  ADD PRIMARY KEY (`id_detalle_propina`);

--
-- Indices de la tabla `detalle_servicios`
--
ALTER TABLE `detalle_servicios`
  ADD PRIMARY KEY (`id_detalle_servicio`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle_venta`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id_devolucion`);

--
-- Indices de la tabla `devoluciones_ventas`
--
ALTER TABLE `devoluciones_ventas`
  ADD PRIMARY KEY (`id_devolucion_venta`);

--
-- Indices de la tabla `horas_extras`
--
ALTER TABLE `horas_extras`
  ADD PRIMARY KEY (`id_hora_extra`);

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
-- Indices de la tabla `piezas`
--
ALTER TABLE `piezas`
  ADD PRIMARY KEY (`id_pieza`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `propinas`
--
ALTER TABLE `propinas`
  ADD PRIMARY KEY (`id_propina`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

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
-- AUTO_INCREMENT de la tabla `anticipos`
--
ALTER TABLE `anticipos`
  MODIFY `id_anticipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id_comision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_comisiones`
--
ALTER TABLE `detalle_comisiones`
  MODIFY `id_detalle_comision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_cuentas`
--
ALTER TABLE `detalle_cuentas`
  MODIFY `id_detalle_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_devoluciones`
--
ALTER TABLE `detalle_devoluciones`
  MODIFY `id_detalle_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_pedidos`
--
ALTER TABLE `detalle_pedidos`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_propinas`
--
ALTER TABLE `detalle_propinas`
  MODIFY `id_detalle_propina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_servicios`
--
ALTER TABLE `detalle_servicios`
  MODIFY `id_detalle_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id_devolucion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones_ventas`
--
ALTER TABLE `devoluciones_ventas`
  MODIFY `id_devolucion_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_extras`
--
ALTER TABLE `horas_extras`
  MODIFY `id_hora_extra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `piezas`
--
ALTER TABLE `piezas`
  MODIFY `id_pieza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `propinas`
--
ALTER TABLE `propinas`
  MODIFY `id_propina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario_venta`
--
ALTER TABLE `usuario_venta`
  MODIFY `id_usuario_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `regenerar_codigo` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-01-09 01:51:53' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DECLARE nuevo_codigo INT;

    SET nuevo_codigo = FLOOR(1000 + (RAND() * 9000));
    DELETE FROM codigos;
    INSERT INTO codigos (codigo) VALUES (nuevo_codigo);
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
