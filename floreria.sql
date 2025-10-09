-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Oct 03, 2025 at 02:24 AM
-- Server version: 11.8.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `floreria`
--

-- --------------------------------------------------------

--
-- Table structure for table `detalle`
--

CREATE TABLE `detalle` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `detalle`
--

INSERT INTO `detalle` (`id`, `id_pedido`, `id_producto`, `cantidad`) VALUES
(1, 3, 1, 6),
(2, 4, 1, 2),
(4, 5, 1, 2),
(5, 5, 5, 1),
(6, 6, 5, 2),
(7, 6, 7, 1),
(8, 7, 5, 2),
(9, 7, 7, 1),
(10, 8, 5, 2),
(11, 8, 7, 1),
(12, 9, 5, 2),
(13, 9, 7, 1),
(14, 10, 5, 2),
(15, 10, 7, 1),
(16, 11, 7, 2),
(17, 12, 7, 2),
(18, 13, 7, 2),
(19, 14, 7, 2),
(21, 17, 1, 3),
(22, 17, 5, 1),
(23, 18, 1, 3),
(27, 21, 6, 1),
(28, 21, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `iniciar`
--

CREATE TABLE `iniciar` (
  `usuario` varchar(20) DEFAULT NULL,
  `contrasena` varchar(250) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `iniciar`
--

INSERT INTO `iniciar` (`usuario`, `contrasena`, `id`) VALUES
('prueba', '$2y$10$hKYfUpCSV33N1xVqPKydL.hiRVxA5JqcwUyARfJN3BaW5zajs.UeW', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `estado_pago` enum('pendiente','pagado','cancelado') DEFAULT 'pendiente',
  `costo_envio` decimal(10,2) DEFAULT 0.00,
  `fecha_envio` date DEFAULT NULL,
  `lugar` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `recibe` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `pedido`
--

INSERT INTO `pedido` (`id`, `id_usuario`, `metodo_pago`, `estado_pago`, `costo_envio`, `fecha_envio`, `lugar`, `descripcion`, `recibe`) VALUES
(1, 1, 'tarjeta', 'pagado', 50.00, '2025-07-05', 'Calle Ficticia 123', 'Pedido de cumpleaños', 'Ana Martínez'),
(2, 1, 'tarjeta', 'pagado', 50.00, '2025-11-05', 'Calle Ficticia 123', 'Pedido de cumpleaños', 'Ana valeria'),
(3, 1, 'efectivo', 'pendiente', 50.00, '2025-08-21', 'Lucio Cabañas #323', 'cubo', 'valeria'),
(4, 2, 'transferencia', 'pagado', 50.00, '2025-08-21', 'Lucio Cabañas #323', 'cubo', 'Valeria'),
(5, 2, 'transferencia', 'pagado', 50.00, '2025-08-21', 'Lucio Cabañas #323', 'cubo', 'Valeria'),
(6, 2, 'transferencia', 'pagado', 50.00, '2025-08-12', 'Cuarto frío', 'Ramo y rosas', 'Ana Pérez'),
(7, 2, 'transferencia', 'pagado', 50.00, '2025-08-12', 'Cuarto frío', 'Ramo y rosas', 'Ana Pérez'),
(8, 2, 'transferencia', 'pagado', 50.00, '2025-08-12', 'Cuarto frío', 'Ramo y rosas', 'Ana Pérez'),
(9, 2, 'transferencia', 'pagado', 50.00, '2025-08-12', 'Cuarto frío', 'Ramo y rosas', 'Ana Pérez'),
(10, 2, 'transferencia', 'pagado', 50.00, '2025-08-12', 'Cuarto frío', 'Ramo y rosas', 'Ana Pérez'),
(11, 3, 'efectivo', 'pendiente', 50.00, '2025-11-12', 'lucio cabanas', 'casa verde', 'ana'),
(12, 5, 'efectivo', 'pendiente', 50.00, '2025-08-21', 'Lucio Cabañas #323', 'cubo', 'valeria'),
(13, 5, 'efectivo', 'pendiente', 50.00, '2025-08-21', 'Lucio Cabañas #323', 'casa verde', 'valeria'),
(14, 1, 'efectivo', 'pagado', 50.00, '2025-09-11', 'lucio', 'casa verde', 'fernanda'),
(15, 1, 'tarjeta', 'pagado', 50.00, '2025-07-05', 'Calle Ficticia 123', 'Pedido de cumpleaños', 'Ana Martínez'),
(16, 1, 'efectivo', 'pendiente', 50.00, '2025-08-22', 'Calle Reforma #123', 'Arreglo floral con dedicatoria', 'Valeria'),
(17, 1, 'efectivo', 'pendiente', 50.00, '2025-08-22', 'Calle Reforma #123', 'Arreglo floral con dedicatoria', 'Valeria'),
(18, 9, 'efectivo', 'pendiente', 50.00, '2025-08-22', 'templo san lorenzo', 'blanco y rosa', 'Marisa Ramirez'),
(21, 7, 'transferecia', 'pagado', 70.00, '2025-08-22', 'la forestal', 'casa verde', 'maria');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `precio`, `descripcion`, `categoria`) VALUES
(1, 'Cubo de rosas', 250.00, 'rosas rojas, cubo de madera', 'Cubos'),
(5, 'oso corazón cariñoso', 350.00, 'oso de peluche en caja de madera adornada, acompañado con un globo metálico', 'arreglos'),
(6, 'box love 1', 900.00, 'caja mediana de madera 12 rosas 3 hortencias verdes', 'arreglo'),
(7, 'girasol queen', 1000.00, 'ramo con 6 girasoles y 18 rosas moneda limonium 3 papeles', 'ramo'),
(8, 'box love2', 600.00, 'caja de madera chica con 6 rosas 2 ferrero 4 fresas de chocolate', 'arreglo'),
(9, 'romantic box', 1550.00, 'tambito de madera chico 24 rosa 24ferreros corona chica', 'arreglo'),
(10, 'love princess', 650.00, 'ramo 12 rosas 3 gerberas 3 mariposas moreleana espuma y papeles', 'ramo'),
(11, 'ramo queen', 1100.00, 'ramo de 50 rosas 25 diamantes corona mediana y 2 mariposas', 'ramo'),
(12, 'love rose box', 450.00, 'cubito de madera con 12 rosas y astromedia letrerito de madera', 'arreglo'),
(13, 'ferrero rose', 450.00, 'cubito de madera con 12 rosas astromedia follajes y 5 ferreros', 'arreglo'),
(14, 'eternal rose', 280.00, 'ramo con 12 rosas 3 mariposas', 'ramo'),
(15, 'romantic rose', 250.00, 'ramo con 12 rosas astromedia y letrero de madera', 'ramo'),
(16, 'exotic love', 1700.00, 'arreglo con 24 rosas 5 girasoles y 8 star individual', 'arreglo'),
(17, 'queen heart', 2000.00, 'arreglo con 44 rosas 14 ferreros base mediana de corazon', 'arreglo'),
(18, 'reyna de corazones', 400.00, 'ramo con 24 rosas linea economica', 'ramo'),
(19, 'queen magic box', 1900.00, 'caja de maderacon cajoncito 36 rosas y 24 ferreros coronita', 'arreglo'),
(20, 'sweet love', 900.00, 'cajita de madera con tapa 20 rosas 1 mariposa', 'arreglo'),
(21, 'etenity love', 1600.00, 'tambo mediano de madera 6 girasoles y 24 rosas', 'arreglo'),
(22, 'ramo girasol', 2800.00, 'ramo con 20 girasoles y 10 tulipanes follajes', 'ramo'),
(23, 'corazon tulipan', 1100.00, 'caja cuadrada sin tapa25 rosas 2 tulipanes e hidrogel', 'arreglo'),
(24, 'corazon fresas', 1100.00, 'corazon mediano 2 girasoles 14 rosas 16 ferreros 8 chocofresas', 'arreglo'),
(25, 'box isabel queen', 1900.00, 'tambito con 40 rosas 10 ferrero coronita', 'arreglo'),
(26, 'blue roses eternal', 2000.00, 'ramo de 100 rosas', 'ramo'),
(27, 'hony love', 1100.00, 'cajita larga de madera 3 girasoles 12 rosas y mini rosas', 'arreglo'),
(28, 'xoxo love', 1400.00, 'ramo en base de oasis de corazon 3 paq de gyspo pintada 10 papeles', 'arreglo'),
(29, 'golden rose', 1200.00, 'caja largita tapa de mica 36 rosas no incluye botella', 'arreglo'),
(30, 'victoria', 1400.00, 'tambito de madera 24 rosas 13 varas de liciantus 4 hortencias', 'arreglo'),
(31, 'alicia pink', 230.00, 'ramo 2 gerberas 6 rosas 2 polares', 'ramo'),
(32, 'teddy love', 2400.00, 'tambito 30 rosas 4 tulipanes 5 lilies 3 liciantus 1 osito', 'arreglo'),
(33, 'love princesa roja', 2400.00, 'ramo de 100rosas 3 mariposas 1 corona grande', 'ramo'),
(34, 'real love', 2000.00, 'base madera 60 rosas 9 liciantus 15 claveles', 'arreglo'),
(35, 'ramo maria', 200.00, 'ramo con 6 rosas 1 girasol 1 mariposa', 'ramo'),
(36, 'garden love', 1800.00, '4 lilies 7 mini rosas 40 rosas 4 varas de liciantus tambito de mader', 'arreglo'),
(37, 'dulce tulipan', 650.00, 'ramo 9 tulipanes', 'ramo'),
(38, 'virtud', 2000.00, 'arreglo base de madera 40 rosas 6 hortencia 6 girasoles 2 pampas', 'arreglo'),
(39, 'pink coquette', 180.00, '3 gerberas 2 claveles', 'ramo'),
(40, 'florencia', 220.00, 'ramo 2 rosas 3 gerberas 1 coronita mini 1 globo chico', 'ramo'),
(41, 'love fantacy', 1200.00, '50 rosas corona mediana 1mariposa', 'ramo'),
(42, 'coquette bouquet', 2400.00, 'ramo de100 rosas 1 corona grande 3 mariposas', 'ramo'),
(43, 'rojo coquette', 200.00, 'ramo 6 rosas 3 perritos 1mariposa', 'ramo'),
(44, 'corazon de san valentin', 2100.00, 'arreglo caja de corazon grande 50 rosas 5 hortencias 1osito de fomy', 'arreglo'),
(45, 'ramo gerberas rosas', 1000.00, 'ramo de 24 gerberas 24 claveles', 'ramo'),
(46, 'cofre de encanto', 1400.00, '12 rosas 3 tulipanes 12 minirosa 3 perrito 3 hortensias verdes', 'arreglo'),
(47, 'ramo nuve blanca', 100.00, 'ramo pequeño de gyspo 2 papeles', 'ramo'),
(48, 'girasol de primavera', 100.00, 'ramo de 1 girasol', 'ramo'),
(49, 'ramo de tulipanes', 800.00, '10 tulipanes watsflower y safari', 'ramo'),
(50, 'amor sereno', 950.00, '8rosas 4 horiental 3 varas de lili 6 flores de liciantus 6 flores de briantus 3 perritos tambo chico', 'arreglo'),
(51, 'sueños rosados', 550.00, 'ramo 3 gerberas 6 rosas 6 claveles 4 mini rosas 1 papel de alas 4', 'arreglo'),
(52, 'encanto escarlata', 1400.00, '32 rosas 1 paq de perritos 1/2 paq de astromedia wats base barro', 'arreglo'),
(53, 'sol de primavera', 150.00, 'ramo 1 girasol 1 gerbera 1 rosa', 'arreglo'),
(54, 'dulce susurro', 125.00, '6 rosas tono a elegir', 'ramo'),
(55, 'dulce despertar', 130.00, 'ramo 2 gerberas 2 rosas normal', 'ramo'),
(56, 'romance floral', 125.00, 'ramo 6 rosas rojas normal', 'ramo'),
(57, 'encanto blanco', 700.00, 'maceta de orquidea blanca', 'maceta'),
(58, 'jardin de cristal', 900.00, '5 perritos 6 flores de liciantus 3 rosas 2 aves 3 tulipanes 5 claveles argentina y gravilla pecera grande', 'arreglo'),
(59, 'pasion carmesi', 250.00, 'ramo 12 rosas rojas normal', 'ramo'),
(60, 'encanto de gerbera', 500.00, 'ramo con 13 gerberas gyspo astromedia morelianas 5 papeles', 'ramo'),
(61, 'rosa eterna', 2500.00, 'rosa eterna en capsula', 'rosa'),
(62, 'cofre pasion', 1500.00, 'tambito con 32 rosas 10 mariposas de orquilla', 'arreglo'),
(63, 'amor eterno', 200.00, 'ramo con 6 rosas y gipso', 'ramo'),
(64, 'dulce primavera', 200.00, 'ramo de 6 gerberas normal', 'ramo'),
(65, 'pasion de fuego', 1400.00, 'base de corazon chica 34 rosas 7 flores de mini rosa 4 flores de briantus', 'arreglo'),
(66, 'paraiso florarl', 1500.00, '3girasoles 4 aves 2 hawallanas 6 varas de lilis 6 oriental ind 13 rosas safari base de barro grande', 'arreglo'),
(67, 'encanto rosa', 250.00, 'ramo de 12 rosas rosita normal (color puede variar)', 'ramo'),
(68, 'corazon de rosas', 2000.00, 'ramo de 75 rosas en forma de corazon base de plastico gyspo', 'arreglo'),
(69, 'amor en flor', 100.00, 'ramo de 3 gerberas normal', 'ramo'),
(70, 'amor carmesi', 500.00, 'ramo de 24 rosas y gipso', 'ramo'),
(71, 'pasion escarlata', 550.00, '20 rosas 10 alfileres 1 telita decorativa 3 papeles', 'ramo'),
(72, 'bouquet de ensueño', 500.00, '12 claveles 12 rosas 1 telita decorativa varios papeles', 'ramo'),
(73, 'jardin tropical', 1350.00, '7 alcatras 12 rosas 6 orientales 4 aves 2 girasoles 4 hortencia verde jarron grande', 'arreglo'),
(74, 'girasol de amor', 150.00, 'ramo de 3 rosas 1 girasol', 'ramo'),
(75, 'amanecer enrosendal', 950.00, '24 rosas 8perritos astro follajes base de barro', 'arreglo'),
(76, 'sweet woman', 850.00, '6 varas de lilis 4 gerberas 10 rosas 1 astromedia', 'arreglo'),
(77, 'valencia', 1300.00, '24 rosas 10 leatrix 8 hortencias verdes base de ceramica', 'arreglo'),
(78, 'beutiful woaman', 700.00, 'maceta de orquidea morada', 'maceta'),
(79, 'dulcinea', 700.00, 'maceta de orquidea café centro rosado incluye base', 'maceta'),
(80, 'tulipanes love', 900.00, 'ramo de 10 tulipanes', 'ramo'),
(81, 'love mom', 650.00, '3 varas de lilis 9 rosas caja con tapa y follajes', 'arreglo'),
(82, 'dulce reyna', 500.00, '12 rosas 12 papeles follajes 3 papeles', 'ramo'),
(83, 'super jefa', 700.00, 'pecera chica 3 tulipanes 3 horientales 3 rosas 3 hojas de ave', 'ramo'),
(84, 'aleluz', 800.00, '4 tulipanes 4 rosas 4 perros 3 varas de minirosa 3 hortencias verdes lechera de madera', 'arreglo'),
(85, 'dulce primavera1.0', 420.00, '6 gerberas 1 mariposa 3 papeles 1/2 gipso pintada', 'arreglo'),
(86, 'antonieta', 300.00, '10 rosas jarron 214 plumoso campana', 'ramo'),
(87, 'best mom', 800.00, 'ramo de 24 rosas gipso astromedia 1 coronita 3 papeles', 'ramo'),
(88, 'love mommy', 800.00, '4 tulipanes 3 ajos 4 rosas follajes cilindro de cristal', 'arreglo'),
(89, 'imperial', 2650.00, 'ramo de 100 rosas con corona grande', 'ramo'),
(90, 'super mamita', 850.00, '3 ajos 7 rosas 3 tulipanes 2 horientales 2 gerberas follaje y cilindro de cristal', 'arreglo'),
(91, 'veneccia', 700.00, 'cajita de madera con tapa 12 rosas 5 ferrero mii corona', 'arreglo'),
(92, 'exotic queen', 650.00, 'ramo primaveral 3 varas de lili 3 gerberas 12 rosas follajes', 'ramo'),
(93, 'real sweet', 650.00, '13 rosas 7 ferreros corona mini', 'arreglo'),
(94, 'dulzura de mujer', 550.00, 'cubo de 12 rosas 5 ferreros', 'arreglo'),
(95, 'freedom whoman', 450.00, 'cubito primaveral con 2 mariposa', 'arreglo'),
(96, 'mom day', 450.00, 'cubito primaveral con 1 globo chico', 'arreglo'),
(97, 'elegance queen red', 750.00, 'base de cristal 6 rosas 3 varas de lilis 3 hawallanas follajes', 'arreglo'),
(98, 'only love of mom', 1300.00, 'base de barro 5 gerberas 13 rosas 4 perritos 4 tulipanes uña de gato', 'arreglo'),
(99, 'sweet white swam', 3000.00, 'base grande de cristal 70 rosas imperico caja de ferrero grande', 'arreglo'),
(100, 'elegance white gerbera', 650.00, 'pecera chica 3 lilis 4 rosas 2 gerberas 8 flores de liciantus', 'arreglo'),
(101, 'gif pik of mom', 800.00, '2 lilis 5 gerberas 12 rosas 5 papeles 1 paq de surtido astro gyspo', 'arreglo'),
(102, 'all pink roses', 4000.00, 'bote de madera grande 100 rosas 2 paq de astro 1 paq de liciantus', 'arreglo'),
(103, 'pink sweet roses', 800.00, '18rosas 1 gerbera gipso astromedia 4 papeles decorativo', 'ramo'),
(104, 'delicate rose& astromedia', 1700.00, 'base tambo de madera 1 paq de astro 12 rosas 4 hortencia verde 5 tulipanes 6 mini rosas uña de gato', 'arreglo'),
(105, 'love live floral', 2300.00, 'base de ceramica grande 2 lilis 4 tulipanes 3 gerberas 12 rosas 3 orientales 6 perritos 3 hoertencias verdes 1/2 paq de liciantus astros', 'arreglo'),
(106, 'queeen flower', 900.00, 'base de barro 5 lilis 4 gerberas 3 perritos 6 rosas follajes varios', 'arreglo'),
(107, 'cesta de alegria', 1000.00, '12 rosas 8 perros 4 gerberas 4 bars de liciantus 3 baras de lilis 3 aves base platon de barro', 'arreglo'),
(108, 'raiz de mi vida', 750.00, 'copa de cristal 1 paq de pintado rosa 4 tulipanes 4 perritos 5 claveles follajes', 'arreglo'),
(109, 'amor que florece', 500.00, 'ramo una hortensia ind 8 rosas 2 gerberas 1 var de liciantus 2 varas de minirosas telita decorativa', 'arreglo'),
(110, 'detalles del corazon', 1400.00, '18 rosas 4 gerberas 7 orientales 2 girasoles 12 perritos base de cristal', 'arreglo'),
(111, 'bouquet del corazon', 550.00, '12 rosas 3 varas de liciantus 3 varas de mini rosa 5 papeles', 'arreglo'),
(112, 'guardia del sueño', 750.00, '19 rosas 6 perritos base follaes y base de cristal', 'arreglo'),
(113, 'luz de guia', 700.00, '6 claveles 6 tulipanes follajes variados', 'ramo'),
(114, 'suspiros de amor', 600.00, '12 rosas 3 perritos follaje variado base de cristal', 'arreglo'),
(115, 'mimos rosados', 1150.00, '4 orientales 4 perros 6 rosas 4 varas de liciantus 3 varas de lilis tambito rosa', 'arreglo'),
(116, 'amor que florece', 900.00, 'base grande de cristal 13 rosas 2 varas de lilis 5 orientales 7 perros 3 varas de liciantus', 'arreglo'),
(117, 'cesta primavera', 1450.00, '3 girasoles 6 gerberas 24 rosas canasta mediana de carriso', 'arreglo'),
(118, 'corazon de mama', 750.00, '6 gerberas 24 ferreros corazon mediano de madera con tapa', 'arreglo'),
(119, 'amor de mama', 1350.00, '31 rosas 10 perritos 4 gerberas base plato de cristal', 'arreglo'),
(120, 'guerra con ferreros', 1200.00, '15 rosas rojas 20 ferreros 5 varas de lilis', 'arreglo'),
(121, 'ramo mm20', 300.00, '6 rosas 6 claveles astro gipso y 3 papeles', 'ramo'),
(122, 'arreglo mm27', 4000.00, 'canasta de 100 rosas 6 girasoles', 'arreglo'),
(123, 'ramo mm13', 2500.00, '24 rosas 20 girasoles', 'ramo'),
(124, 'canasta mm6', 850.00, '15 rosas 3 hortencias verdes 2 varas de lilis espuma y papiros', 'arreglo'),
(125, 'jarron mm17', 900.00, '4 baras de lilis 6 gerberas 10 rosas 1 paq de astro', 'arreglo'),
(126, 'cajamm4', 1100.00, 'baul 6 tulipanes 5 tallos de mini rosa 2 girasoles 1 hortencia', 'arreglo'),
(127, 'canasta mm7', 450.00, '24 claveles un surtido mixto dollas campana y plumoso', 'arreglo'),
(128, 'ramo mm8', 800.00, '3 perritos una hortcencia 5 mini rosas 12 rosas 3 flores de liciantus', 'arreglo'),
(129, 'arreglo mm19', 1400.00, '26 rosas 3 orientales 2 tulipanes 4 aves', 'arreglo'),
(130, 'arreglo mm3', 1650.00, '15 rosas 3 gerberas 5 lilis 4 aves 10 perritos', 'arreglo'),
(131, 'arreglo f17', 4200.00, '100 rosas 10 baras de lilis 10 hawallanas', 'arreglo'),
(132, 'jarron mm12', 900.00, '10 rosas 3 liciantus 5 perritos follaje fino', 'arreglo'),
(133, 'arreglo mm9', 850.00, '3 baras de lilis 3 tulipanes 2 estargueiser espuma follajes', 'arreglo'),
(134, 'caja mm18', 750.00, 'caja con tapa 2baras de lilis 3 liciantus 6 rosas', 'arreglo'),
(135, 'arreglo mm19', 1800.00, '20 rosas 3 aves 2 girasoles 3 orientales 1 paq de astro', 'arreglo'),
(136, 'canasta mm20', 1500.00, '8 lilis 3 girasoles 24 rosas 2 gerberas 5 perritos', 'arreglo'),
(137, 'ramo mm21', 1600.00, '30 rosas 1 paq de astromedia 4 varas de lilis 5 orientales', 'arreglo'),
(138, 'arreglo mm22', 1200.00, '20 rosas 5 tulipanes 3 gerberas 2 girasoles 4 aves', 'arreglo'),
(139, 'canasta mm23', 1500.00, '18 rosas 4 liciantus 3 lilis 6 perritos 2 girasoles', 'arreglo'),
(140, 'ramo mm24', 1400.00, '25 rosas 4 lilis 3 gerberas 1 paq de astromedia', 'arreglo'),
(141, 'caja mm25', 1100.00, 'caja de madera con tapa 24 rosas 3 gerberas 2 tulipanes', 'arreglo'),
(142, 'ramo mm26', 1000.00, '22 rosas 3 gerberas 2 liciantus 4 tulipanes', 'arreglo'),
(143, 'canasta mm27', 1800.00, '30 rosas 6 liciantus 5 perritos 3 lilis', 'arreglo'),
(144, 'ramo mm28', 1400.00, '24 rosas 3 gerberas 2 lilis 3 tulipanes', 'arreglo'),
(145, 'ramo mm29', 900.00, '15 rosas 4 gerberas 3 lilis 1 tulipan', 'arreglo'),
(146, 'cesta mm30', 1200.00, '20 rosas 5 tulipanes 2 lilis 4 perritos', 'arreglo'),
(147, 'ramo mm31', 1400.00, '25 rosas 4 liciantus 3 lilis 3 tulipanes', 'arreglo'),
(148, 'arreglo mm32', 1300.00, '24 rosas 3 gerberas 4 lilis 2 tulipanes', 'arreglo'),
(149, 'canasta mm33', 1500.00, '30 rosas 5 tulipanes 4 gerberas 3 lilis', 'arreglo'),
(150, 'ramo mm34', 1400.00, '25 rosas 4 lilis 3 tulipanes 2 gerberas', 'arreglo'),
(151, 'ramo mm35', 900.00, '15 rosas 3 gerberas 2 lilis 1 tulipan', 'arreglo'),
(152, 'canasta mm36', 1200.00, '20 rosas 5 tulipanes 3 lilis 4 perritos', 'arreglo'),
(153, 'ramo mm37', 1400.00, '25 rosas 4 liciantus 3 lilis 3 tulipanes', 'arreglo'),
(154, 'arreglo mm38', 1300.00, '24 rosas 3 gerberas 4 lilis 2 tulipanes', 'arreglo'),
(155, 'canasta mm39', 1500.00, '30 rosas 5 tulipanes 4 gerberas 3 lilis', 'arreglo'),
(156, 'ramo mm40', 1400.00, '25 rosas 4 lilis 3 tulipanes 2 gerberas', 'arreglo'),
(157, 'corona b', 1800.00, 'montada en tripie corsach de 24 rosas y dollar', 'corona'),
(158, 'corona c', 3800.00, 'corona tamaño regular con 130 rosas', 'corona'),
(159, 'corona d', 1600.00, 'corona montada entripie 24 rosas 10 tallos de lilis un paquete de perritos', 'arreglo'),
(160, 'corona e', 3000.00, 'corona montada en tripie clavel rosa y horiental', 'corona'),
(161, 'corona f', 2500.00, 'corona con 20 tallos de lilis clavel montada sobre tripie', 'corona'),
(162, 'corona g', 2900.00, 'corona montada sobre tripie clavel lilis y rosas', 'corona'),
(163, 'corona h', 1800.00, 'corona tamaño regular', 'corona'),
(164, 'corona i', 900.00, 'corona tamaño regular', 'corona'),
(165, 'corona j', 1950.00, 'corona de cruz realizada con flor de lilis tamaño regular', 'corona'),
(166, 'corona k', 900.00, 'corona basica para caballero', 'corona'),
(167, 'corona l', 1950.00, 'corona con cruz de oriental individual', 'corona'),
(168, 'corona n', 5000.00, 'corona en base de corazon montada en tripie 200 rosas', 'corona'),
(169, 'corona o', 3200.00, 'corazon con 15 gerberas 40 rosas 15 baras de lilis 3 paq de heleonora en tripie', 'corona'),
(170, 'corona p', 850.00, 'tamaño regular basica', 'corona'),
(171, 'prueba', 250.00, 'prueba', 'canasta'),
(172, 'prueba', 250.00, 'prueba', 'prueba'),
(173, 'prueba 7', 300.00, 'pr', 'prueba');

-- --------------------------------------------------------

--
-- Table structure for table `repartidor`
--

CREATE TABLE `repartidor` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `repartidor`
--

INSERT INTO `repartidor` (`id`, `id_pedido`, `nombre`, `celular`, `fecha`) VALUES
(1, 1, 'valera ibarra', '5551122334', '2025-07-05'),
(2, 3, 'cesar', '6181801434', '2025-08-14');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `celular` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `celular`) VALUES
(1, 'Lorena', '6181801438'),
(2, 'jatziri', '6182037721'),
(3, 'valeria Guereca', '6181801434'),
(5, 'maria', '6182941516'),
(6, 'johan', '8723654782'),
(7, 'Lupita', '61823462837'),
(8, 'lluvia cisneros', '6181801439'),
(9, 'Marisa Ramirez', '6181442695');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_pedidos_detalle`
-- (See below for the actual view)
--
CREATE TABLE `vista_pedidos_detalle` (
`id_pedido` int(11)
,`usuario` varchar(100)
,`metodo_pago` varchar(50)
,`estado_pago` enum('pendiente','pagado','cancelado')
,`costo_envio` decimal(10,2)
,`fecha_envio` date
,`lugar` varchar(200)
,`descripcion` text
,`recibe` varchar(100)
,`productos` mediumtext
,`total_productos` decimal(42,2)
,`total_final` decimal(43,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_pedidos_simple`
-- (See below for the actual view)
--
CREATE TABLE `vista_pedidos_simple` (
`fecha` date
,`recibe` varchar(100)
,`lugar` varchar(200)
,`descripcion` text
,`productos` mediumtext
,`celular` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `vista_pedidos_detalle`
--
DROP TABLE IF EXISTS `vista_pedidos_detalle`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pedidos_detalle`  AS SELECT `p`.`id` AS `id_pedido`, `u`.`nombre` AS `usuario`, `p`.`metodo_pago` AS `metodo_pago`, `p`.`estado_pago` AS `estado_pago`, `p`.`costo_envio` AS `costo_envio`, `p`.`fecha_envio` AS `fecha_envio`, `p`.`lugar` AS `lugar`, `p`.`descripcion` AS `descripcion`, `p`.`recibe` AS `recibe`, group_concat(concat(`pr`.`nombre`,' (',`d`.`cantidad`,')') separator ', ') AS `productos`, sum(`d`.`cantidad` * `pr`.`precio`) AS `total_productos`, sum(`d`.`cantidad` * `pr`.`precio`) + `p`.`costo_envio` AS `total_final` FROM (((`pedido` `p` join `usuario` `u` on(`p`.`id_usuario` = `u`.`id`)) join `detalle` `d` on(`p`.`id` = `d`.`id_pedido`)) join `producto` `pr` on(`d`.`id_producto` = `pr`.`id`)) GROUP BY `p`.`id`, `u`.`nombre`, `p`.`metodo_pago`, `p`.`estado_pago`, `p`.`costo_envio`, `p`.`fecha_envio`, `p`.`lugar`, `p`.`descripcion`, `p`.`recibe` ;

-- --------------------------------------------------------

--
-- Structure for view `vista_pedidos_simple`
--
DROP TABLE IF EXISTS `vista_pedidos_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pedidos_simple`  AS SELECT `p`.`fecha_envio` AS `fecha`, `p`.`recibe` AS `recibe`, `p`.`lugar` AS `lugar`, `p`.`descripcion` AS `descripcion`, group_concat(concat(`pr`.`nombre`,' (',`d`.`cantidad`,')') separator ', ') AS `productos`, `u`.`celular` AS `celular` FROM (((`pedido` `p` join `usuario` `u` on(`p`.`id_usuario` = `u`.`id`)) join `detalle` `d` on(`p`.`id` = `d`.`id_pedido`)) join `producto` `pr` on(`d`.`id_producto` = `pr`.`id`)) GROUP BY `p`.`id`, `p`.`fecha_envio`, `p`.`recibe`, `p`.`lugar`, `p`.`descripcion`, `u`.`celular` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detalle`
--
ALTER TABLE `detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indexes for table `iniciar`
--
ALTER TABLE `iniciar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repartidor`
--
ALTER TABLE `repartidor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detalle`
--
ALTER TABLE `detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `iniciar`
--
ALTER TABLE `iniciar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `repartidor`
--
ALTER TABLE `repartidor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle`
--
ALTER TABLE `detalle`
  ADD CONSTRAINT `detalle_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `detalle_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

--
-- Constraints for table `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Constraints for table `repartidor`
--
ALTER TABLE `repartidor`
  ADD CONSTRAINT `repartidor_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
