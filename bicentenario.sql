-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2025 a las 09:33:44
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
-- Base de datos: `bicentenario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `Id_rol` int(11) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`Id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Comprador'),
(3, 'Organizador'),
(4, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id_usuario` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Contraseña` varchar(50) NOT NULL,
  `Telefono` int(8) NOT NULL,
  `Genero` enum('Masculino','Femenino') NOT NULL,
  `Id_pais` int(10) NOT NULL,
  `Id_ciudad` int(10) NOT NULL,
  `Rol` int(11) NOT NULL,
  `token_verificacion` varchar(255) DEFAULT NULL,
  `Verificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_usuario`, `Nombre`, `Apellido`, `Correo`, `Contraseña`, `Telefono`, `Genero`, `Id_pais`, `Id_ciudad`, `Rol`, `token_verificacion`, `Verificado`) VALUES
(2, 'Alejandra Valentina', 'Paz Villanueva', 'vpaz2237@gmail.com', '$2y$10$OZ0nC.5UMx/xv92JEvcNCeh5qAQWPRd1EGv/qjHx1Uq', 77787675, 'Femenino', 1, 1, 2, 'e2740aea4962f796b58514873c3ad978ebdc95a189a308bb84d2027682e3d2b20bf79e92b5a9b66f7e30389a04fd5f2f37c4', 0),
(3, 'andres', 'ssssss', 'a@gmail.com', '$2y$10$C2N5tipfux22G.hAqiHcT.NXG4jXxh.XkfOtoqT3GmV', 122121212, 'Masculino', 16, 74, 3, '74f1866a73583d01d26be0361d330a726f3cb4068061212f5da98c2e838e7487267cc27ab05c4b8d093300cce8cb9d39f444', 0),
(4, 'dsd', 'gfgdg', 'asd@gmail.com', '$2y$10$4piGdB3RrWjWIwWVnhS3qOGax62hlcNJOpGbPFkH2qV', 87878787, 'Femenino', 8, 36, 4, '1775150fb5cf60e1370f817098ccf91a1c8af4def37a2428f44f62c93e345f56bf3e6dd680dfe3190162a1bdaf1f51d3a5b9', 0),
(5, 'Mauricio Lionel', 'Escalante Cueto', 'haldirescalante@gmail.com', '$2y$10$rbMxkj9WBJEpQy6.oIFNle0sq7Y8ht1CwkMw1CRO8Wk', 70115477, 'Masculino', 1, 1, 1, NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`Id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id_usuario`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD KEY `Rol` (`Rol`),
  ADD KEY `Id_pais` (`Id_pais`),
  ADD KEY `Id_ciudad` (`Id_ciudad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `Id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Rol`) REFERENCES `rol` (`Id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`Id_ciudad`) REFERENCES `ciudad` (`Id_ciudad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`Id_pais`) REFERENCES `pais` (`Id_pais`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
