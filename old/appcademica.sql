-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2024 a las 19:22:14
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
-- Base de datos: `appcademica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `nombre`, `apellido`, `cedula`, `correo`, `celular`, `titulo`, `fecha_de_nacimiento`, `direccion`, `contrasena`, `rol`, `estado`) VALUES
(18, 'Andres Felipe', 'Bolanos Palacios', '1193564006', 'andresbrown@gmail.com', '3214744820', 'ing sistemas', '2024-07-18', 'Carrera', '$2y$10$t9U/oCskoAcQgAYAis7I8OzmGZX/sIJLzjBNgsPspCvqWEWFaV4Em', 'admin', 'activo'),
(27, 'Felipe', 'Bolanos', '000000', 'felipe@gmail.com', '3123123222', 'ing sistemas', '2024-07-26', 'andresbrown@gmail.com', '$2y$10$t9U/oCskoAcQgAYAis7I8OzmGZX/sIJLzjBNgsPspCvqWEWFaV4Em', 'admin', 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`) VALUES
(1, 'Matemáticas'),
(2, 'Naturales'),
(3, 'Sociales'),
(7, 'Artes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclos_escolares`
--

CREATE TABLE `ciclos_escolares` (
  `id` int(11) NOT NULL,
  `nombre_ciclo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ciclos_escolares`
--

INSERT INTO `ciclos_escolares` (`id`, `nombre_ciclo`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(6, 'Uno', '2024-01-01', '2024-09-11', 'activo'),
(7, 'Dos', '2024-09-12', '2024-11-05', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `sexo` char(1) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_de_nacimiento` date NOT NULL,
  `direccion` text NOT NULL,
  `eps` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `pension` varchar(255) NOT NULL,
  `caja_comp` varchar(255) NOT NULL,
  `documentos` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` varchar(50) NOT NULL,
  `estado` enum('activo','inactivo','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `nombre`, `apellido`, `cedula`, `correo`, `celular`, `sexo`, `titulo`, `fecha_de_nacimiento`, `direccion`, `eps`, `contrasena`, `pension`, `caja_comp`, `documentos`, `fecha_registro`, `rol`, `estado`) VALUES
(5, 'Docente uno', 'Docente', '1233432', 'docente@gmail.com', '1234232323', 'M', 'ing sistemas', '2024-07-29', 'dsds', 'EPS1', '$2y$10$A/ut.tNqLM2gnJLQLkNtCuARRgCzfUYbSs2GxBC1GHJDqjeLtO.AS', 'Pension1', 'Caja1', '%PDF-1.7\n%????\n1 0 obj\n<</Type/Catalog/Pages 2 0 R/Lang(es) /StructTreeRoot 10 0 R/MarkInfo<</Marked true>>/Metadata 21 0 R/ViewerPreferences 22 0 R>>\nendobj\n2 0 obj\n<</Type/Pages/Count 1/Kids[ 3 0 R] >>\nendobj\n3 0 obj\n<</Type/Page/Parent 2 0 R/R', '2024-07-29 22:12:57', 'docentes', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_materias`
--

CREATE TABLE `docente_materias` (
  `id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `docente_materias`
--

INSERT INTO `docente_materias` (`id`, `docente_id`, `materia_id`, `grupo_id`, `fecha_asignacion`) VALUES
(1, 5, 9, 1, '2024-09-14 20:28:43'),
(2, 5, 2, 1, '2024-09-15 12:10:32'),
(3, 5, 3, 1, '2024-09-15 12:10:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `grupo` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `lugar_expedicion` varchar(255) DEFAULT NULL,
  `fecha_expedicion` date DEFAULT NULL,
  `correo` varchar(255) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `demografica` varchar(255) DEFAULT NULL,
  `eps` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `grupo_etnico` varchar(255) DEFAULT NULL,
  `acudiente` varchar(255) DEFAULT NULL,
  `numero_acudiente` varchar(20) DEFAULT NULL,
  `documentos` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `grupo`, `nombre`, `apellido`, `cedula`, `lugar_expedicion`, `fecha_expedicion`, `correo`, `celular`, `titulo`, `sexo`, `fecha_de_nacimiento`, `direccion`, `demografica`, `eps`, `estado`, `grupo_etnico`, `acudiente`, `numero_acudiente`, `documentos`, `contrasena`, `rol`) VALUES
(1, '4', 'Andres', 'Bola', '12345678', NULL, NULL, 'andres@gmail.com', '3211111111', NULL, 'M', '2000-02-11', 'andresbrown@gmail.com', NULL, 'EPS1', NULL, 'GrupoEtnico1', 'sdasd', '1222222222', NULL, '$2y$10$xDwaQSTy5JnGy478SPcPhuVPAg4X5NY0LCKFdMzOKShTrEhS6Vj.O', 'estudiante'),
(3, '2', 'felipe', 'Bolaños', '1232123432', NULL, NULL, 'dasd@gmail.com', '3211111111', NULL, 'M', '2000-02-11', 'andresddbrown@gmail.com', NULL, 'EPS1', NULL, 'GrupoEtnico1', 'sdasd', '2222222222', NULL, '$2y$10$uPmiRrT5QrsIwfm1BhorDeyf6FDO2OjasyoD.BQjKPcMTTV42mqPa', 'estudiante'),
(4, '2', 'Criston', 'Col', '145323233', NULL, NULL, 'cris@gmail.com', '3213121212', NULL, 'M', '2000-02-11', 'andres', NULL, 'EPS2', NULL, 'GrupoEtnico1', 'aasd', '1111111111', NULL, '$2y$10$j5nQwrLBeb9UIZxEo98V.e2fgBU0MvmlpyUx/YVlEdagiQjtlTIjK', 'estudiante'),
(5, '1', 'Tiriom', 'Lanister', '1192332822', NULL, NULL, 'tirion@idd.com', '3213444444', NULL, 'M', '2022-11-10', 'andres', NULL, 'EPS3', NULL, 'GrupoEtnico1', 'aasas', '2222222233', NULL, '$2y$10$p9YOdZlFz2NklZala8CdW.j8/We6qnd2GpwW0YlbDuE23GQGg7S1O', 'estudiante'),
(6, '1', 'Maria', 'test', '132434', NULL, NULL, 'mara@gmi.com', '3144343231', NULL, 'F', '2024-08-29', 'fsdfsdf', NULL, 'EPS1', NULL, 'GrupoEtnico1', 'afs', '4342342342', '/App/estudiantesdoc/documentos/Maria_test/66d12bb46bdf5-Andres Felipe Bolaños CV.pdf', '$2y$10$oNBQc8W23Tr2x5avzVwQsuAC3JubFwHgUyf6oC4I5PYMVu9L2b8WW', 'estudiante'),
(7, '1', 'Mario Bros', 'Bros', '54654', NULL, NULL, 'mariobros@gmail.com', '3433434434', NULL, 'M', '2024-08-29', 'dfsdfsf', NULL, 'EPS1', NULL, 'GrupoEtnico1', 'fsfsf', '3123123323', '/App/estudiantesdoc/documentos/Mario Bros_Bros/66d138719d3e1-Andres Felipe Bolaños CV.pdf', '$2y$10$NLdO7rL6HdqjhXbqbNK.Q.Ejfuxr0AyV2oY36Auel//JMg7pVZaum', 'estudiante'),
(10, '3', 'Maria Jose', 'Bolanos', '2432323423', NULL, NULL, 'mariajosee@gmail.com', '3222222222', NULL, 'M', '2024-09-05', 'asas', NULL, 'EPS1', NULL, 'GrupoEtnico1', 'Red', '2121211212', '/App/estudiantesdoc/documentos/Maria Jose_Bolanos/66d9d63c75957-Text.pdf', '$2y$10$7PogEBHG4GYkd8Dwtn7EZukQjGAIVF2vkRDYenhnFLGSM9bK32yJ6', 'estudiante'),
(11, '4', 'Test', 'Tests', '32323332323', NULL, NULL, 'test@test.com', '3423434343', NULL, 'M', '2024-09-12', 'Sjjasan', NULL, 'EPS1', NULL, 'GrupoEtnico2', 'asdasdasd', '2232323232', '/App/estudiantesdoc/documentos/Test_Tests/66e354f404661-Fundación Instituto Mundo Creativo.pdf', '$2y$10$46JnYb2pDARPDbShvfVC0e7ti9ZPjrEN9SUORvYdHglvXndGU2tFS', 'estudiante');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_grupo`
--

CREATE TABLE `estudiante_grupo` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `fecha_asignacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante_grupo`
--

INSERT INTO `estudiante_grupo` (`id`, `estudiante_id`, `grupo_id`, `fecha_asignacion`) VALUES
(1, 1, 4, '2024-08-09 10:50:34'),
(3, 3, 2, '2024-08-09 10:51:26'),
(4, 4, 2, '2024-08-10 21:45:00'),
(5, 5, 1, '2024-08-11 12:49:00'),
(6, 6, 1, '2024-08-29 21:17:24'),
(7, 7, 1, '2024-08-29 22:11:45'),
(11, 10, 3, '2024-09-05 11:03:08'),
(12, 11, 4, '2024-09-12 15:54:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `Codigo` varchar(50) NOT NULL,
  `nombre_grupo` varchar(255) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `nivel_id` int(11) DEFAULT NULL,
  `ciclo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `Codigo`, `nombre_grupo`, `fecha_creacion`, `estado`, `nivel_id`, `ciclo_id`) VALUES
(1, '1', 'Grupo uno', '2024-08-09 17:46:14', 'activo', 1, 6),
(2, '2', 'Grupo dos', '2024-08-09 17:47:16', 'activo', 1, 6),
(3, '3', 'Grupo tres', '2024-09-05 17:53:25', 'activo', 2, 6),
(4, '4', 'Oncea', '2024-09-12 22:52:06', 'activo', 2, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_materias`
--

CREATE TABLE `grupo_materias` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grupo_materias`
--

INSERT INTO `grupo_materias` (`id`, `grupo_id`, `materia_id`) VALUES
(17, 1, 1),
(18, 1, 2),
(19, 1, 3),
(20, 2, 1),
(21, 2, 2),
(22, 2, 3),
(23, 3, 1),
(24, 4, 1),
(25, 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `materia` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `materia`, `categoria`, `categoria_id`) VALUES
(1, 'Matematicas', '', 1),
(2, 'Estadistica', '', 1),
(3, 'Geometria', '', 1),
(4, 'Biologia', '', 2),
(5, 'Espanol', '', 7),
(6, 'Sociales', '', 3),
(7, 'ingles', '', 7),
(8, 'Contabilidad', '', 1),
(9, 'arte', '', 7),
(10, 'Testq', '', 7),
(11, 'tetsdos', '', 1),
(12, 'Testtres', '', 3),
(13, 'testcuatro', '', 2),
(14, 'testssssss', '', 1),
(15, 'UnoDosTres', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL,
  `nombre_nivel` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nombre_nivel`, `estado`) VALUES
(1, 'Primaria', 'activo'),
(2, 'Secundaria', 'activo'),
(3, 'Cursos', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_estudiantes`
--

CREATE TABLE `notas_estudiantes` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `año_académico` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notas_estudiantes`
--

INSERT INTO `notas_estudiantes` (`id`, `estudiante_id`, `materia_id`, `grupo_id`, `nota`, `observacion`, `año_académico`) VALUES
(1, 1, 2, 1, 5.00, '', '0000'),
(2, 1, 1, 1, 2.00, '', '0000'),
(3, 6, 2, 1, 2.00, '', '0000'),
(4, 6, 1, 1, 1.00, '', '0000'),
(5, 5, 2, 1, 4.00, '', '0000'),
(6, 5, 1, 1, 4.00, 'dddd', '0000'),
(44, 1, 3, 1, 1.00, '', '0000'),
(47, 6, 3, 1, 5.00, '', '0000'),
(50, 5, 3, 1, 1.00, '', '0000'),
(67, 7, 2, 1, 1.00, '', '0000'),
(68, 7, 3, 1, 1.00, '', '0000'),
(69, 7, 1, 1, 1.00, '', '0000'),
(169, 4, 1, 2, 3.00, '', '0000'),
(170, 3, 1, 2, 3.00, '', '0000'),
(171, 4, 2, 2, 3.00, '', '0000'),
(173, 3, 2, 2, 5.00, '', '0000'),
(184, 4, 3, 2, 3.00, '', '0000'),
(187, 3, 3, 2, 2.00, '', '0000'),
(244, 10, 1, 3, 2.00, '', '0000'),
(247, 11, 1, 4, 5.00, '0', '2024'),
(249, 11, 4, 4, 3.00, '0', '2024'),
(259, 1, 4, 4, 5.00, '4', '2024'),
(260, 1, 1, 4, 4.00, '4', '2024');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `estudiante_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `rol`, `admin_id`, `docente_id`, `estudiante_id`) VALUES
(2, 'Andres Felipe', 'andresbrown@gmail.com', '$2y$10$O0jDdrr7v4Dvbe6LK/UwA.wlX/B4phyKJZx75Tq4ewE5KLwI0dge6', 'admin', 18, NULL, NULL),
(4, 'Felipe', 'felipe@gmail.com', '$2y$10$S3qvXMN2ndnNaGk5Lronu.ugCEfeAs.94ft16EU4piWlXv0c6MY9O', 'admin', 27, NULL, NULL),
(11, 'Docente uno', 'docente@gmail.com', '$2y$10$A/ut.tNqLM2gnJLQLkNtCuARRgCzfUYbSs2GxBC1GHJDqjeLtO.AS', 'docentes', NULL, 5, NULL),
(18, 'Maria antoe', 'marian@gmail.com', '$2y$10$5vzXKmAoNJ6LKRHdBhQpk.7eMiJx5qELVbvYzvxvs8lgWUY6wu7c.', 'estudiante', NULL, NULL, 7),
(19, 'Marco Antonio', 'marco@gmail.com', '$2y$10$Foc8CW2cZIvYyC1ssQVZuuHHI8x19kGeyDMHwnAFLEhi0TifANMwa', 'estudiante', NULL, NULL, 8),
(20, 'Estudiante', 'estudianteuno@gmail.com', '$2y$10$wRKyhrPTuh86MPAQU00G0OmP3j7R1o09vQJHVD0leC1nvOUWdXGGS', 'estudiante', NULL, NULL, 9),
(21, 'Estudiante dos', 'estudiantedos@gmail.com', '$2y$10$zcOTZc.5kEb9SNTLRp491.O6VEhomz6RTm4zZBY3vgR4xNlos1aeu', 'estudiante', NULL, NULL, 10),
(22, 'Andres', 'andres@gmail.com', '$2y$10$xDwaQSTy5JnGy478SPcPhuVPAg4X5NY0LCKFdMzOKShTrEhS6Vj.O', 'estudiante', NULL, NULL, 1),
(24, 'felipe', 'dasd@gmail.com', '$2y$10$uPmiRrT5QrsIwfm1BhorDeyf6FDO2OjasyoD.BQjKPcMTTV42mqPa', 'estudiante', NULL, NULL, 3),
(25, 'Criston', 'cris@gmail.com', '$2y$10$j5nQwrLBeb9UIZxEo98V.e2fgBU0MvmlpyUx/YVlEdagiQjtlTIjK', 'estudiante', NULL, NULL, 4),
(26, 'Tiriom', 'tirion@idd.com', '$2y$10$p9YOdZlFz2NklZala8CdW.j8/We6qnd2GpwW0YlbDuE23GQGg7S1O', 'estudiante', NULL, NULL, 5),
(27, 'Maria', 'mara@gmi.com', '$2y$10$oNBQc8W23Tr2x5avzVwQsuAC3JubFwHgUyf6oC4I5PYMVu9L2b8WW', 'estudiante', NULL, NULL, 6),
(28, 'Mario Bros', 'mariobros@gmail.com', '$2y$10$NLdO7rL6HdqjhXbqbNK.Q.Ejfuxr0AyV2oY36Auel//JMg7pVZaum', 'estudiante', NULL, NULL, 7),
(30, 'Maria Jose', 'mariajose@gmail.com', '$2y$10$6CCQdFC94qmigExV36UiVOa3.3yHT1jigoSiIUxTco4BvK0p1vWC6', 'estudiante', NULL, NULL, 8),
(32, 'Maria Jose', 'mariajosee@gmail.com', '$2y$10$7PogEBHG4GYkd8Dwtn7EZukQjGAIVF2vkRDYenhnFLGSM9bK32yJ6', 'estudiante', NULL, NULL, 10),
(33, 'Test', 'test@test.com', '$2y$10$46JnYb2pDARPDbShvfVC0e7ti9ZPjrEN9SUORvYdHglvXndGU2tFS', 'estudiante', NULL, NULL, 11);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo_2` (`correo`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ciclos_escolares`
--
ALTER TABLE `ciclos_escolares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_ciclo` (`nombre_ciclo`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `docente_materias`
--
ALTER TABLE `docente_materias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_docente` (`docente_id`),
  ADD KEY `fk_materia` (`materia_id`),
  ADD KEY `fk_grupo` (`grupo_id`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `UC_Estudiante` (`cedula`,`correo`);

--
-- Indices de la tabla `estudiante_grupo`
--
ALTER TABLE `estudiante_grupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nivel_id` (`nivel_id`),
  ADD KEY `idx_ciclo_id` (`ciclo_id`);

--
-- Indices de la tabla `grupo_materias`
--
ALTER TABLE `grupo_materias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `materia_id` (`materia_id`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`categoria_id`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_estudiantes`
--
ALTER TABLE `notas_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estudiante_materia_grupo` (`estudiante_id`,`materia_id`,`grupo_id`),
  ADD KEY `fk_estudiante_id` (`estudiante_id`),
  ADD KEY `fk_materia_id` (`materia_id`),
  ADD KEY `fk_grupo_id` (`grupo_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `fk_docente_id` (`docente_id`),
  ADD KEY `fk_estudiante` (`estudiante_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ciclos_escolares`
--
ALTER TABLE `ciclos_escolares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `docente_materias`
--
ALTER TABLE `docente_materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `estudiante_grupo`
--
ALTER TABLE `estudiante_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `grupo_materias`
--
ALTER TABLE `grupo_materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `notas_estudiantes`
--
ALTER TABLE `notas_estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `docente_materias`
--
ALTER TABLE `docente_materias`
  ADD CONSTRAINT `fk_docente` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_grupo`
--
ALTER TABLE `estudiante_grupo`
  ADD CONSTRAINT `estudiante_grupo_ibfk_1` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `estudiante_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupo_ciclo` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclos_escolares` (`id`),
  ADD CONSTRAINT `fk_grupos_ciclo_id` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclos_escolares` (`id`),
  ADD CONSTRAINT `fk_grupos_nivel_id` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`),
  ADD CONSTRAINT `fk_nivel_grupo` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`);

--
-- Filtros para la tabla `grupo_materias`
--
ALTER TABLE `grupo_materias`
  ADD CONSTRAINT `grupo_materias_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `grupo_materias_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`);

--
-- Filtros para la tabla `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `notas_estudiantes`
--
ALTER TABLE `notas_estudiantes`
  ADD CONSTRAINT `fk_notas_estudiantes_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `fk_notas_estudiantes_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `fk_notas_estudiantes_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_docente_id` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`),
  ADD CONSTRAINT `fk_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`),
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
