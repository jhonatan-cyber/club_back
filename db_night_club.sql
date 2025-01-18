-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-01-2025 a las 12:23:54
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id_apertura` int(11) NOT NULL,
  `monto_apertura` int(10) NOT NULL,
  `ventas_realizadas` int(11) NOT NULL,
  `monto_cierre` int(10) NOT NULL,
  `monto_trasferencia` int(10) NOT NULL,
  `usuario_id_cierre` int(11) NOT NULL,
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
(3, 'Chica', 1, '2025-01-13 10:45:44', NULL, NULL);

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
(2, '1525363', 'Jamin', 'Jasmin', 'Perez', 'S/n', '7541412', 'Casada', 'Afp', 800, 8000, 'jasmin@gmail.com', '$2y$10$nb4YOC8MZ7LA1DGiC63bO.pMSIERYF0v/Nhlyl7ZNY0qGkGyKmeeC', 3, 'default.png', 1, '2025-01-13 10:46:53', NULL, NULL);

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
  `total` int(10) NOT NULL DEFAULT current_timestamp(),
  `total_comision` int(10) NOT NULL,
  `fecha_crea` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id_anticipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comisiones`
--
ALTER TABLE `comisiones`
  MODIFY `id_comision` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_comisiones`
--
ALTER TABLE `detalle_comisiones`
  MODIFY `id_detalle_comision` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_detalle_propina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_servicios`
--
ALTER TABLE `detalle_servicios`
  MODIFY `id_detalle_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_hora_extra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `piezas`
--
ALTER TABLE `piezas`
  MODIFY `id_pieza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `propinas`
--
ALTER TABLE `propinas`
  MODIFY `id_propina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario_venta`
--
ALTER TABLE `usuario_venta`
  MODIFY `id_usuario_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

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
