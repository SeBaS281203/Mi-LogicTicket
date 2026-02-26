-- Pega aquí tu exportación de la base de datos
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-02-2026 a las 01:58:31
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
-- Base de datos: `logicticket`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_approve_event` (IN `p_event_id` INT)   UPDATE events SET status = 'published' WHERE id = p_event_id AND status = 'pending_approval'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_dashboard_stats` ()   SELECT
                (SELECT COUNT(*) FROM users) AS total_users,
                (SELECT COUNT(*) FROM users WHERE role = 'organizer') AS total_organizers,
                (SELECT COUNT(*) FROM events) AS total_events,
                (SELECT COUNT(*) FROM events WHERE status = 'published') AS events_published,
                (SELECT COUNT(*) FROM events WHERE status = 'pending_approval') AS events_pending,
                (SELECT COUNT(*) FROM events WHERE status = 'published' AND start_date >= CURDATE()) AS events_active,
                (SELECT COUNT(*) FROM orders WHERE status = 'paid') AS total_orders,
                (SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'paid') AS total_revenue$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reject_event` (IN `p_event_id` INT)   UPDATE events SET status = 'draft' WHERE id = p_event_id AND status = 'pending_approval'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_revenue_by_month` ()   SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COALESCE(SUM(total), 0) AS revenue
            FROM orders
            WHERE status = 'paid'
              AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `link_text` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link_url`, `link_text`, `is_active`, `sort_order`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(1, '¡Eventos destacados!', 'Encuentra las mejores entradas', NULL, '/eventos', 'Ver eventos', 1, 1, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 'Ofertas de temporada', 'Descuentos en entradas seleccionadas', 'banners/eI9oxszLfQj3pB6koOLRBzVFvqjk8Bi0bDPZuWKh.png', NULL, 'Explorar', 1, 2, NULL, NULL, '2026-02-18 01:20:46', '2026-02-25 23:28:59'),
(3, 'ING. ARCILA DIAZ', 'Proyecto desarrollado bajo la asesoría del Ing. Arcila', 'banners/zBCufz7B7HqlE2gSSBKsvx4UqfzYE8eAeHeWfF73.png', NULL, NULL, 1, 3, '2026-02-25 00:00:00', '2026-03-03 00:00:00', '2026-02-25 23:22:14', '2026-02-25 23:31:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Conciertos', 'conciertos', 'Conciertos y festivales de música', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 'Deportes', 'deportes', 'Eventos deportivos', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(3, 'Teatro', 'teatro', 'Obras de teatro y musicales', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(4, 'Conferencias', 'conferencias', 'Charlas y conferencias', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(5, 'Fiestas', 'fiestas', 'Fiestas y eventos nocturnos', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(6, 'Gastronomía', 'gastronomia', 'Ferias y eventos gastronómicos', NULL, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Peru',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cities`
--

INSERT INTO `cities` (`id`, `name`, `slug`, `country`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Lima', 'lima', 'Peru', 1, 1, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 'Arequipa', 'arequipa', 'Peru', 1, 2, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(3, 'Cusco', 'cusco', 'Peru', 1, 3, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(4, 'Trujillo', 'trujillo', 'Peru', 1, 4, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(5, 'Chiclayo', 'chiclayo', 'Peru', 1, 5, '2026-02-18 01:20:46', '2026-02-18 01:20:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `venue_name` varchar(255) NOT NULL,
  `venue_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Peru',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `ticket_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `available_tickets` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `event_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending_approval','published','cancelled') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `user_id`, `category_id`, `title`, `slug`, `description`, `image`, `venue_name`, `venue_address`, `city`, `country`, `latitude`, `longitude`, `start_date`, `end_date`, `ticket_price`, `available_tickets`, `event_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Festival de Rock 2025', 'festival-de-rock-2025-e69125', 'Descripción del evento Festival de Rock 2025. Ven y disfruta con nosotros.', NULL, 'Estadio Nacional', '92694 Erdman Overpass Apt. 850', 'Lima', 'Peru', NULL, NULL, '2026-05-04 19:00:46', '2026-05-04 22:00:46', 0.00, 0, NULL, 'published', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 3, 6, 'Maratón de Lima', 'maraton-de-lima-e6f139', 'Descripción del evento Maratón de Lima. Ven y disfruta con nosotros.', NULL, 'Costa Verde', '4177 Anibal Causeway Apt. 243', 'Lima', 'Peru', NULL, NULL, '2026-03-06 19:00:46', '2026-03-06 22:00:46', 0.00, 0, NULL, 'published', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(3, 2, 2, 'Obra: Romeo y Julieta', 'obra-romeo-y-julieta-e719fd', 'Descripción del evento Obra: Romeo y Julieta. Ven y disfruta con nosotros.', NULL, 'Teatro Municipal', '4470 Ebert Mission Apt. 798', 'Lima', 'Peru', NULL, NULL, '2026-02-28 19:00:46', '2026-02-28 22:00:46', 0.00, 0, NULL, 'published', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(4, 3, 3, 'Conferencia Tech 2025', 'conferencia-tech-2025-e73375', 'Descripción del evento Conferencia Tech 2025. Ven y disfruta con nosotros.', NULL, 'Centro de Convenciones', '5820 Destin Coves', 'Lima', 'Peru', NULL, NULL, '2026-04-22 19:00:46', '2026-04-22 22:00:46', 0.00, 0, NULL, 'draft', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(5, 2, 1, 'Fiesta de Año Nuevo', 'fiesta-de-ano-nuevo-e74cc4', 'Descripción del evento Fiesta de Año Nuevo. Ven y disfruta con nosotros.', NULL, 'Salón VIP', '1830 Lowe Cliff', 'Arequipa', 'Peru', NULL, NULL, '2026-04-06 19:00:46', '2026-04-06 22:00:46', 0.00, 0, NULL, 'draft', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(6, 3, 3, 'Concierto de Cumbia', 'concierto-de-cumbia-e76803', 'Descripción del evento Concierto de Cumbia. Ven y disfruta con nosotros.', NULL, 'Coliseo Arequipa', '17393 Zetta Fall Apt. 607', 'Arequipa', 'Peru', NULL, NULL, '2026-05-02 19:00:46', '2026-05-02 22:00:46', 0.00, 0, NULL, 'published', '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(7, 2, 4, 'Inti Raymi 2025', 'inti-raymi-2025-e784aa', 'Descripción del evento Inti Raymi 2025. Ven y disfruta con nosotros.', NULL, 'Sacsayhuamán', '6260 Jerde Terrace Apt. 502', 'Cusco', 'Peru', NULL, NULL, '2026-04-17 19:00:46', '2026-04-17 22:00:46', 0.00, 0, NULL, 'published', '2026-02-18 01:20:46', '2026-02-18 01:20:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_reclamaciones`
--

CREATE TABLE `libro_reclamaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_reclamo` varchar(32) NOT NULL COMMENT 'Correlativo tipo LR-AÑO-000001',
  `tipo_documento` varchar(20) NOT NULL COMMENT 'DNI, CE, Pasaporte',
  `numero_documento` varchar(20) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `direccion` varchar(500) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo_reclamo` varchar(20) NOT NULL COMMENT 'reclamo, queja',
  `descripcion` text NOT NULL,
  `pedido_consumidor` text DEFAULT NULL,
  `evento_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, atendido, cerrado',
  `respuesta_empresa` text DEFAULT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_17_000001_add_role_to_users_table', 1),
(5, '2025_02_17_000002_create_categories_table', 1),
(6, '2025_02_17_000003_create_events_table', 1),
(7, '2025_02_17_000004_create_ticket_types_table', 1),
(8, '2025_02_17_000005_create_orders_table', 1),
(9, '2025_02_17_000006_create_order_items_table', 1),
(10, '2025_02_18_000000_add_event_fields_to_events_table', 1),
(11, '2025_02_19_000001_add_commission_to_orders_table', 1),
(12, '2025_02_19_000002_create_tickets_table', 1),
(13, '2025_02_20_000001_add_pending_approval_to_events_status', 1),
(14, '2025_02_20_000002_create_settings_table', 1),
(15, '2025_02_20_000003_create_cities_table', 1),
(16, '2025_02_20_000004_create_banners_table', 1),
(17, '2025_02_21_000001_create_stored_procedures', 1),
(18, '2026_02_17_000001_create_libro_reclamaciones_table', 2),
(19, '2026_02_19_005409_create_tendencias_table', 3),
(20, '2026_02_19_000001_add_ruc_to_users_table', 4),
(21, '2026_02_20_000001_add_production_indexes', 5),
(22, '2026_02_22_203450_add_scanned_fields_to_tickets_table', 6),
(23, '2026_02_24_000000_create_password_reset_tokens_table', 7),
(24, '2026_02_25_000001_add_registration_profile_fields_to_users_table', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL COMMENT 'Stripe PaymentIntent or similar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_email`, `customer_name`, `customer_phone`, `subtotal`, `commission_amount`, `total`, `status`, `payment_method`, `payment_id`, `created_at`, `updated_at`) VALUES
(1, 'LT-DEMO001-20250221', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 102.00, 5.10, 107.10, 'paid', 'manual', NULL, '2026-02-18 01:20:46', '2026-02-25 22:47:23'),
(2, 'LT-NTO6LQPC-20260219', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 148.00, 7.40, 155.40, 'paid', 'manual', NULL, '2026-02-19 09:42:59', '2026-02-25 22:47:23'),
(3, 'LT-CM8DVMCB-20260219', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 46.00, 2.30, 48.30, 'paid', 'manual', NULL, '2026-02-19 21:25:30', '2026-02-25 22:47:23'),
(4, 'LT-VUANJGV6-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 51.00, 2.55, 53.55, 'paid', 'manual', NULL, '2026-02-23 02:01:27', '2026-02-25 22:47:23'),
(5, 'LT-OKX7LROP-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 121.00, 6.05, 127.05, 'paid', 'manual', NULL, '2026-02-23 02:03:38', '2026-02-25 22:47:23'),
(6, 'LT-F6257RY5-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 51.00, 2.55, 53.55, 'paid', 'manual', NULL, '2026-02-23 02:06:20', '2026-02-25 22:47:23'),
(7, 'LT-TGTBR42X-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 102.00, 5.10, 107.10, 'paid', 'manual', NULL, '2026-02-23 02:10:05', '2026-02-25 22:47:23'),
(8, 'LT-RCGIPRPY-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 269.00, 13.45, 282.45, 'paid', NULL, NULL, '2026-02-23 02:16:36', '2026-02-25 22:47:23'),
(9, 'LT-1RM86BOT-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 269.00, 13.45, 282.45, 'paid', NULL, NULL, '2026-02-23 02:16:52', '2026-02-25 22:47:23'),
(10, 'LT-PSC2OD0D-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 32.00, 1.60, 33.60, 'paid', NULL, NULL, '2026-02-23 02:20:39', '2026-02-25 22:47:23'),
(11, 'LT-OFXO92HW-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 32.00, 1.60, 33.60, 'paid', NULL, NULL, '2026-02-23 02:22:27', '2026-02-25 22:47:23'),
(12, 'LT-VZX8LNXD-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 32.00, 1.60, 33.60, 'paid', NULL, NULL, '2026-02-23 02:24:39', '2026-02-25 22:47:23'),
(13, 'LT-AUAYQ0PM-20260222', 4, 'client@logicticket.com', 'Cliente Demo', '999555666', 32.00, 1.60, 33.60, 'paid', NULL, NULL, '2026-02-23 02:24:47', '2026-02-25 22:47:23'),
(14, 'LT-Z3OOJRIS-20260222', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 301.00, 15.05, 316.05, 'paid', NULL, NULL, '2026-02-23 02:30:54', '2026-02-25 22:47:23'),
(15, 'LT-PGAS8HVU-20260222', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 230.00, 11.50, 241.50, 'paid', NULL, NULL, '2026-02-23 02:51:14', '2026-02-25 22:47:23'),
(16, 'LT-LLHAHNK3-20260222', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 51.00, 2.55, 53.55, 'paid', NULL, NULL, '2026-02-23 03:17:39', '2026-02-25 22:47:23'),
(17, 'LT-DSMNCADM-20260223', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 32.00, 1.60, 33.60, 'paid', NULL, NULL, '2026-02-23 19:20:41', '2026-02-25 22:47:23'),
(18, 'LT-UEPI9QDL-20260224', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 51.00, 2.55, 53.55, 'paid', NULL, NULL, '2026-02-24 23:24:55', '2026-02-25 22:47:23'),
(19, 'LT-8PFE2OVB-20260224', 8, 'rmelendresedinj@uss.edu.pe', 'edin', NULL, 121.00, 6.05, 127.05, 'paid', NULL, NULL, '2026-02-24 21:14:47', '2026-02-25 22:47:23'),
(20, 'LT-DHNSUXWT-20260224', 8, 'rmelendresedinj@uss.edu.pe', 'edin', NULL, 121.00, 6.05, 127.05, 'paid', NULL, NULL, '2026-02-24 21:15:38', '2026-02-25 22:47:23'),
(21, 'LT-WOQLW8S3-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 148.00, 7.40, 155.40, 'paid', NULL, NULL, '2026-02-25 20:28:25', '2026-02-25 22:47:23'),
(22, 'LT-PBBDGNXR-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 296.00, 14.80, 310.80, 'paid', NULL, NULL, '2026-02-25 20:42:47', '2026-02-25 22:47:23'),
(23, 'LT-OGI0SC35-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 121.00, 6.05, 127.05, 'paid', NULL, NULL, '2026-02-25 21:41:44', '2026-02-25 22:47:23'),
(24, 'LT-D6VFU5OB-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', NULL, NULL, '2026-02-25 22:20:47', '2026-02-25 22:47:23'),
(25, 'LT-RXXCGPUE-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', NULL, NULL, '2026-02-25 22:25:51', '2026-02-25 22:47:23'),
(26, 'LT-YHBE2DD1-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', NULL, NULL, '2026-02-25 22:26:37', '2026-02-25 22:47:23'),
(27, 'LT-6GYAJFCY-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', NULL, NULL, '2026-02-25 22:32:57', '2026-02-25 22:47:23'),
(28, 'LT-VZ5F6BGE-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', NULL, NULL, '2026-02-25 22:33:07', '2026-02-25 22:47:23'),
(29, 'LT-JDNZQXWZ-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 451.00, 22.55, 473.55, 'paid', 'stripe', 'pi_3T4qj4HrFcTZRXEn1zSU2xmJ', '2026-02-25 22:37:44', '2026-02-25 22:47:23'),
(30, 'LT-SWFA8BYX-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 148.00, 7.40, 155.40, 'paid', 'stripe', 'pi_3T4qlmHrFcTZRXEn1QVzVaur', '2026-02-25 22:40:18', '2026-02-25 22:47:23'),
(31, 'LT-PPDO5PDF-20260225', 4, 'client@logicticket.com', 'Cliente Demo', NULL, 121.00, 6.05, 127.05, 'paid', 'stripe', 'pi_3T4qnNHrFcTZRXEn0kyEqaCN', '2026-02-25 22:42:30', '2026-02-25 22:47:23'),
(32, 'LT-7KTDPW75-20260225', 8, 'rmelendresedinj@uss.edu.pe', 'edin', NULL, 148.00, 7.40, 155.40, 'paid', 'stripe', 'pi_3T4qrSHrFcTZRXEn1bWWf9ff', '2026-02-25 22:46:42', '2026-02-25 22:47:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_type_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_type_name` varchar(255) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `ticket_type_id`, `event_id`, `ticket_type_name`, `event_title`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 3, 'General', 'Obra: Romeo y Julieta', 2, 51.00, 102.00, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 2, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-19 09:42:59', '2026-02-19 09:42:59'),
(3, 3, 11, 6, 'General', 'Concierto de Cumbia', 1, 46.00, 46.00, '2026-02-19 21:25:30', '2026-02-19 21:25:30'),
(4, 4, 5, 3, 'General', 'Obra: Romeo y Julieta', 1, 51.00, 51.00, '2026-02-23 02:01:27', '2026-02-23 02:01:27'),
(5, 5, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-23 02:03:38', '2026-02-23 02:03:38'),
(6, 6, 5, 3, 'General', 'Obra: Romeo y Julieta', 1, 51.00, 51.00, '2026-02-23 02:06:20', '2026-02-23 02:06:20'),
(7, 7, 5, 3, 'General', 'Obra: Romeo y Julieta', 2, 51.00, 102.00, '2026-02-23 02:10:05', '2026-02-23 02:10:05'),
(8, 8, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-23 02:16:36', '2026-02-23 02:16:36'),
(9, 8, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-23 02:16:36', '2026-02-23 02:16:36'),
(10, 9, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-23 02:16:52', '2026-02-23 02:16:52'),
(11, 9, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-23 02:16:52', '2026-02-23 02:16:52'),
(12, 10, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 02:20:39', '2026-02-23 02:20:39'),
(13, 11, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 02:22:27', '2026-02-23 02:22:27'),
(14, 12, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 02:24:39', '2026-02-23 02:24:39'),
(15, 13, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 02:24:47', '2026-02-23 02:24:47'),
(16, 14, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 02:30:54', '2026-02-23 02:30:54'),
(17, 14, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-23 02:30:54', '2026-02-23 02:30:54'),
(18, 14, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-23 02:30:54', '2026-02-23 02:30:54'),
(19, 15, 2, 1, 'VIP', 'Festival de Rock 2025', 1, 230.00, 230.00, '2026-02-23 02:51:14', '2026-02-23 02:51:14'),
(20, 16, 5, 3, 'General', 'Obra: Romeo y Julieta', 1, 51.00, 51.00, '2026-02-23 03:17:39', '2026-02-23 03:17:39'),
(21, 17, 1, 1, 'General', 'Festival de Rock 2025', 1, 32.00, 32.00, '2026-02-23 19:20:41', '2026-02-23 19:20:41'),
(22, 18, 5, 3, 'General', 'Obra: Romeo y Julieta', 1, 51.00, 51.00, '2026-02-24 23:24:55', '2026-02-24 23:24:55'),
(23, 19, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-24 21:14:47', '2026-02-24 21:14:47'),
(24, 20, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-24 21:15:38', '2026-02-24 21:15:38'),
(25, 21, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-25 20:28:25', '2026-02-25 20:28:25'),
(26, 22, 13, 7, 'General', 'Inti Raymi 2025', 2, 148.00, 296.00, '2026-02-25 20:42:47', '2026-02-25 20:42:47'),
(27, 23, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 21:41:44', '2026-02-25 21:41:44'),
(28, 24, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:20:47', '2026-02-25 22:20:47'),
(29, 24, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:20:47', '2026-02-25 22:20:47'),
(30, 24, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:20:47', '2026-02-25 22:20:47'),
(31, 25, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:25:51', '2026-02-25 22:25:51'),
(32, 25, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:25:51', '2026-02-25 22:25:51'),
(33, 25, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:25:51', '2026-02-25 22:25:51'),
(34, 26, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:26:37', '2026-02-25 22:26:37'),
(35, 26, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:26:37', '2026-02-25 22:26:37'),
(36, 26, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:26:37', '2026-02-25 22:26:37'),
(37, 27, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:32:57', '2026-02-25 22:32:57'),
(38, 27, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:32:57', '2026-02-25 22:32:57'),
(39, 27, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:32:57', '2026-02-25 22:32:57'),
(40, 28, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:33:07', '2026-02-25 22:33:07'),
(41, 28, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:33:07', '2026-02-25 22:33:07'),
(42, 28, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:33:07', '2026-02-25 22:33:07'),
(43, 29, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:37:44', '2026-02-25 22:37:44'),
(44, 29, 4, 2, 'VIP', 'Maratón de Lima', 1, 169.00, 169.00, '2026-02-25 22:37:44', '2026-02-25 22:37:44'),
(45, 29, 14, 7, 'VIP', 'Inti Raymi 2025', 1, 161.00, 161.00, '2026-02-25 22:37:44', '2026-02-25 22:37:44'),
(46, 30, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-25 22:40:18', '2026-02-25 22:40:18'),
(47, 31, 3, 2, 'General', 'Maratón de Lima', 1, 121.00, 121.00, '2026-02-25 22:42:30', '2026-02-25 22:42:30'),
(48, 32, 13, 7, 'General', 'Inti Raymi 2025', 1, 148.00, 148.00, '2026-02-25 22:46:42', '2026-02-25 22:46:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7kp2jRWUzTLnQR1UuPNYyIIK504VDayT4hkuGYz2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; es-PE) WindowsPowerShell/5.1.26100.7705', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2xVVmZlemdQYnNia3l4SEJ2SUdoZExESjZOVDdndllubkRGSEZNaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772061960),
('gbaNs9fInPc5F9qi5y8GjRsS0PsowURjmaLEkPoO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; es-PE) WindowsPowerShell/5.1.26100.7705', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHB1MXB5enBRMGUxY0dnNnhHVGFMOEV1OTZXaExVQkRWbXlNeldWZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772061995),
('I5awp0OsfKyzNxQexx4OsoddEjBYo3QCxaSSLJvF', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVFVMMUZIbk8yVUdtRU1zNnpWMlJiSkkxSDVaRVJCc3laelhkMHEwUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1772063615),
('t8Qjwa8hYcabU3YWfoIigNRyn8TtXPfLTGJuu7wg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; es-PE) WindowsPowerShell/5.1.26100.7705', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkZFdDRYc2FaVmN3d3pOTm4yS1pueGVhRFZ5VW83a3p4bTZYRUJTZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772061978),
('zonTWY6oyWRzFUgEaQ0HTX6KsthOA7reMvwYvDzk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; es-PE) WindowsPowerShell/5.1.26100.7705', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemduQVNmbE82aXp5REFBZVc5MmJ3b3ZsSUJTZ2ZwZGlFc0FBS0hyeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZWRpYS9wdWJsaWMvYmFubmVycy9lSTlveHN6TGZRajNwQjZrb09MUkJ6VkZ2cWprOEJpMGJEUFp1V0toLnBuZyI7czo1OiJyb3V0ZSI7czoxMjoibWVkaWEucHVibGljIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1772062200);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `key` varchar(128) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('commission_percentage', '5', '2026-02-18 01:20:46', '2026-02-18 01:20:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tendencias`
--

CREATE TABLE `tendencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `orden` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(64) NOT NULL COMMENT 'Código único para QR y validación',
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `scanned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `order_item_id`, `code`, `is_used`, `scanned_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'TK-6EMQ4CI8ETFX-blNC', 0, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 1, 'TK-YHWBIP53AHGN-RNqz', 0, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(3, 2, 'TK-MYNPCIFH9XZV-YIYR', 0, NULL, '2026-02-19 09:42:59', '2026-02-19 09:42:59'),
(4, 3, 'TK-ISGRTBAXYKS3-5AWQ', 0, NULL, '2026-02-19 21:25:30', '2026-02-19 21:25:30'),
(5, 4, 'TK-0WS1GP0WWSYT-VVLD', 0, NULL, '2026-02-23 02:01:27', '2026-02-23 02:01:27'),
(6, 5, 'TK-4YPXEOLN2A5F-DAKV', 0, NULL, '2026-02-23 02:03:38', '2026-02-23 02:03:38'),
(7, 6, 'TK-UYK1G8XGREWQ-Q41E', 0, NULL, '2026-02-23 02:06:20', '2026-02-23 02:06:20'),
(8, 7, 'TK-BGQCQTL0YTHQ-8EEU', 0, NULL, '2026-02-23 02:10:05', '2026-02-23 02:10:05'),
(9, 7, 'TK-FBC6DGH9MGUJ-EBMG', 0, NULL, '2026-02-23 02:10:05', '2026-02-23 02:10:05'),
(10, 43, 'TK-OCRKSMCBCIJZ-4H3I', 0, NULL, '2026-02-25 22:38:43', '2026-02-25 22:38:43'),
(11, 44, 'TK-TNT8WK6QOTVE-VN80', 0, NULL, '2026-02-25 22:38:43', '2026-02-25 22:38:43'),
(12, 45, 'TK-PXZEEKU3DH4Y-VGZC', 0, NULL, '2026-02-25 22:38:43', '2026-02-25 22:38:43'),
(13, 46, 'TK-GKNSWH9J49JG-MF9C', 0, NULL, '2026-02-25 22:41:30', '2026-02-25 22:41:30'),
(14, 47, 'TK-5SBEQOIGUTSS-0NMJ', 0, NULL, '2026-02-25 22:43:09', '2026-02-25 22:43:09'),
(15, 48, 'TK-F41MP3GPEGML-LXP5', 0, NULL, '2026-02-25 22:47:23', '2026-02-25 22:47:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `quantity_sold` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `max_per_order` int(11) DEFAULT NULL,
  `sale_start` datetime DEFAULT NULL,
  `sale_end` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `event_id`, `name`, `description`, `price`, `quantity`, `quantity_sold`, `max_per_order`, `sale_start`, `sale_end`, `created_at`, `updated_at`) VALUES
(1, 1, 'General', NULL, 32.00, 484, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(2, 1, 'VIP', NULL, 230.00, 42, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(3, 2, 'General', NULL, 121.00, 349, 3, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-25 22:43:09'),
(4, 2, 'VIP', NULL, 169.00, 72, 1, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-25 22:38:43'),
(5, 3, 'General', NULL, 51.00, 175, 6, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-23 02:10:05'),
(6, 3, 'VIP', NULL, 221.00, 57, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(7, 4, 'General', NULL, 66.00, 212, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(8, 4, 'VIP', NULL, 165.00, 57, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(9, 5, 'General', NULL, 95.00, 474, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(10, 5, 'VIP', NULL, 233.00, 68, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(11, 6, 'General', NULL, 46.00, 389, 1, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-19 21:25:30'),
(12, 6, 'VIP', NULL, 360.00, 35, 0, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(13, 7, 'General', NULL, 148.00, 129, 3, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-25 22:47:23'),
(14, 7, 'VIP', NULL, 161.00, 63, 1, NULL, NULL, NULL, '2026-02-18 01:20:46', '2026-02-25 22:38:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(120) DEFAULT NULL,
  `last_name` varchar(120) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'client',
  `phone` varchar(255) DEFAULT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `city` varchar(120) DEFAULT NULL,
  `document_type` varchar(20) DEFAULT NULL,
  `document_number` varchar(30) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `organization_address` varchar(255) DEFAULT NULL,
  `marketing_consent` tinyint(1) NOT NULL DEFAULT 0,
  `terms_accepted_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `email`, `role`, `phone`, `ruc`, `country`, `city`, `document_type`, `document_number`, `gender`, `organization_name`, `organization_address`, `marketing_consent`, `terms_accepted_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL, 'admin@logicticket.com', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$6kEkdlCUCL4bkusCD64HHeR6MhiraqrVIFdX/L1ZrTUw1HKxixXSS', NULL, '2026-02-18 01:20:45', '2026-02-18 01:20:45'),
(2, 'Organizador Demo', NULL, NULL, 'organizer@logicticket.com', 'organizer', '999111222', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$KUXj7rmQzGHiqKcmROLxnOA3hClXLCIh8Lq6QWeI59RUJPxr9Rihe', NULL, '2026-02-18 01:20:45', '2026-02-18 01:20:45'),
(3, 'María Producciones', NULL, NULL, 'maria@logicticket.com', 'organizer', '999333444', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$kWqbP3HYJfW1RbTVp.7zzONjJ2wi6rjlEU4OLSz.qzXyHt0CQ76hu', NULL, '2026-02-18 01:20:45', '2026-02-18 01:20:45'),
(4, 'Cliente Demo', NULL, NULL, 'client@logicticket.com', 'client', '999555666', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$zQNSVpLKgCYvYrVba9dVSeH78veYR9QnvJOwIY78wz/tayQhaEFV6', NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(5, 'Juan Pérez', NULL, NULL, 'juan@logicticket.com', 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$6RZqG9IV0bTaNJmDrM6vo.KbrMrKFbOu/ll2pSAGln.T0113O0jua', NULL, '2026-02-18 01:20:46', '2026-02-18 01:20:46'),
(8, 'edin', NULL, NULL, 'rmelendresedinj@uss.edu.pe', 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$fg8mcE1CJFjRLosC57Cztes5q2WZS/5mgHiOmSb4cBPQR7SjEPrVa', 'DBWmXiWbth04gqQfUBYKGTJVTckfGiXNFA2ESEnvIzigJT2d9haP3q7OOgoX', '2026-02-24 23:52:12', '2026-02-24 20:17:44'),
(9, 'edin', NULL, NULL, 'edinromeromelendres@gmail.com', 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$kMH2YXf0orJDOhHB4ebJ5OCvyEVgH5TsqIUIRD8Cs8sP8wxJWl.86', NULL, '2026-02-25 00:35:19', '2026-02-25 00:35:19'),
(10, 'Sebastian', NULL, NULL, 'nsilvajosueseba@uss.edu.pe', 'client', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '$2y$12$PnET73iRKaoIkd/0DAEKtuU3RlN2flKXRJ04Z4rd3BVUf7P.BHi1i', NULL, '2026-02-25 00:38:24', '2026-02-25 00:38:24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indices de la tabla `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_slug_unique` (`slug`);

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
  ADD KEY `events_user_id_foreign` (`user_id`),
  ADD KEY `events_category_id_foreign` (`category_id`),
  ADD KEY `events_status_start_date_index` (`status`,`start_date`),
  ADD KEY `events_city_index` (`city`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libro_reclamaciones`
--
ALTER TABLE `libro_reclamaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libro_reclamaciones_codigo_reclamo_unique` (`codigo_reclamo`),
  ADD KEY `libro_reclamaciones_evento_id_foreign` (`evento_id`),
  ADD KEY `libro_reclamaciones_user_id_foreign` (`user_id`),
  ADD KEY `libro_reclamaciones_codigo_reclamo_index` (`codigo_reclamo`),
  ADD KEY `libro_reclamaciones_estado_index` (`estado`),
  ADD KEY `libro_reclamaciones_tipo_reclamo_index` (`tipo_reclamo`),
  ADD KEY `libro_reclamaciones_created_at_index` (`created_at`),
  ADD KEY `libro_reclamaciones_estado_created_at_index` (`estado`,`created_at`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_status_index` (`user_id`,`status`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_created_at_index` (`created_at`),
  ADD KEY `orders_status_created_at_index` (`status`,`created_at`),
  ADD KEY `orders_customer_email_index` (`customer_email`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_ticket_type_id_foreign` (`ticket_type_id`),
  ADD KEY `order_items_event_id_index` (`event_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `tendencias`
--
ALTER TABLE `tendencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_code_unique` (`code`),
  ADD KEY `tickets_order_item_id_foreign` (`order_item_id`),
  ADD KEY `tickets_code_index` (`code`);

--
-- Indices de la tabla `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_types_event_id_foreign` (`event_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libro_reclamaciones`
--
ALTER TABLE `libro_reclamaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `tendencias`
--
ALTER TABLE `tendencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libro_reclamaciones`
--
ALTER TABLE `libro_reclamaciones`
  ADD CONSTRAINT `libro_reclamaciones_evento_id_foreign` FOREIGN KEY (`evento_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `libro_reclamaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ticket_type_id_foreign` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_types` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `ticket_types_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

