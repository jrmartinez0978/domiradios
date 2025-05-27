-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-05-2025 a las 06:00:08
-- Versión del servidor: 10.11.11-MariaDB-0ubuntu0.24.04.2
-- Versión de PHP: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web-domiradios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('23ba98563dd826a1daf38a883487eb42c9db8048', 'i:1;', 1733154929),
('23ba98563dd826a1daf38a883487eb42c9db8048:timer', 'i:1733154929;', 1733154929),
('2be81c76ff043863bab9603f514714a36ad189d1', 'i:1;', 1733180980),
('2be81c76ff043863bab9603f514714a36ad189d1:timer', 'i:1733180980;', 1733180980),
('356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1734632662),
('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1734632662;', 1734632662),
('3ef01e2e420f9cb5e4cd69da3b447ca3c7d93f92', 'i:1;', 1746082250),
('3ef01e2e420f9cb5e4cd69da3b447ca3c7d93f92:timer', 'i:1746082250;', 1746082250),
('4dfa449af82ff8e41f88759b049017679826c715', 'i:1;', 1730570215),
('4dfa449af82ff8e41f88759b049017679826c715:timer', 'i:1730570215;', 1730570215),
('765875b1dc6b7a98610363859e286b106c438398', 'i:1;', 1734630377),
('765875b1dc6b7a98610363859e286b106c438398:timer', 'i:1734630377;', 1734630377),
('c378a17aa413e39ba83cca53f74cfc77ef65da7e', 'i:1;', 1745208181),
('c378a17aa413e39ba83cca53f74cfc77ef65da7e:timer', 'i:1745208181;', 1745208181),
('edba75e5014725ad4eb5bca58f876a590c762dca', 'i:1;', 1733149509),
('edba75e5014725ad4eb5bca58f876a590c762dca:timer', 'i:1733149509;', 1733149509),
('listeners_24', 'i:45;', 1747312362),
('listeners_26', 'i:85;', 1737505311),
('listeners_29', 'i:73;', 1747955294),
('listeners_30', 'i:97;', 1747843137),
('listeners_31', 'i:27;', 1746825934),
('listeners_32', 'i:74;', 1748023861),
('listeners_33', 'i:70;', 1747837833),
('listeners_34', 'i:96;', 1748022240),
('listeners_35', 'i:27;', 1736835047),
('listeners_36', 'i:69;', 1748021232),
('listeners_37', 'i:52;', 1727312716),
('listeners_38', 'i:96;', 1747837804),
('listeners_39', 'i:6;', 1747728443),
('listeners_40', 'i:16;', 1748043841),
('listeners_41', 'i:94;', 1747953428),
('listeners_42', 'i:91;', 1747839466),
('listeners_44', 'i:100;', 1747838900),
('listeners_45', 'i:9;', 1747838967),
('listeners_46', 'i:66;', 1740921254),
('listeners_47', 'i:86;', 1747422544),
('listeners_50', 'i:96;', 1747838957),
('listeners_51', 'i:3;', 1748059879),
('listeners_52', 'i:68;', 1747863962),
('listeners_53', 'i:1;', 1747839362),
('listeners_56', 'i:61;', 1746810597),
('listeners_57', 'i:1;', 1747839531);

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
-- Estructura de tabla para la tabla `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `is_full_bg` int(11) NOT NULL DEFAULT 0,
  `ui_top_chart` int(11) NOT NULL DEFAULT 1,
  `ui_genre` int(11) NOT NULL DEFAULT 1,
  `ui_favorite` int(11) NOT NULL DEFAULT 1,
  `ui_themes` int(11) NOT NULL DEFAULT 2,
  `ui_detail_genre` int(11) NOT NULL DEFAULT 1,
  `ui_player` int(11) NOT NULL DEFAULT 1,
  `ui_search` int(11) NOT NULL DEFAULT 1,
  `app_type` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `configs`
--

INSERT INTO `configs` (`id`, `is_full_bg`, `ui_top_chart`, `ui_genre`, `ui_favorite`, `ui_themes`, `ui_detail_genre`, `ui_player`, `ui_search`, `app_type`) VALUES
(1, 0, 2, 2, 2, 2, 2, 2, 2, 2);

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
-- Estructura de tabla para la tabla `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `isActive` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `genres`
--

INSERT INTO `genres` (`id`, `name`, `slug`, `img`, `isActive`, `created_at`, `updated_at`) VALUES
(24, 'Santo Domingo', 'santo-domingo', 'genres/logo sde.png', 1, '2024-09-14 04:51:33', '2024-09-24 18:46:59'),
(25, 'Santiago', 'santiago', 'genres/log santiago.png', 1, '2024-09-14 04:53:16', '2024-09-24 18:47:24'),
(26, 'Azua', 'azua', 'genres/ayuntamiento-de-azua-1.svg', 1, '2024-09-18 16:04:30', '2024-09-24 18:51:35'),
(27, 'Online', 'online', 'genres/online.jpg', 1, '2024-09-18 16:08:00', '2024-09-24 18:51:58');

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
(19, '0001_01_01_000000_create_users_table', 1),
(20, '0001_01_01_000001_create_cache_table', 1),
(21, '0001_01_01_000002_create_jobs_table', 1),
(22, '2024_08_20_023148_create_radios_table', 1),
(23, '2024_08_20_024159_create_genres_table', 1),
(24, '2024_08_20_024617_create_settings_table', 1),
(25, '2024_08_20_024832_create_themes_table', 1),
(26, '2024_08_20_025022_create_configs_table', 1),
(27, '2024_08_20_033227_create_new_radio_cats_table', 1),
(28, '2024_08_22_031043_add_timestamps_to_genres_table', 2),
(29, '2024_08_22_031602_add_timestamps_to_radios_table', 3),
(30, '2024_08_23_045708_update_empty_slugs_in_radios_table', 4),
(31, '2024_09_11_152235_add_rating_and_description_to_radios_table', 5);

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
-- Estructura de tabla para la tabla `radios`
--

CREATE TABLE `radios` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `tags` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bitrate` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type_radio` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `source_radio` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `link_radio` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_agent_radio` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `url_facebook` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `url_twitter` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `url_instagram` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `url_website` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `isFeatured` int(11) NOT NULL DEFAULT 0,
  `isActive` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `radios`
--

INSERT INTO `radios` (`id`, `name`, `slug`, `tags`, `bitrate`, `img`, `type_radio`, `source_radio`, `link_radio`, `user_agent_radio`, `url_facebook`, `url_twitter`, `url_instagram`, `url_website`, `isFeatured`, `isActive`, `created_at`, `updated_at`, `rating`, `description`) VALUES
(24, 'Alofoke Radio FM', 'alofoke-radio-fm', 'Urbana, Dembow, Reggaeton, Tropical, alofoke fm en vivo', '99.3 MHz', 'radios/logo alofoke.webp', 'MP3', 'Shoutcast', 'https://radiordomi.com:8566/stream', 'Shoutcast V2', 'https://facebook.com', 'https://x.com', 'https://www.instagram.com/alofokeradiofm/', 'https://www.alofoke.fm/', 1, 1, '2024-09-04 00:25:29', '2025-04-21 04:08:15', 5, '<p>radio alofoke fm en vivo, la estación líder en Santo Domingo que combina lo mejor del reggaetón, hip-hop, bachata y noticias de última hora. Disfruta de programas únicos y entrevistas exclusivas con los artistas más populares. ¡Vive el entretenimiento más picante de la radio dominicana!&nbsp;</p>'),
(26, 'Romantica 90 FM', 'romantica-90-fm', 'baladas, Boleros, latin pop, soft rock', '128 bitrate', 'radios/logog.romantica.webp', 'MP3', 'SonicPanel', 'https://puertoplataradio.com/8016/stream', 'Shoutcast V2', 'https://www.facebook.com/romantica90', 'https://x.com/romantica90fm', 'https://www.instagram.com/romantica90fm/', 'https://romantica90.com', 1, 1, '2024-09-04 01:29:13', '2024-09-24 18:54:06', 5, '<p>&nbsp;Escucha Romántica 90 FM, la emisora que te conecta con las mejores baladas y boleros. Disfruta de una fina selección de música romántica diseñada para enamorados, al ritmo de tu corazón. Sintoniza en línea y vive una experiencia musical única con los éxitos más grandes del género romántico.&nbsp;</p>'),
(29, 'La Z Digital FM', 'la-z-digital-fm', 'Noticias, Deporte, ', '101.3 MHz', 'radios/logo zdigital.jpg', 'MP3', 'Shoutcast', 'https://27383.live.streamtheworld.com/Z101FM.mp3', 'shoutcast v2', 'https://www.facebook.com/z101digital/', 'https://x.com/z101digital', 'https://domiradios.com', 'https://z101digital.com/', 1, 1, NULL, '2024-10-11 03:44:44', 4, '<p>&nbsp;Descubre La Z Digital FM, la emisora líder en República Dominicana que te ofrece una mezcla única de noticias en vivo, análisis político, deportes, y entretenimiento. Sintoniza programas icónicos como \'El Gobierno de la Mañana\' para estar al día con la actualidad nacional e internacional, todo en un solo lugar.&nbsp;</p>'),
(30, 'Latina 104 FM', 'latina-104-fm', 'reggaeton, salsa, merengue, bachata, balada, tropical', '104.3 MHz', 'radios/latina 104.jfif', 'MP3', 'Shoutcast', '	https://radio.dominiserver.com/proxy/latina104?mp=/stream', 'Shoutcast V2', 'https://www.facebook.com/latina104fm/', 'https://x.com/Latina104radio', 'https://www.instagram.com/', 'https://latina104.net/', 0, 1, '2024-09-11 21:30:35', '2024-09-24 18:56:00', 3, '<p>&nbsp;Conéctate con Latina 104 FM y disfruta de una selección variada de merengue, bachata, salsa y reggaetón. La emisora que te acompaña con lo mejor de la música tropical y latina, transmitiendo desde Santo Domingo para todos los amantes de los ritmos caribeños.&nbsp;</p>'),
(31, 'Ternura 89 FM', 'ternura-89-fm', 'Tropical, Baladas, Noticias, Informativa', '89.1 MHz', 'radios/ternura-fm-89-1-en-vivo.jpg', 'MP3', 'Shoutcast', 'https://iptvlatinoplus.com/?url=http://ny2.radiocast.us:8033/stream', 'Shoutcast V2', NULL, NULL, NULL, 'http://ternura89.com/front', 0, 1, '2024-09-18 16:29:40', '2024-09-24 19:01:33', 4, '<p>&nbsp;Ternura 89.1 FM, transmitiendo en vivo desde Azua, ofrece una programación diversa con la mejor selección de baladas románticas y contenido informativo. Disfruta de una mezcla única de música tropical y noticias regionales, creada para acompañarte en cada momento del día.&nbsp;</p>'),
(32, 'Expreso FM', 'expreso-fm', 'Tropical, merengues, Bachatas, Baladas,', '104.7 MHz', 'radios/expreso fm azua.webp', 'MP3', 'Shoutcast', 'https://stream-174.zeno.fm/q3nh1x9nmueuv?zt', 'Shoutcast V2', NULL, NULL, NULL, 'https://www.expresoazua.com/', 0, 1, '2024-09-18 16:47:26', '2024-09-24 19:02:27', 3, '<p>&nbsp;Expreso FM 104.7 transmite desde Azua, República Dominicana, con una vibrante mezcla de merengue, bachata y baladas. Escucha en vivo y disfruta de la mejor música tropical y los programas interactivos que acompañan a los oyentes las 24 horas del día. ¡Conéctate y vive la energía de Expreso FM!&nbsp;</p>'),
(33, 'Radio Caracol AM', 'radio-caracol-am', 'Cristiana, música cristiana, clásicos y oldies', '1200 KHz', 'radios/radio caracol am azua.jpg', 'MP3', 'Shoutcast', 'https://rs4.domint.net:8108/stream', 'Shoutcast V2', 'https://www.facebook.com/radiofonicasrd', NULL, NULL, 'https://empresasradiofonicas.com.do/emisora/radio-caracol/', 0, 1, '2024-09-18 16:56:32', '2024-09-24 19:03:38', 2, '<p>&nbsp;Radio Caracol 1200 AM transmite desde Azua, República Dominicana, ofreciendo una variada programación de música cristiana, clásicos y oldies. Sintoniza en vivo para disfrutar de contenido inspirador y mensajes cristianos que llegan a cada hogar. Escucha online y acompáñanos en la difusión de la palabra de Dios.&nbsp;</p>'),
(34, 'La Kalle FM Azua ', 'la-kalle-fm-azua', 'Urbana, Tropical, dembow, Reggaeton, Salsa, Merengue', '96.3 MHz', 'radios/la kalle azua.png', 'MP3', 'Icecast', 'https://radio.telemicro.com.do/lakalleazua', 'Shoutcast V2', NULL, NULL, NULL, 'https://emisorastelemicro.com/station/la-kalle-azua/', 0, 1, '2024-09-18 17:34:14', '2024-09-24 19:05:12', 4, '<p>&nbsp;La Kalle 96.3 FM, la emisora de Azua que te trae lo mejor de la música urbana y tropical. Disfruta de los éxitos de reggaetón, hip-hop, bachata y merengue en una programación vibrante que mantiene el ritmo de República Dominicana. Escucha en vivo y mantente al día con los artistas y géneros más populares.&nbsp;</p>'),
(35, 'Orginal FM Azua', 'orginal-fm-azua', 'Tropical, bachata, merengue, música tropical, urbana', '107.3 MHz', 'radios/original azua.png', 'MP3', 'Icecast', 'https://radio.telemicro.com.do/originalazua', 'Shoutcast V2', NULL, NULL, NULL, 'https://emisorastelemicro.com/station/original-azua/', 0, 1, '2024-09-18 17:55:00', '2024-09-24 19:05:43', 3, '<p><em>Original 107.3 FM</em> es una emisora perteneciente al Grupo Telemicro que transmite desde la región sur de la República Dominicana. Su programación está enfocada en una amplia variedad de géneros musicales, que incluyen bachata, merengue, música tropical, urbana y más.</p>'),
(36, 'Primera FM Azua', 'primera-fm-azua', 'Baladas, baladas románticas, pop, boleros', '88.1 MHz', 'radios/primera fm azua.jpg', 'MP3', 'Icecast', 'https://radio.telemicro.com.do/primeraazua', 'Shoutcast V2', NULL, NULL, NULL, 'https://emisorastelemicro.com/station/primera-fm-azua/', 0, 1, '2024-09-18 18:04:59', '2024-09-24 19:06:23', 4, '<p><em>Primera FM 88.1 Azua&nbsp;</em>transmite desde Azua, República Dominicana. Con una programación de baladas románticas, pop y boleros, es conocida como \"la radio más romántica de Azua.\" La estación ofrece un enfoque en géneros suaves y populares, como baladas y música romántica.</p>'),
(37, 'Primera FM Sto Dgo', 'primera-fm-sto-dgo', 'Baladas, Romanticas, Emisoras Romanticas, emisora Suave', '88.1 FM', 'radios/primera fm sto-dgo.png', 'MP3', 'Other', 'https://n03.radiojar.com/5705c71sn2zuv', 'Shoutcast V2', NULL, NULL, NULL, 'https://emisorastelemicro.com/station/primera-fm-88-1/', 1, 1, '2024-09-25 23:32:18', '2024-09-26 01:25:09', 5, '<p><strong>Primera FM 88.1</strong>, la emisora romantica en <strong>Santo Domingo</strong> con una selección única de <strong>música romántica, pop latino, y baladas</strong>, mejor música de la <strong>República Dominicana</strong>. Primera FM, tu estación favorita las 24 horas, te conecta con lo mejor de la radio dominicana. ¡Sintoniza y disfruta de la diferencia musical!&nbsp;</p>'),
(38, 'Los 40 FM', 'los-40-fm', 'Música Latina, Música Mundial, Pop Latino, Top 40', '88.5 MHz', 'radios/los 40.png', 'MP3', 'Shoutcast', 'https://radio4.domint.net:8114/stream', 'Shoutcast V2', 'https://www.facebook.com/Los40RD/', 'https://x.com/LOS40_RD', NULL, 'https://www.los40.do', 0, 1, '2024-09-26 01:24:04', '2024-09-26 01:27:36', 4, '<p><strong>Los 40 88.1 FM</strong>, la emisora juvenil preferida en <strong>Santo Domingo</strong>. Disfruta de los últimos éxitos de <strong>pop latino, reggaetón, y música electrónica</strong>. Conéctate a las mejores entrevistas, noticias del entretenimiento y los rankings de <strong>Top 40</strong>. Sintoniza ahora y mantente al día con los hits más sonados de la <strong>República Dominicana</strong></p>'),
(39, 'Escape FM ', 'escape-fm', 'Musica Pop, Emisora de Rock, Reggaeton', '88.9 MHz', 'radios/escape 88.9.jfif', 'MP3', 'Shoutcast', 'https://radio4.domint.net:8094/stream', 'Shoutcast V2', 'https://www.facebook.com/Escape889/', 'https://x.com/escape889', NULL, 'https://www.rdmusica.com/', 0, 1, '2024-09-26 02:21:47', '2024-09-26 02:21:47', 3, '<p>&nbsp;<strong>Escape 88.9 FM</strong> es una <strong>Emisora de Santo Domingo</strong> con una selección única de <strong>pop y rock</strong> en inglés y español. Disfruta de música sin interrupciones y programas en vivo las 24 horas. Escápate al ritmo de los <strong>80s, 90s</strong>, y los hits actuales que marcan tendencia en la <strong>República Dominicana</strong>. ¡Solo música, siempre hits!&nbsp;</p>'),
(40, 'Sentido FM', 'sentido-fm', 'Música romántica, Baladas clásicas, contemporáneas, Emisora de radio, romántica Santo Domingo\nSentido FM República Dominicana\nRadio online de baladas románticas', '89.3 MHz', 'radios/sentido 89.png', 'MP3', 'Shoutcast', 'https://radio4.domint.net:8158/stream', 'Shoutcast V2', NULL, 'https://x.com/sentido893fm', NULL, 'https://sentido893.com/', 0, 1, '2024-09-26 02:43:29', '2024-09-26 02:43:29', 3, '<p><strong>Sentido 89.3 FM</strong>, la estación romántica de <strong>Santo Domingo</strong> que combina los mejores éxitos de baladas y sonidos contemporáneos. Desde clásicos inolvidables hasta los temas más recientes, conecta tus emociones con la música que te acompaña durante el día. Disfruta de una transmisión en vivo y sin interrupciones que te llevará a momentos únicos en la <strong>República Dominicana</strong>.</p>'),
(41, 'Renuevo Fm', 'renuevo-fm', 'Radio cristiana, Música Cristiana, Renuevo 89.7, programación espiritual,Valores cristianos, Emisora cristiana República Dominicana', '89.7 MHz', 'radios/radio-renuevo-89-7-fm-en-vivo.jpg', 'MP3', 'Shoutcast', 'https://radio3.domint.net:8116/stream', 'Shoutcast V2', NULL, NULL, NULL, 'https://www.renuevo897.com/', 0, 1, '2024-09-26 03:17:36', '2024-09-26 03:18:34', 4, '<p><strong>Renuevo 89.7 FM</strong>, la emisora cristiana de <strong>Santo Domingo Oeste</strong>, que ofrece una programación inspiradora enfocada en música gospel y valores espirituales. Conéctate a contenido edificante, reflexiones que transforman vidas y mensajes centrados en Jesucristo. Renuevo FM es tu compañera ideal para fortalecer tu fe y vivir una experiencia de radio que inspira esperanza y unidad familiar.&nbsp;</p>'),
(42, 'Fuego 90 FM', 'fuego-90-fm', 'salsa, tropical, salsera, Fuego 90 salsa, Salsa y bachata en Fuego 90\nRadio Fuego 90 en vivo, Música tropical', '90.1 MHz', 'radios/fuego 90 fm.png', 'MP3', 'Shoutcast', 'https://radio5.domint.net:8110/stream', 'Shoutcast V2', NULL, 'https://x.com/fuego90fm01', NULL, 'https://www.rdmusica.com/', 0, 1, '2024-10-03 02:45:42', '2024-10-03 02:45:42', 5, '<p><strong>Fuego 90.1 FM</strong>, \"La Salsera\", la emisora líder en <strong>Santo Domingo</strong> para disfrutar de los mejores éxitos de <strong>salsa, bachata, merengue</strong> y más. Con programas dinámicos y música tropical las 24 horas, Fuego 90 es tu conexión con lo mejor de la música latina. Vive la energía del Caribe con una experiencia musical única.&nbsp;</p>'),
(43, 'Estrella 90 FM', 'estrella-90-fm', 'Baladas pop, en Estrella 90,\nRadio adulto, contemporáneo Santo Domingo,\nEstrella 90 FM éxitos,\nMúsica en inglés y español,\nEstrella 90.5 en vivo,', '90.5 MHz', 'radios/Estrella-90.jpg', 'MP3', 'SonicPanel', 'https://stream-145.zeno.fm/t8r7m90te0quv', 'Shoutcast V2', 'https://www.facebook.com/estrella905fm', 'https://x.com/Estrella90FM', NULL, 'https://estrella90.com/', 0, 1, '2024-10-03 03:49:07', '2024-10-03 03:58:51', 4, '<p><strong>Estrella 90.5 FM</strong>, la emisora líder en <strong>Santo Domingo</strong> con una mezcla única de <strong>baladas pop, rock adulto contemporáneo</strong> y los éxitos más recientes. Disfruta de una programación dirigida a jóvenes y adultos contemporáneos, con música en inglés y español. Estrella 90, donde la música nunca pasa de moda.&nbsp;</p>'),
(44, 'La 91 FM', 'la-91-fm', 'Musica Pop,\nLa 91 FM en vivo,\nMúsica pop rock,\néxitos en inglés,\nProgramación variada,', '91.3 MHz', 'radios/logo la 91 fm.png', 'MP3', 'Shoutcast', 'https://stream-147.zeno.fm/u6o7ex59x8ttv', 'Shoutcast V2', 'https://www.facebook.com/profile.php?id=100063607871685', 'https://x.com/la913fm?lang=es', 'https://www.instagram.com/la91fm/', 'https://www.la91fm.com/', 0, 1, '2024-10-08 02:59:23', '2024-10-08 03:02:50', 4, '<p><strong>La 91.3 FM</strong>, la emisora pionera en la radio dominicana, que transmite lo mejor de la música pop y rock en inglés desde 1985. Disfruta de una programación variada con éxitos clásicos y contemporáneos, noticias y entretenimiento para todo el país. <strong>La 91 FM</strong>, llega a ti donde quiera que estés, combinando tradición y modernidad&nbsp;</p>'),
(45, 'La Rocka 91 FM', 'la-rocka-91-fm', 'Música rock, pop en inglés,\nRadio juvenil,\nradio de rock, R&B en vivo,', '91.7 MHz', 'radios/la roka 91.jpg', 'MP3', 'Shoutcast', 'https://iptvlatinoplus.com/?url=http://5.135.183.124:8243/stream', 'Shoutcast V2', 'https://www.facebook.com/Larocka917rd', 'https://x.com/larocka917rd', NULL, 'https://larocka917.com/', 0, 1, '2024-10-08 03:15:24', '2024-10-08 03:17:49', 3, '<p><strong>La Rocka 91.7 FM</strong>, la emisora más dinámica de la cuidad de&nbsp;<strong>Santo Domingo</strong>, transmitiendo las 24 horas con una mezcla única de <strong>rock, pop, hip-hop, R&amp;B</strong>, y los mejores géneros actuales. Con música en inglés y una programación variada, <strong>La Rocka</strong> es el lugar perfecto para disfrutar de lo mejor del entretenimiento musical juvenil.&nbsp;</p>'),
(46, 'Hits 92 FM', 'hits-92-fm', 'merengue, bachata, salsa, reggaetón, dembow', '92.1 MHz', 'radios/hits-92-fm-en-vivo.jpg', 'MP3', 'Icecast', 'https://rgradio.net/hits92/hits92', 'Icecast 2', 'https://www.facebook.com/people/Hits-RD', NULL, 'https://www.instagram.com/hits92fm', 'https://hits92.com/', 0, 1, '2024-10-11 02:16:57', '2024-10-11 02:16:57', 5, '<p><strong>Hits 92.1 FM</strong>, la emisora número uno en música variada en <strong>Santo Domingo</strong>. Disfruta de los mejores éxitos de <strong>merengue, bachata, salsa, reggaetón, dembow</strong> y más, con programas dedicados al entretenimiento, deportes y noticias. <strong>Hits 92</strong>, la radio que te acompaña las 24 horas con lo mejor de la música tropical.&nbsp;</p>'),
(47, 'CDN Radio FM', 'cdn-radio-fm', ' deportes, salud, política, familia, Radio informativa CDN 92.5 FM', '92.5 MHz', 'radios/cdn-92-5-fm.jpg', 'MP3', 'Icecast', 'https://play.cdnradio.com.do/cdnlive', 'Icecast 2', 'https://www.facebook.com/CDNRADIO', 'https://x.com/CDNRadio', 'https://www.instagram.com/cdnradio/', 'https://cdnradio.com.do/', 0, 1, '2024-10-11 02:33:00', '2024-10-11 02:33:00', 4, '<p>&nbsp;Escucha en vivo <strong>CDN Radio 92.5 FM</strong>, la emisora líder en noticias y entretenimiento en <strong>Santo Domingo</strong>. Con una programación diversa que incluye <strong>deportes, salud, política y familia</strong>, CDN te mantiene informado con las últimas noticias y análisis. Sintoniza a través de la radio o en línea, y sigue nuestros programas especializados en todo momento.&nbsp;</p>'),
(48, 'Pura Vida 92 FM', 'pura-vida-92-fm', 'cristiana, reflexiones, mensajes de fe, Pura Vida FM en vivo, Emisora cristiana 92.9 FM', '92.9 MHz', 'radios/pura-vida-967-fm.png', 'MP3', 'SonicPanel', 'https://sonic.radiostreaminglatino.com/8294/stream', 'Sonic Panel', 'https://www.facebook.com/PuraVida929/', NULL, 'https://www.instagram.com/puravidafm/', 'https://puravidafm.net/', 0, 1, '2024-10-11 02:57:13', '2024-10-11 02:57:13', 4, '<p><strong>Pura Vida 92.9 FM</strong>, la emisora cristiana líder en <strong>Santo Domingo</strong>. Disfruta de una programación inspiradora con música cristiana, reflexiones y mensajes de fe. Conéctate a través de 92.9 FM para recibir una dosis diaria de motivación espiritual, valores familiares y crecimiento personal. ¡Pura Vida, sin límites!&nbsp;</p>'),
(49, 'Independencia FM', 'independencia-fm', 'tropical, merengue, bachata, salsa', '93.3 MHz', 'radios/Independencia 93.png', 'MP3', 'Icecast', 'https://radio.telemicro.com.do/inde', 'Icecast 2', NULL, NULL, NULL, 'https://emisorastelemicro.com/station/independencia-93-3/', 0, 1, '2024-10-11 03:09:12', '2024-10-11 03:09:12', 5, '<p><strong>Independencia 93.3 FM</strong>, la emisora tropical líder en <strong>Santo Domingo</strong>. Disfruta de una programación variada que incluye los mejores éxitos de <strong>merengue, bachata, salsa</strong>, y otros ritmos tropicales. Conéctate con la cultura dominicana a través de la música y programas que destacan lo mejor del entretenimiento y noticias locales. ¡Vive el ritmo de Independencia!&nbsp;</p>'),
(50, 'Ultra 93.7 FM', 'ultra-937-fm', 'Pop, Top 40 música pop, musica ingles, urbana', '93.7 MHz', 'radios/ultra97.3fm.jpg', 'MP3', 'Shoutcast', 'https://iptvlatinoplus.com/?url=http://72.29.87.97:8010/stream', 'Shoutcast V2', NULL, NULL, 'https://www.instagram.com/ultra93.7fm/', 'https://ultrafm.com/', 0, 1, '2024-10-11 03:32:55', '2024-10-11 03:36:00', 3, '<p><strong>Ultra 93.7 FM</strong>, la emisora pop líder en <strong>Santo Domingo</strong> con la mejor música en español e inglés. Disfruta de los éxitos <strong>Top 40</strong> y lo último en pop, además de concursos, noticias y entrevistas exclusivas. ¡Vive la música con Ultra 93.7 FM, más que música!&nbsp;</p>'),
(51, 'Fidelity 94.1 FM', 'fidelity-941-fm', 'Baladas pop, pop en español, Fidelity 94.1 en vivo', '94.1 MHz', 'radios/fidelity 94.png', 'MP3', 'Icecast', 'https://26583.live.streamtheworld.com/FIDELITY_SC', 'Icecast 2', 'https://www.facebook.com/Fidelity941FM/', 'https://x.com/fidelity941', NULL, 'https://fidelityfm.com.do/', 0, 1, '2024-10-19 20:08:52', '2024-10-19 20:08:52', 3, '<p><strong>Fidelity 94.1 FM</strong>, la emisora líder en <strong>baladas y pop romántico</strong> en <strong>Santo Domingo</strong>. Disfruta de la mejor música romántica y pop en inglés y español, con programas icónicos como <strong>Dando Candela</strong> y <strong>El Matutino Alternativo</strong>. Fidelity 94.1, tu estación de música versátil que te acompaña en cada momento del día.&nbsp;</p>'),
(52, 'KQ 94.5 FM', 'kq-945-fm', 'Urbana, Reguetón, dembow, Emisora urbana', '94.5 MHz', 'radios/KQ (94.5).webp', 'MP3', 'Shoutcast', 'https://radio.yaservers.com:9990/stream', 'Shoutcast V2', 'https://www.facebook.com/people/KQ945FM', NULL, 'https://www.instagram.com/kq94.5', 'https://kq94.net', 1, 1, '2024-11-02 18:19:54', '2024-11-02 18:20:53', 5, '<p><strong>KQ 94.5 FM</strong>, la emisora líder en <strong>Santo Domingo</strong> que ofrece lo mejor de la <strong>música urbana</strong>. Disfruta de los últimos éxitos de <strong>reguetón, dembow y trap</strong>, además de programas interactivos y noticias del entretenimiento. Con locutores dinámicos y una programación variada, KQ 94.5 FM te mantiene conectado con las tendencias musicales actuales.</p>'),
(53, 'La Nota 95 FM', 'la-nota-95-fm', 'adulto Contemporaneo, top-40, La Nota 95.7 en vivo, Radio de Santo Domingo', '95.7 MHz', 'radios/lanota957.webp', 'MP3', 'Shoutcast', 'https://radios.xumcast.live/proxy/hilnplay/live', 'Shoutcast V2', NULL, NULL, NULL, 'https://lanota957fm.com/', 0, 1, '2024-12-02 16:16:55', '2024-12-02 16:16:55', 5, '<p><strong>La Nota 95.7 FM</strong>, la emisora de Santo Domingo que te ofrece una programación variada con información, entretenimiento y los éxitos musicales del momento. Disfruta de programas destacados como \"Esto No Tiene Nombre\" y \"Cuentas Claras\", que mantienen a la audiencia informada y entretenida las 24 horas del día.</p>'),
(54, 'Quisqueya 96.1 FM', 'quisqueya-961-fm', 'adulto contemporáneo, Quisqueya 96.1 en vivo, Emisora 96.1 FM', '96.1 MHz', 'radios/quisqueya-961.webp', 'MP3', 'Shoutcast', 'https://protvradiostream.com:8620/stream', 'Shoutcast V2', 'https://www.facebook.com/quisqueyafm', 'https://twitter.com/QuisqueyaRDFM', NULL, 'https://quisqueyafmrd.com/', 0, 1, '2024-12-02 16:27:16', '2024-12-02 16:27:16', 3, '<p><strong>Quisqueya 96.1 FM</strong>, la emisora cultural de Santo Domingo que ofrece una programación variada para el público adulto contemporáneo. Disfruta de géneros musicales nacionales e internacionales, con énfasis en la música antillana, y mantente conectado con las tendencias musicales mundiales.</p>'),
(55, 'Rumba 96 FM', 'rumba-96-fm', 'Ritmo 96 en vivo, variedad, salsa, merengue, bachata, música urbana, Emisora 96.5 FM', '96.5 MHz', 'radios/ritmo96.webp', 'MP3', 'Other', 'https://stream-146.zeno.fm/y0br5ck4ququv', 'Zeno Radio', 'https://www.facebook.com/ritmo96', NULL, 'https://twitter.com/ritmo96', 'https://ritmo96.com/', 0, 1, '2024-12-02 16:40:40', '2024-12-02 16:40:40', 5, '<p><strong>Ritmo 96.5 FM</strong>, la emisora líder en <strong>Santo Domingo</strong> que te ofrece lo mejor de la <strong>música joven</strong>. Disfruta de una programación variada que incluye <strong>salsa, merengue, bachata, música urbana</strong> y los éxitos más recientes en inglés. Con programas destacados como \"Ritmo de la Mañana\", Ritmo 96.5 FM te mantiene al día con las tendencias musicales actuales.</p>'),
(56, 'Dominicana FM', 'dominicana-fm', 'Noticias, Tropical, Noticias', '98.9 MHz', 'radios/Dominicana fm.png', 'MP3', 'Shoutcast', 'https://protvradiostream.com:8610/stream', 'Shoutcast V2', NULL, NULL, NULL, 'https://dominicanafmrd.com/', 0, 1, '2024-12-19 18:01:57', '2024-12-19 18:12:36', 4, '<p><strong>Dominicana FM 98.9</strong>, la emisora de la <strong>Corporación Estatal de Radio y Televisión (CERTV)</strong> que transmite desde <strong>Santo Domingo</strong>. Disfruta de una programación variada que incluye <strong>música, noticias y entretenimiento</strong>, disponible las 24 horas en las frecuencias <strong>98.9 FM</strong> y <strong>99.9 FM</strong> para todo el país, y en línea a través de <a href=\"http://www.dominicanafmrd.com/\">dominicanafmrd.com</a>.&nbsp;</p>'),
(57, 'BE 99 FM', 'be-99-fm', 'Rock, Hip Hop, Pop', '99.7 MHz', 'radios/be 99.png', 'MP3', 'Shoutcast', 'https://ca5ssl.rcast.net/stream/61186.mp3', 'Shoutcast V2', 'https://www.facebook.com/be997/', NULL, NULL, 'https://be997.com/', 0, 1, '2024-12-19 18:24:54', '2024-12-19 18:24:54', 2, '<p><strong>Be 99.7 FM</strong>, la emisora de Santo Domingo que te ofrece una cuidada selección de <strong>música en inglés</strong> dirigida a un público <strong>adulto joven</strong>. Anteriormente conocida como Radio Listín, Be 99.7 FM se ha renovado para brindarte una experiencia musical fresca y contemporánea.&nbsp;</p>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radios_cat`
--

CREATE TABLE `radios_cat` (
  `id` int(11) NOT NULL,
  `radio_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `radios_cat`
--

INSERT INTO `radios_cat` (`id`, `radio_id`, `genre_id`) VALUES
(37, 27, 19),
(39, 28, 20),
(45, 24, 24),
(47, 29, 24),
(48, 30, 24),
(49, 26, 27),
(50, 31, 26),
(51, 32, 26),
(52, 33, 26),
(53, 34, 26),
(54, 35, 26),
(55, 36, 26),
(56, 37, 24),
(57, 38, 24),
(58, 39, 24),
(59, 40, 24),
(60, 41, 24),
(61, 42, 24),
(62, 43, 24),
(63, 44, 24),
(64, 45, 24),
(65, 46, 24),
(66, 47, 24),
(67, 48, 24),
(68, 49, 24),
(69, 50, 24),
(70, 51, 24),
(71, 52, 24),
(72, 53, 24),
(73, 54, 24),
(74, 55, 24),
(75, 56, 24),
(76, 57, 24);

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
('58pdxes4waa7TnX4F5pQBqdtNhqARgoSfvdwTEWV', NULL, '52.167.144.161', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUEzRjBBTnlFV3pKeDloU2R6cVVBNVMxZ293QnpPeGpBTjBtN1JIRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vYXBpL3JhZGlvL2N1cnJlbnQtdHJhY2svNTYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748059554),
('74hbT8OPw5wIr8FFHZUNvntFAzxQyktQMCYZ2nlg', NULL, '2001:19f0:b001:3de:5400:3ff:fe9e:32e0', 'Mozilla/5.0 (compatible; monitoring360bot/1.1; +https://app.360monitoring.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMm95SWQ3Ym03UWI2d3U5VHh3RXdwRjVHeVJHbGVXUWhMb0lHaGVnSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748060220),
('aECTibUC1W6vCtiAOrv70Ophm4QV3eBUVoMYVHQe', NULL, '205.210.31.155', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUZJSDJsY2ZlZlM4cEtqb1dxVmdMV0lOZVhpMDl0UXJKd3NxM3RlcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748063211),
('cvyKKdodY4J8TPWWqSzPElXaIlef8HCMHCF111Ur', NULL, '52.167.144.177', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkhIUUg5NDZUbnBCZXBUdVE2b3BKRkd3N3ZvdHplcUtoaDZPSW9NZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vZW1pc29yYXMvZG9taW5pY2FuYS1mbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748059507),
('DfRi9Lm15z3JpcvJL175b1kRCGOsbiiC5bQuQhAS', NULL, '2001:19f0:b001:3de:5400:3ff:fe9e:32e0', 'Mozilla/5.0 (compatible; monitoring360bot/1.1; +https://app.360monitoring.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemlYU2R1WHk2amZMREh2MnpFclFYTDJTaGR6c3RMbXBRZkFVaElUTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748058829),
('DGhMcMtmTwo29kwRfb3etCZlP35kdZbZaraGKlF9', NULL, '52.167.144.137', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2hES0lPbVp6cnN3OW1XQnNNbUZ3c0c2QW42WVVNcVBXVlJqSmxyUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vYXBpL3JhZGlvL2N1cnJlbnQtdHJhY2svNTEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748059819),
('Ei0L8q5l8uQ6olZuQDnKPB6RJWszeOtXDINrcRbi', NULL, '185.132.187.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 CCleaner/130.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRzJIbkR3SHJBSDdqR1JuN0dKZk1ON3d6cE13YVF3RW1oZGxyVkt6VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748065031),
('Fw7W0iNsPmEIm8oFl3sKV39YrGHmALIHTQ0RBLLV', NULL, '35.229.78.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiRDVOR2lKOTdydW1qelNISFp4bjU3enBCTlpOWFhyQ2FUZ1F1UmtPRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1748063258),
('Gr7Vx2KnriYp1r9OVdzaytXeVUyZAVxj2qe7wjIk', NULL, '166.108.233.222', 'Mozilla/5.0 (Windows NT 5.0; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnpXakVKQzNsWXNvUmNKWE50UDM2ZGIwc3BhNFVHVUFPN2JqcmpjNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vZW1pc29yYXMvYWxvZm9rZS1yYWRpby1mbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748064773),
('KY8vCnKqch98x4ykrBE0v6udFWgnoBif1YgvcCxd', NULL, '3.252.74.41', 'Plesk screenshot bot https://support.plesk.com/hc/en-us/articles/10301006946066', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnlITDNYT3ZCQXdJc3ZGbTdETkF5dXJ1MVgyYzlaZ3dGUDJJUWRkcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748066379),
('ncecAtXH56KXGOZAem3PyY4bzFfyRoGLkFu1Qotr', NULL, '51.222.253.5', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDg0aU9rVGpRY1lWSTlyM04zYnlSdUVnNlZWT1Jzb3F4MU5GU204biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjE6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vaW5kZXgucGhwL2VtaXNvcmFzL2luZGVwZW5kZW5jaWEtZm0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748065307),
('NfNdVpI6RGZr4day1bHajwhSIZ5QnfwQtuWdcAbb', NULL, '43.130.102.7', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzBTOXk4Zmx4Q05xNG40TEFuTFBoMG9Xd2R5dTd2SDJMNkVoUjZGTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748065820),
('ofgzdewaGIq8svwP9N6LMrWmYG1xH6VxqAfOKA6o', NULL, '100.27.233.136', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHNRRzlHbzNaOXFrNml5OGdicFdMOG5LOUNUaUNWck1raXVwRWhvZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748064139),
('ojJ4Egocne9RBdh9Xa4kIF5Nwm0iy8G2b7MQRppf', NULL, '2001:19f0:b001:3de:5400:3ff:fe9e:32e0', 'Mozilla/5.0 (compatible; monitoring360bot/1.1; +https://app.360monitoring.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREZxVllGR0dhNmFEb1F1d2Q0bGtpQ2tyYklPTkhOY1VWZHRFUVlRbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748065596),
('pTq8foU9p3n3G62e8zJ8hiTAzTW4LB3evwK7i0yC', NULL, '52.167.144.137', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTFlTng5N2tMRFhpdzV6N1kxMktyVGNBclpRZU9EWnpHMVJEajlJcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vZW1pc29yYXMvZmlkZWxpdHktOTQxLWZtIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1748059799),
('UQkVQO1gz5c1I0xSoNgxOjKV3eg9i8RToYL63APK', NULL, '2001:19f0:b001:3de:5400:3ff:fe9e:32e0', 'Mozilla/5.0 (compatible; monitoring360bot/1.1; +https://app.360monitoring.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0FHRDV4eEhmdW5SMnFxWHdNRVFKTkhvN1l1TE9wSzJaTjBZNnY1USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748063796),
('VaiJFuT5jN3kxGXTXgIfAkgev6ju0VtsyIEQaGHE', NULL, '2001:19f0:b001:3de:5400:3ff:fe9e:32e0', 'Mozilla/5.0 (compatible; monitoring360bot/1.1; +https://app.360monitoring.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDAzUTNHcEM2VUlJenEwd0xYd293Y3dLTU13R20wcWhZenNiRmpDTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748062020),
('vzdPVg9Wnyz4WfveQwDE6DnY67ybbt6NJyGklySi', NULL, '168.228.233.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEVRc1ZKMlRiaEY4aDFsclZqdjMxa0d1UWQ4UUhoZmlieU9qeFo0USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vYXBpL3JhZGlvL2N1cnJlbnQtdHJhY2svMjQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1748066400),
('zoq5iYIa2iAcI4KfAbMFgAZsNQZQYKs4b8X4GMmZ', NULL, '2a03:2880:27ff:70::', 'meta-externalagent/1.1 (+https://developers.facebook.com/docs/sharing/webmasters/crawler)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0ZaSHh5dU9BRlAzcER4cEJxSlFYa25qb1ZVUG1qWVNGdHNvakQ3TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vZG9taXJhZGlvcy5jb20uZG8vZW1pc29yYXMvYWxvZm9rZS1yYWRpby1mbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748062684);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_email` varchar(255) NOT NULL,
  `app_copyright` varchar(255) NOT NULL,
  `app_phone` varchar(255) NOT NULL,
  `app_website` varchar(255) NOT NULL,
  `app_facebook` varchar(255) NOT NULL,
  `app_twitter` varchar(255) NOT NULL,
  `app_term_of_use` text NOT NULL,
  `app_privacy_policy` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `app_name`, `app_email`, `app_copyright`, `app_phone`, `app_website`, `app_facebook`, `app_twitter`, `app_term_of_use`, `app_privacy_policy`) VALUES
(1, 'DomiRadios', 'contacto@domiradios.com.do', 'core2host.com.do', '+16173880102', 'https://domiradios.com.do', 'https://www.facebook.com/domiradios', 'https://twitter.com/domiradios', '<h2><strong>TERMS AND CONDITIONS</strong></h2>\r\n\r\n<p><strong>Introduction</strong></p>\r\n\r\n<p>These Website Standard Terms and Conditions written on this webpage shall manage your use of this website. These Terms will be applied fully and affect to your use of this Website. By using this Website, you agreed to accept all terms and conditions written in here. You must not use this Website if you disagree with any of these Website Standard Terms and Conditions.</p>\r\n\r\n<p>Minors or people below 12 years old are not allowed to use this Website.</p>\r\n\r\n<p><strong>Intellectual Property Rights</strong></p>\r\n\r\n<p>Other than the content you own, under these Terms, <strong>Domiradios&nbsp;</strong>and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>\r\n\r\n<p>You are granted limited license only for purposes of viewing the material contained on this Website.</p>\r\n\r\n<p><strong>Restrictions</strong></p>\r\n\r\n<p>You are specifically restricted from all of the following</p>\r\n\r\n<ul>\r\n	<li>publishing any Website material in any other media;</li>\r\n	<li>selling, sublicensing and/or otherwise commercializing any Website material;</li>\r\n	<li>publicly performing and/or showing any Website material;</li>\r\n	<li>using this Website in any way that is or may be damaging to this Website;</li>\r\n	<li>using this Website in any way that impacts user access to this Website;</li>\r\n	<li>using this Website contrary to applicable laws and regulations, or in any way may cause harm to the Website, or to any person or business entity;</li>\r\n	<li>engaging in any data mining, data harvesting, data extracting or any other similar activity in relation to this Website;</li>\r\n	<li>using this Website to engage in any advertising or marketing.</li>\r\n</ul>\r\n\r\n<p>Certain areas of this Website are restricted from being access by you and <strong>Domiradios</strong> may further restrict access by you to any areas of this Website, at any time, in absolute discretion. Any user ID and password you may have for this Website are confidential and you must maintain confidentiality as well.</p>\r\n\r\n<p><strong>Your Content</strong></p>\r\n\r\n<p>In these Website Standard Terms and Conditions, &ldquo;Your Content&rdquo; shall mean any audio, video text, images or other material you choose to display on this Website. By displaying Your Content, you grant <strong>Domiradios</strong> a non-exclusive, worldwide irrevocable, sub licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>\r\n\r\n<p>Your Content must be your own and must not be invading any third-party&rsquo;s rights. <strong>Domiradios&nbsp;</strong>reserves the right to remove any of Your Content from this Website at any time without notice.</p>\r\n\r\n<p><strong>No warranties</strong></p>\r\n\r\n<p>This Website is provided &ldquo;as is,&rdquo; with all faults, and <strong>Domiradios</strong> express no representations or warranties, of any kind related to this Website or the materials contained on this Website. Also, nothing contained on this Website shall be interpreted as advising you.</p>\r\n\r\n<p><strong>Limitation of liability</strong></p>\r\n\r\n<p>In no event shall <strong>Your Company</strong>, nor any of its officers, directors and employees, shall be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract. &nbsp;<strong>Domiradios</strong>, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this Website.</p>\r\n\r\n<p><strong>Indemnification</strong></p>\r\n\r\n<p>You hereby indemnify to the fullest extent <strong>Domiradios</strong> from and against any and/or all liabilities, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these Terms.</p>\r\n\r\n<p><strong>Severability</strong></p>\r\n\r\n<p>If any provision of these Terms is found to be invalid under any applicable law, such provisions shall be deleted without affecting the remaining provisions herein.</p>\r\n\r\n<p><strong>Variation of Terms</strong></p>\r\n\r\n<p><strong>Domiradios</strong> is permitted to revise these Terms at any time as it sees fit, and by using this Website you are expected to review these Terms on a regular basis.</p>\r\n\r\n<p><strong>Assignment</strong></p>\r\n\r\n<p>The <strong>Domiradios</strong> is allowed to assign, transfer, and subcontract its rights and/or obligations under these Terms without any notification. However, you are not allowed to assign, transfer, or subcontract any of your rights and/or obligations under these Terms.</p>\r\n\r\n<p><strong>Entire Agreement</strong></p>\r\n\r\n<p>These Terms constitute the entire agreement between <strong>Domiradios&nbsp;</strong>and you in relation to your use of this Website, and supersede all prior agreements and understandings.</p>\r\n', '<h1><strong>Privacy Policy</strong></h1>\r\n\r\n<p>Last updated: 14th July 2024</p>\r\n\r\n<h2><strong>Introduction</strong></h2>\r\n\r\n<p>This privacy policy sets out how Domiradios uses and protects any information that you give. We are committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this app, then you can be assured that it will only be used in accordance with this privacy statement. Domiradios may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.</p>\r\n\r\n<p><strong>Effective Date:</strong>&nbsp;14th of July, 2024</p>\r\n\r\n<h2><strong>Information We Collect</strong></h2>\r\n\r\n<p>We may collect the following information. We always strive to ask for the minimum amount of data to provide you with the best possible experience. Please refer to the app&#39;s permission dialogs for specific information requested:</p>\r\n\r\n<ul>\r\n	<li>Device information (such as country, device version, language, network type)</li>\r\n	<li>Personal identifiers (such as email address, name)</li>\r\n	<li>Usage data (such as interactions with the app)</li>\r\n</ul>\r\n\r\n<h2><strong>How We Use the Information</strong></h2>\r\n\r\n<p>We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</p>\r\n\r\n<ul>\r\n	<li>To provide and maintain our services</li>\r\n	<li>To improve the user experience within our app</li>\r\n	<li>To manage your account and provide customer support</li>\r\n	<li>To contact you via email for updates, promotional materials, and information you may find useful, provided you have given consent</li>\r\n	<li>To ensure compliance with our legal obligations and enforce our terms and policies</li>\r\n</ul>\r\n\r\n<h2><strong>Security of Your Information</strong></h2>\r\n\r\n<p>We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic, and managerial procedures to safeguard and secure the information we collect online.</p>\r\n\r\n<h2><strong>Cookies and Tracking Technologies</strong></h2>\r\n\r\n<p>A cookie is a small file which asks permission to be placed on your device&#39;s hard drive. Once you agree, the file is added, and the cookie helps analyze web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual by gathering and remembering information about your preferences.</p>\r\n\r\n<p>We use cookies and similar tracking technologies to monitor activity on our service and store certain information. Cookies are used to:</p>\r\n\r\n<ul>\r\n	<li>Understand and save your preferences for future visits</li>\r\n	<li>Compile aggregate data about site traffic and site interactions to offer better experiences and tools in the future</li>\r\n</ul>\r\n\r\n<p>Overall, cookies help us provide you with a better experience by enabling us to monitor which areas you find useful and which you do not. You can choose to accept or decline cookies. Most browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.</p>\r\n\r\n<h2><strong>Managing Your Personal Information</strong></h2>\r\n\r\n<p>You may choose to restrict the collection or use of your personal information by adjusting the permissions in your device settings or within the app itself.</p>\r\n\r\n<p>We will not sell, distribute, or lease your personal information to third parties without your explicit consent unless required by law. We may use your personal information to send you promotional information about our own content that we think you may find interesting, provided you have given your consent.</p>\r\n\r\n<p>If you believe that any information we are holding about you is incorrect or incomplete, please contact us as soon as possible. We will promptly correct any information found to be incorrect.</p>\r\n\r\n<h2><strong>Data Retention</strong></h2>\r\n\r\n<p>We will retain your personal information only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use your information to the extent necessary to comply with our legal obligations, resolve disputes, and enforce our policies.</p>\r\n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `grad_start_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `grad_end_color` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `grad_orientation` int(11) NOT NULL DEFAULT 0,
  `is_single_theme` int(11) NOT NULL DEFAULT 0,
  `isActive` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `themes`
--

INSERT INTO `themes` (`id`, `name`, `img`, `grad_start_color`, `grad_end_color`, `grad_orientation`, `is_single_theme`, `isActive`) VALUES
(1, 'Warm Flame', '', '#ff9a9e', '#fad0c4', 135, 0, 1),
(2, 'Night Fade', '', '#a18cd1', '#fbc2eb', 90, 0, 1),
(3, 'Winter Neva', '', '#a1c4fd', '#c2e9fb', 315, 0, 1),
(4, 'Dusty Grass', '', '#d4fc79', '#96e6a1', 315, 0, 1),
(5, 'Tempting Azure', '', '#84fab0', '#8fd3f4', 315, 0, 1),
(6, 'Ripe Malinka', '', '#f093fb', '#f5576c', 315, 0, 1),
(7, 'Plum Plate', '', '#667eea', '#764ba2', 315, 0, 1),
(8, 'Happy Fisher', '', '#89f7fe', '#66a6ff', 315, 0, 1),
(9, ' Itmeo Branding', '', '#2af598', '#009efd', 270, 0, 1),
(10, 'Mixed Hopes', '', '#c471f5', '#fa71cd', 90, 0, 1),
(11, 'Amour Amour', '', '#f77062', '#fe5196', 90, 0, 1),
(12, 'High Flight', '', '#0acffe', '#495aff', 0, 0, 1),
(13, 'Passionate Bed', 'theme_79076_bg.jpg', '#ff758c', '#ff7eb3', 0, 0, 1),
(14, 'Lady Lips', '', '#ff9a9e', '#fecfef', 90, 0, 1),
(15, 'Deep Blue', '', '#6a11cb', '#2575fc', 0, 0, 1),
(16, 'Star Wine', '', '#b465da', '#ee609c', 0, 0, 1),
(17, 'Happy Acid', '', '#37ecba', '#72afd3', 90, 0, 1),
(18, 'Strong Bliss', '', '#f78ca0', '#fe9a8b', 0, 0, 1),
(19, 'Fly High', '', '#48c6ef', '#6f86d6', 90, 0, 1),
(20, 'Grown Early', '', '#0ba360', '#3cba92', 90, 0, 1),
(21, 'Sharp Blues', '', '#00c6fb', '#005bea', 45, 0, 1),
(22, 'Great Whale', '', '#a3bded', '#6991c7', 45, 0, 1),
(23, 'Teen Notebook', '', '#9795f0', '#fbc8d4', 90, 0, 1),
(24, 'Aqua Splash', '', '#13547a', '#80d0c7', 90, 0, 1),
(25, 'Summer Games', '', '#92fe9d', '#00c9ff', 0, 0, 1),
(26, 'October Silence', '', '#b721ff', '#21d4fd', 135, 0, 1),
(27, 'Eternal Constance', '', '#09203f', '#537895', 90, 0, 1),
(28, 'Amour Amour', '', '#f77062', '#fe5196', 90, 0, 1),
(29, 'Happy Memories', '', '#ff5858', '#f09819', 135, 0, 1),
(30, 'Le Cocktail', '', '#874da2', '#c43a30', 45, 0, 1),
(31, 'River City', '', '#4481eb', '#04befe', 90, 0, 1),
(32, 'SeashoreGet', '', '#209cff', '#68e0cf', 90, 0, 1),
(33, 'Night Sky', '', '#1e3c72', '#2a5298', 90, 0, 1),
(34, 'Plum Bath', '', '#cc208e', '#6713d2', 90, 0, 1),
(35, 'Orange Juice', '', '#fc6076', '#ff9a44', 135, 0, 1),
(36, 'Smart Indigo', '', '#b224ef', '#7579ff', 90, 1, 1),
(37, 'Aqua Guidance', '', '#007adf', '#00ecbc', 90, 0, 1),
(38, 'Sleepless Night', '', '#5271c4', '#eca1fe', 315, 0, 1),
(39, 'Bitch\'s', '', '#cccccc', '#996699', 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_avatar` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `user_status` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `name`, `user_avatar`, `password`, `email`, `user_status`) VALUES
(1, 'jrmartinez0978', 'user_90008_img-thing.jpg', '$2y$12$QnjJ/VlL3qmKEBXgpVzLIOu.1d8ubZVBCFvLjfz58wgXLDEWgcgM2', 'jrmartinez0978@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `radio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `visitas`
--

INSERT INTO `visitas` (`id`, `radio_id`, `created_at`, `updated_at`) VALUES
(1, 26, '2024-10-10 15:56:42', '2024-10-10 15:56:42'),
(2, 26, '2024-10-10 15:56:59', '2024-10-10 15:56:59'),
(3, 26, '2024-10-10 19:16:21', '2024-10-10 19:16:21'),
(4, 30, '2024-10-10 19:16:26', '2024-10-10 19:16:26'),
(5, 24, '2024-10-10 19:16:48', '2024-10-10 19:16:48'),
(6, 24, '2024-10-10 19:16:55', '2024-10-10 19:16:55'),
(7, 24, '2024-10-10 19:18:42', '2024-10-10 19:18:42'),
(8, 41, '2024-10-10 20:31:55', '2024-10-10 20:31:55'),
(9, 36, '2024-10-10 20:32:10', '2024-10-10 20:32:10'),
(10, 24, '2024-10-11 02:42:33', '2024-10-11 02:42:33'),
(11, 26, '2024-10-11 02:42:39', '2024-10-11 02:42:39'),
(12, 26, '2024-10-11 02:42:48', '2024-10-11 02:42:48'),
(13, 50, '2024-10-11 03:33:47', '2024-10-11 03:33:47'),
(14, 50, '2024-10-11 03:35:00', '2024-10-11 03:35:00'),
(15, 50, '2024-10-11 03:36:09', '2024-10-11 03:36:09'),
(16, 50, '2024-10-11 03:37:56', '2024-10-11 03:37:56'),
(17, 38, '2024-10-11 03:38:19', '2024-10-11 03:38:19'),
(18, 29, '2024-10-11 03:38:25', '2024-10-11 03:38:25'),
(19, 24, '2024-10-11 03:38:36', '2024-10-11 03:38:36'),
(20, 35, '2024-10-11 03:39:37', '2024-10-11 03:39:37'),
(21, 40, '2024-10-11 03:39:41', '2024-10-11 03:39:41'),
(22, 41, '2024-10-11 03:39:45', '2024-10-11 03:39:45'),
(23, 37, '2024-10-11 03:39:49', '2024-10-11 03:39:49'),
(24, 29, '2024-10-11 03:43:09', '2024-10-11 03:43:09'),
(25, 26, '2024-10-11 11:03:54', '2024-10-11 11:03:54'),
(26, 24, '2024-10-11 11:03:58', '2024-10-11 11:03:58'),
(27, 24, '2024-10-11 11:19:51', '2024-10-11 11:19:51'),
(28, 24, '2024-10-11 11:20:03', '2024-10-11 11:20:03'),
(29, 24, '2024-10-11 11:59:26', '2024-10-11 11:59:26'),
(30, 26, '2024-10-11 12:37:30', '2024-10-11 12:37:30'),
(31, 24, '2024-10-11 12:37:47', '2024-10-11 12:37:47'),
(32, 46, '2024-10-12 21:31:23', '2024-10-12 21:31:23'),
(33, 24, '2024-10-14 11:01:13', '2024-10-14 11:01:13'),
(34, 24, '2024-10-14 11:01:27', '2024-10-14 11:01:27'),
(35, 24, '2024-10-14 11:01:57', '2024-10-14 11:01:57'),
(36, 24, '2024-10-14 11:53:52', '2024-10-14 11:53:52'),
(37, 24, '2024-10-14 12:25:14', '2024-10-14 12:25:14'),
(38, 49, '2024-10-14 13:30:35', '2024-10-14 13:30:35'),
(39, 49, '2024-10-14 14:49:14', '2024-10-14 14:49:14'),
(40, 26, '2024-10-15 16:09:58', '2024-10-15 16:09:58'),
(41, 24, '2024-10-16 16:54:27', '2024-10-16 16:54:27'),
(42, 26, '2024-10-16 16:54:30', '2024-10-16 16:54:30'),
(43, 24, '2024-10-17 13:49:34', '2024-10-17 13:49:34'),
(44, 50, '2024-10-18 21:38:00', '2024-10-18 21:38:00'),
(45, 49, '2024-10-18 21:38:32', '2024-10-18 21:38:32'),
(46, 43, '2024-10-18 21:40:54', '2024-10-18 21:40:54'),
(47, 51, '2024-10-19 20:09:40', '2024-10-19 20:09:40'),
(48, 24, '2024-10-23 11:18:11', '2024-10-23 11:18:11'),
(49, 24, '2024-10-23 11:26:08', '2024-10-23 11:26:08'),
(50, 41, '2024-10-23 20:23:48', '2024-10-23 20:23:48'),
(51, 29, '2024-10-23 20:24:25', '2024-10-23 20:24:25'),
(52, 49, '2024-10-25 19:51:01', '2024-10-25 19:51:01'),
(53, 34, '2024-11-02 16:01:36', '2024-11-02 16:01:36'),
(54, 52, '2024-11-02 18:21:03', '2024-11-02 18:21:03'),
(55, 29, '2024-11-03 02:35:12', '2024-11-03 02:35:12'),
(56, 46, '2024-11-08 14:33:38', '2024-11-08 14:33:38'),
(57, 48, '2024-11-13 03:23:59', '2024-11-13 03:23:59'),
(58, 41, '2024-11-14 15:41:26', '2024-11-14 15:41:26'),
(59, 41, '2024-11-15 01:25:38', '2024-11-15 01:25:38'),
(60, 49, '2024-11-23 00:10:56', '2024-11-23 00:10:56'),
(61, 24, '2024-11-24 19:47:19', '2024-11-24 19:47:19'),
(62, 34, '2024-11-26 22:55:17', '2024-11-26 22:55:17'),
(63, 34, '2024-11-29 22:21:58', '2024-11-29 22:21:58'),
(64, 34, '2024-11-29 22:44:23', '2024-11-29 22:44:23'),
(65, 34, '2024-11-29 22:45:49', '2024-11-29 22:45:49'),
(66, 34, '2024-12-01 14:30:19', '2024-12-01 14:30:19'),
(67, 24, '2024-12-02 15:39:11', '2024-12-02 15:39:11'),
(68, 24, '2024-12-02 15:39:53', '2024-12-02 15:39:53'),
(69, 53, '2024-12-02 16:17:47', '2024-12-02 16:17:47'),
(70, 54, '2024-12-02 16:28:00', '2024-12-02 16:28:00'),
(71, 55, '2024-12-02 16:41:29', '2024-12-02 16:41:29'),
(72, 34, '2024-12-02 19:16:49', '2024-12-02 19:16:49'),
(73, 34, '2024-12-02 23:10:23', '2024-12-02 23:10:23'),
(74, 37, '2024-12-08 21:20:32', '2024-12-08 21:20:32'),
(75, 39, '2024-12-08 21:20:58', '2024-12-08 21:20:58'),
(76, 55, '2024-12-08 21:21:30', '2024-12-08 21:21:30'),
(77, 38, '2024-12-08 21:29:10', '2024-12-08 21:29:10'),
(78, 42, '2024-12-13 22:23:54', '2024-12-13 22:23:54'),
(79, 37, '2024-12-14 15:36:14', '2024-12-14 15:36:14'),
(80, 41, '2024-12-18 19:54:44', '2024-12-18 19:54:44'),
(81, 30, '2024-12-18 22:05:59', '2024-12-18 22:05:59'),
(82, 56, '2024-12-19 18:13:03', '2024-12-19 18:13:03'),
(83, 57, '2024-12-19 18:26:13', '2024-12-19 18:26:13'),
(84, 26, '2024-12-22 15:03:26', '2024-12-22 15:03:26'),
(85, 57, '2024-12-23 02:44:36', '2024-12-23 02:44:36'),
(86, 37, '2024-12-25 02:18:47', '2024-12-25 02:18:47'),
(87, 24, '2024-12-25 02:24:06', '2024-12-25 02:24:06'),
(88, 30, '2024-12-26 00:40:00', '2024-12-26 00:40:00'),
(89, 30, '2024-12-26 00:40:27', '2024-12-26 00:40:27'),
(90, 24, '2024-12-26 00:40:37', '2024-12-26 00:40:37'),
(91, 30, '2024-12-26 00:40:50', '2024-12-26 00:40:50'),
(92, 30, '2024-12-26 00:42:24', '2024-12-26 00:42:24'),
(93, 35, '2024-12-26 00:42:39', '2024-12-26 00:42:39'),
(94, 26, '2024-12-27 21:19:09', '2024-12-27 21:19:09'),
(95, 30, '2024-12-27 21:19:23', '2024-12-27 21:19:23'),
(96, 50, '2024-12-29 18:02:51', '2024-12-29 18:02:51'),
(97, 50, '2024-12-29 18:37:44', '2024-12-29 18:37:44'),
(98, 42, '2024-12-30 07:56:37', '2024-12-30 07:56:37'),
(99, 24, '2025-01-02 21:39:00', '2025-01-02 21:39:00'),
(100, 31, '2025-01-04 19:07:47', '2025-01-04 19:07:47'),
(101, 51, '2025-01-05 04:04:35', '2025-01-05 04:04:35'),
(102, 26, '2025-01-05 17:36:16', '2025-01-05 17:36:16'),
(103, 30, '2025-01-05 17:39:06', '2025-01-05 17:39:06'),
(104, 31, '2025-01-05 17:39:11', '2025-01-05 17:39:11'),
(105, 37, '2025-01-14 13:46:31', '2025-01-14 13:46:31'),
(106, 24, '2025-01-14 18:34:52', '2025-01-14 18:34:52'),
(107, 47, '2025-01-15 12:59:06', '2025-01-15 12:59:06'),
(108, 32, '2025-01-20 11:41:33', '2025-01-20 11:41:33'),
(109, 46, '2025-01-20 14:59:36', '2025-01-20 14:59:36'),
(110, 24, '2025-01-20 14:59:55', '2025-01-20 14:59:55'),
(111, 39, '2025-01-20 15:00:06', '2025-01-20 15:00:06'),
(112, 37, '2025-01-20 15:01:49', '2025-01-20 15:01:49'),
(113, 30, '2025-01-20 15:01:58', '2025-01-20 15:01:58'),
(114, 54, '2025-01-20 15:03:04', '2025-01-20 15:03:04'),
(115, 52, '2025-01-20 15:03:12', '2025-01-20 15:03:12'),
(116, 30, '2025-01-20 15:10:29', '2025-01-20 15:10:29'),
(117, 49, '2025-01-21 09:04:18', '2025-01-21 09:04:18'),
(118, 54, '2025-01-22 13:14:36', '2025-01-22 13:14:36'),
(119, 30, '2025-01-22 14:10:35', '2025-01-22 14:10:35'),
(120, 42, '2025-01-22 14:59:13', '2025-01-22 14:59:13'),
(121, 50, '2025-01-22 14:59:26', '2025-01-22 14:59:26'),
(122, 30, '2025-01-22 15:05:15', '2025-01-22 15:05:15'),
(123, 24, '2025-01-22 15:05:47', '2025-01-22 15:05:47'),
(124, 30, '2025-01-23 01:54:42', '2025-01-23 01:54:42'),
(125, 31, '2025-01-23 01:54:46', '2025-01-23 01:54:46'),
(126, 31, '2025-01-23 01:54:48', '2025-01-23 01:54:48'),
(127, 29, '2025-01-23 01:54:51', '2025-01-23 01:54:51'),
(128, 29, '2025-01-23 01:54:53', '2025-01-23 01:54:53'),
(129, 26, '2025-01-23 01:54:57', '2025-01-23 01:54:57'),
(130, 24, '2025-01-23 01:55:02', '2025-01-23 01:55:02'),
(131, 42, '2025-01-23 01:58:45', '2025-01-23 01:58:45'),
(132, 54, '2025-01-23 13:05:23', '2025-01-23 13:05:23'),
(133, 56, '2025-01-23 22:49:00', '2025-01-23 22:49:00'),
(134, 41, '2025-01-23 23:27:24', '2025-01-23 23:27:24'),
(135, 44, '2025-01-28 13:59:50', '2025-01-28 13:59:50'),
(136, 50, '2025-02-02 19:49:35', '2025-02-02 19:49:35'),
(137, 54, '2025-02-02 21:30:16', '2025-02-02 21:30:16'),
(138, 29, '2025-02-03 22:22:10', '2025-02-03 22:22:10'),
(139, 44, '2025-02-06 12:13:11', '2025-02-06 12:13:11'),
(140, 44, '2025-02-06 17:02:00', '2025-02-06 17:02:00'),
(141, 24, '2025-02-08 13:23:51', '2025-02-08 13:23:51'),
(142, 26, '2025-02-08 13:24:04', '2025-02-08 13:24:04'),
(143, 30, '2025-02-08 13:24:40', '2025-02-08 13:24:40'),
(144, 36, '2025-02-08 14:23:47', '2025-02-08 14:23:47'),
(145, 31, '2025-02-08 14:24:07', '2025-02-08 14:24:07'),
(146, 31, '2025-02-08 14:30:56', '2025-02-08 14:30:56'),
(147, 26, '2025-02-08 19:03:03', '2025-02-08 19:03:03'),
(148, 29, '2025-02-08 19:03:13', '2025-02-08 19:03:13'),
(149, 24, '2025-02-08 19:51:28', '2025-02-08 19:51:28'),
(150, 24, '2025-02-08 19:51:43', '2025-02-08 19:51:43'),
(151, 43, '2025-02-08 19:52:50', '2025-02-08 19:52:50'),
(152, 45, '2025-02-08 19:53:08', '2025-02-08 19:53:08'),
(153, 55, '2025-02-08 19:53:15', '2025-02-08 19:53:15'),
(154, 56, '2025-02-08 19:53:22', '2025-02-08 19:53:22'),
(155, 34, '2025-02-13 03:28:59', '2025-02-13 03:28:59'),
(156, 50, '2025-02-16 16:46:37', '2025-02-16 16:46:37'),
(157, 24, '2025-02-17 11:56:43', '2025-02-17 11:56:43'),
(158, 29, '2025-02-17 11:56:58', '2025-02-17 11:56:58'),
(159, 37, '2025-02-17 11:57:09', '2025-02-17 11:57:09'),
(160, 24, '2025-02-17 12:24:07', '2025-02-17 12:24:07'),
(161, 26, '2025-02-17 12:33:57', '2025-02-17 12:33:57'),
(162, 26, '2025-02-17 12:34:16', '2025-02-17 12:34:16'),
(163, 24, '2025-02-17 12:34:23', '2025-02-17 12:34:23'),
(164, 54, '2025-02-17 12:46:32', '2025-02-17 12:46:32'),
(165, 29, '2025-02-17 12:50:57', '2025-02-17 12:50:57'),
(166, 26, '2025-02-17 13:00:22', '2025-02-17 13:00:22'),
(167, 24, '2025-02-17 13:01:05', '2025-02-17 13:01:05'),
(168, 48, '2025-02-19 02:47:08', '2025-02-19 02:47:08'),
(169, 29, '2025-02-20 01:17:58', '2025-02-20 01:17:58'),
(170, 41, '2025-02-20 12:37:07', '2025-02-20 12:37:07'),
(171, 26, '2025-02-21 07:37:34', '2025-02-21 07:37:34'),
(172, 30, '2025-02-21 07:37:47', '2025-02-21 07:37:47'),
(173, 47, '2025-02-21 07:38:36', '2025-02-21 07:38:36'),
(174, 51, '2025-02-24 21:04:18', '2025-02-24 21:04:18'),
(175, 56, '2025-02-24 22:47:56', '2025-02-24 22:47:56'),
(176, 56, '2025-02-24 22:49:17', '2025-02-24 22:49:17'),
(177, 56, '2025-02-25 11:21:48', '2025-02-25 11:21:48'),
(178, 56, '2025-02-25 22:05:18', '2025-02-25 22:05:18'),
(179, 44, '2025-02-25 23:48:24', '2025-02-25 23:48:24'),
(180, 41, '2025-02-26 15:16:36', '2025-02-26 15:16:36'),
(181, 44, '2025-02-27 00:21:14', '2025-02-27 00:21:14'),
(182, 53, '2025-02-28 04:11:35', '2025-02-28 04:11:35'),
(183, 44, '2025-03-01 23:17:42', '2025-03-01 23:17:42'),
(184, 30, '2025-03-01 23:18:09', '2025-03-01 23:18:09'),
(185, 44, '2025-03-02 09:10:55', '2025-03-02 09:10:55'),
(186, 44, '2025-03-02 09:58:47', '2025-03-02 09:58:47'),
(187, 29, '2025-03-02 09:59:04', '2025-03-02 09:59:04'),
(188, 46, '2025-03-02 13:11:39', '2025-03-02 13:11:39'),
(189, 29, '2025-03-03 19:14:40', '2025-03-03 19:14:40'),
(190, 44, '2025-03-04 16:19:18', '2025-03-04 16:19:18'),
(191, 35, '2025-03-06 19:03:07', '2025-03-06 19:03:07'),
(192, 36, '2025-03-06 19:19:53', '2025-03-06 19:19:53'),
(193, 30, '2025-03-07 05:32:22', '2025-03-07 05:32:22'),
(194, 31, '2025-03-07 05:34:08', '2025-03-07 05:34:08'),
(195, 44, '2025-03-07 16:34:11', '2025-03-07 16:34:11'),
(196, 56, '2025-03-07 22:11:31', '2025-03-07 22:11:31'),
(197, 26, '2025-03-08 15:24:19', '2025-03-08 15:24:19'),
(198, 26, '2025-03-08 15:24:35', '2025-03-08 15:24:35'),
(199, 26, '2025-03-08 15:24:37', '2025-03-08 15:24:37'),
(200, 26, '2025-03-08 15:24:48', '2025-03-08 15:24:48'),
(201, 44, '2025-03-09 21:51:15', '2025-03-09 21:51:15'),
(202, 42, '2025-03-12 10:45:06', '2025-03-12 10:45:06'),
(203, 51, '2025-03-13 14:21:40', '2025-03-13 14:21:40'),
(204, 47, '2025-03-13 21:34:20', '2025-03-13 21:34:20'),
(205, 42, '2025-03-16 16:01:12', '2025-03-16 16:01:12'),
(206, 42, '2025-03-16 18:18:10', '2025-03-16 18:18:10'),
(207, 44, '2025-03-18 17:52:26', '2025-03-18 17:52:26'),
(208, 47, '2025-03-21 12:15:28', '2025-03-21 12:15:28'),
(209, 37, '2025-03-23 14:03:50', '2025-03-23 14:03:50'),
(210, 31, '2025-03-23 14:04:43', '2025-03-23 14:04:43'),
(211, 24, '2025-03-25 12:34:43', '2025-03-25 12:34:43'),
(212, 54, '2025-03-27 14:07:47', '2025-03-27 14:07:47'),
(213, 52, '2025-03-28 03:40:13', '2025-03-28 03:40:13'),
(214, 24, '2025-03-28 03:40:37', '2025-03-28 03:40:37'),
(215, 29, '2025-03-28 03:40:53', '2025-03-28 03:40:53'),
(216, 42, '2025-03-28 17:07:42', '2025-03-28 17:07:42'),
(217, 34, '2025-03-28 22:44:07', '2025-03-28 22:44:07'),
(218, 52, '2025-03-28 23:16:25', '2025-03-28 23:16:25'),
(219, 40, '2025-03-29 10:53:57', '2025-03-29 10:53:57'),
(220, 48, '2025-03-29 20:58:02', '2025-03-29 20:58:02'),
(221, 42, '2025-03-30 21:44:50', '2025-03-30 21:44:50'),
(222, 24, '2025-03-31 12:21:00', '2025-03-31 12:21:00'),
(223, 42, '2025-03-31 19:32:28', '2025-03-31 19:32:28'),
(224, 35, '2025-03-31 22:45:58', '2025-03-31 22:45:58'),
(225, 24, '2025-04-01 10:56:28', '2025-04-01 10:56:28'),
(226, 26, '2025-04-01 10:56:34', '2025-04-01 10:56:34'),
(227, 31, '2025-04-01 10:56:40', '2025-04-01 10:56:40'),
(228, 29, '2025-04-01 11:34:53', '2025-04-01 11:34:53'),
(229, 42, '2025-04-01 12:56:13', '2025-04-01 12:56:13'),
(230, 42, '2025-04-01 18:09:23', '2025-04-01 18:09:23'),
(231, 42, '2025-04-01 20:46:49', '2025-04-01 20:46:49'),
(232, 29, '2025-04-02 13:10:43', '2025-04-02 13:10:43'),
(233, 44, '2025-04-02 13:31:10', '2025-04-02 13:31:10'),
(234, 29, '2025-04-03 11:59:08', '2025-04-03 11:59:08'),
(235, 40, '2025-04-03 14:04:43', '2025-04-03 14:04:43'),
(236, 42, '2025-04-03 19:43:04', '2025-04-03 19:43:04'),
(237, 42, '2025-04-03 23:31:40', '2025-04-03 23:31:40'),
(238, 54, '2025-04-04 15:05:22', '2025-04-04 15:05:22'),
(239, 54, '2025-04-04 15:07:56', '2025-04-04 15:07:56'),
(240, 42, '2025-04-04 21:30:47', '2025-04-04 21:30:47'),
(241, 42, '2025-04-04 22:00:25', '2025-04-04 22:00:25'),
(242, 42, '2025-04-06 15:03:58', '2025-04-06 15:03:58'),
(243, 44, '2025-04-07 15:37:35', '2025-04-07 15:37:35'),
(244, 44, '2025-04-07 16:00:31', '2025-04-07 16:00:31'),
(245, 47, '2025-04-07 21:45:09', '2025-04-07 21:45:09'),
(246, 24, '2025-04-08 11:49:51', '2025-04-08 11:49:51'),
(247, 44, '2025-04-08 17:49:02', '2025-04-08 17:49:02'),
(248, 44, '2025-04-08 18:18:27', '2025-04-08 18:18:27'),
(249, 37, '2025-04-09 20:46:53', '2025-04-09 20:46:53'),
(250, 41, '2025-04-09 21:55:40', '2025-04-09 21:55:40'),
(251, 24, '2025-04-10 11:25:37', '2025-04-10 11:25:37'),
(252, 46, '2025-04-10 16:12:49', '2025-04-10 16:12:49'),
(253, 44, '2025-04-10 16:31:02', '2025-04-10 16:31:02'),
(254, 29, '2025-04-10 19:17:22', '2025-04-10 19:17:22'),
(255, 29, '2025-04-10 19:19:06', '2025-04-10 19:19:06'),
(256, 30, '2025-04-10 19:19:09', '2025-04-10 19:19:09'),
(257, 44, '2025-04-11 10:43:24', '2025-04-11 10:43:24'),
(258, 46, '2025-04-11 11:52:46', '2025-04-11 11:52:46'),
(259, 56, '2025-04-12 22:07:20', '2025-04-12 22:07:20'),
(260, 40, '2025-04-15 12:33:02', '2025-04-15 12:33:02'),
(261, 40, '2025-04-18 20:41:39', '2025-04-18 20:41:39'),
(262, 24, '2025-04-21 04:06:40', '2025-04-21 04:06:40'),
(263, 24, '2025-04-21 04:09:09', '2025-04-21 04:09:09'),
(264, 24, '2025-04-21 05:15:38', '2025-04-21 05:15:38'),
(265, 24, '2025-04-21 09:40:55', '2025-04-21 09:40:55'),
(266, 26, '2025-04-21 09:57:34', '2025-04-21 09:57:34'),
(267, 38, '2025-04-21 12:31:33', '2025-04-21 12:31:33'),
(268, 24, '2025-04-21 13:22:31', '2025-04-21 13:22:31'),
(269, 30, '2025-04-21 14:26:11', '2025-04-21 14:26:11'),
(270, 30, '2025-04-21 14:27:50', '2025-04-21 14:27:50'),
(271, 52, '2025-04-21 14:28:02', '2025-04-21 14:28:02'),
(272, 56, '2025-04-21 14:28:09', '2025-04-21 14:28:09'),
(273, 24, '2025-04-21 16:04:15', '2025-04-21 16:04:15'),
(274, 24, '2025-04-21 18:54:21', '2025-04-21 18:54:21'),
(275, 24, '2025-04-22 13:22:17', '2025-04-22 13:22:17'),
(276, 38, '2025-04-22 13:51:26', '2025-04-22 13:51:26'),
(277, 29, '2025-04-22 13:51:33', '2025-04-22 13:51:33'),
(278, 30, '2025-04-22 13:51:39', '2025-04-22 13:51:39'),
(279, 24, '2025-04-22 13:51:48', '2025-04-22 13:51:48'),
(280, 56, '2025-04-22 22:07:17', '2025-04-22 22:07:17'),
(281, 24, '2025-04-23 16:38:37', '2025-04-23 16:38:37'),
(282, 50, '2025-04-23 18:00:08', '2025-04-23 18:00:08'),
(283, 24, '2025-04-24 13:09:01', '2025-04-24 13:09:01'),
(284, 29, '2025-04-24 14:37:27', '2025-04-24 14:37:27'),
(285, 30, '2025-04-24 14:37:37', '2025-04-24 14:37:37'),
(286, 37, '2025-04-24 14:37:47', '2025-04-24 14:37:47'),
(287, 39, '2025-04-24 14:37:56', '2025-04-24 14:37:56'),
(288, 24, '2025-04-24 14:38:06', '2025-04-24 14:38:06'),
(289, 39, '2025-04-24 14:38:11', '2025-04-24 14:38:11'),
(290, 26, '2025-04-24 14:38:31', '2025-04-24 14:38:31'),
(291, 31, '2025-04-24 14:38:43', '2025-04-24 14:38:43'),
(292, 33, '2025-04-24 14:38:49', '2025-04-24 14:38:49'),
(293, 35, '2025-04-24 14:38:55', '2025-04-24 14:38:55'),
(294, 50, '2025-04-24 14:39:15', '2025-04-24 14:39:15'),
(295, 50, '2025-04-24 15:22:18', '2025-04-24 15:22:18'),
(296, 24, '2025-04-24 15:24:52', '2025-04-24 15:24:52'),
(297, 30, '2025-04-24 16:26:11', '2025-04-24 16:26:11'),
(298, 38, '2025-04-24 16:26:19', '2025-04-24 16:26:19'),
(299, 24, '2025-04-24 16:53:42', '2025-04-24 16:53:42'),
(300, 50, '2025-04-24 20:42:13', '2025-04-24 20:42:13'),
(301, 56, '2025-04-24 22:11:15', '2025-04-24 22:11:15'),
(302, 24, '2025-04-25 17:03:15', '2025-04-25 17:03:15'),
(303, 48, '2025-04-25 21:03:29', '2025-04-25 21:03:29'),
(304, 42, '2025-04-26 10:18:22', '2025-04-26 10:18:22'),
(305, 44, '2025-04-26 15:16:13', '2025-04-26 15:16:13'),
(306, 42, '2025-04-26 19:40:09', '2025-04-26 19:40:09'),
(307, 42, '2025-04-26 19:59:43', '2025-04-26 19:59:43'),
(308, 44, '2025-04-27 23:37:45', '2025-04-27 23:37:45'),
(309, 24, '2025-04-28 12:46:21', '2025-04-28 12:46:21'),
(310, 24, '2025-04-28 13:39:46', '2025-04-28 13:39:46'),
(311, 44, '2025-04-28 16:21:43', '2025-04-28 16:21:43'),
(312, 24, '2025-04-28 19:42:33', '2025-04-28 19:42:33'),
(313, 24, '2025-04-29 10:53:02', '2025-04-29 10:53:02'),
(314, 24, '2025-04-29 11:34:15', '2025-04-29 11:34:15'),
(315, 24, '2025-04-29 12:15:16', '2025-04-29 12:15:16'),
(316, 26, '2025-04-29 12:20:13', '2025-04-29 12:20:13'),
(317, 24, '2025-04-29 12:20:23', '2025-04-29 12:20:23'),
(318, 24, '2025-04-29 12:20:40', '2025-04-29 12:20:40'),
(319, 40, '2025-04-29 13:03:07', '2025-04-29 13:03:07'),
(320, 37, '2025-04-29 13:04:10', '2025-04-29 13:04:10'),
(321, 44, '2025-04-29 13:10:59', '2025-04-29 13:10:59'),
(322, 24, '2025-04-29 13:15:27', '2025-04-29 13:15:27'),
(323, 39, '2025-04-29 13:17:04', '2025-04-29 13:17:04'),
(324, 38, '2025-04-29 13:21:29', '2025-04-29 13:21:29'),
(325, 37, '2025-04-29 13:22:58', '2025-04-29 13:22:58'),
(326, 39, '2025-04-29 15:47:20', '2025-04-29 15:47:20'),
(327, 38, '2025-04-29 15:47:29', '2025-04-29 15:47:29'),
(328, 37, '2025-04-29 15:47:38', '2025-04-29 15:47:38'),
(329, 24, '2025-04-29 15:47:52', '2025-04-29 15:47:52'),
(330, 36, '2025-04-29 19:08:19', '2025-04-29 19:08:19'),
(331, 24, '2025-04-29 21:28:56', '2025-04-29 21:28:56'),
(332, 37, '2025-04-30 00:35:59', '2025-04-30 00:35:59'),
(333, 44, '2025-04-30 10:37:29', '2025-04-30 10:37:29'),
(334, 24, '2025-04-30 12:51:40', '2025-04-30 12:51:40'),
(335, 24, '2025-04-30 13:39:43', '2025-04-30 13:39:43'),
(336, 24, '2025-04-30 13:49:52', '2025-04-30 13:49:52'),
(337, 30, '2025-04-30 13:50:06', '2025-04-30 13:50:06'),
(338, 24, '2025-04-30 13:50:16', '2025-04-30 13:50:16'),
(339, 26, '2025-04-30 13:50:47', '2025-04-30 13:50:47'),
(340, 30, '2025-04-30 13:52:20', '2025-04-30 13:52:20'),
(341, 31, '2025-04-30 13:56:36', '2025-04-30 13:56:36'),
(342, 24, '2025-04-30 13:58:22', '2025-04-30 13:58:22'),
(343, 24, '2025-04-30 13:58:29', '2025-04-30 13:58:29'),
(344, 24, '2025-04-30 13:58:43', '2025-04-30 13:58:43'),
(345, 30, '2025-04-30 14:06:29', '2025-04-30 14:06:29'),
(346, 30, '2025-04-30 14:06:33', '2025-04-30 14:06:33'),
(347, 47, '2025-04-30 14:15:40', '2025-04-30 14:15:40'),
(348, 24, '2025-04-30 15:34:50', '2025-04-30 15:34:50'),
(349, 24, '2025-04-30 16:56:40', '2025-04-30 16:56:40'),
(350, 24, '2025-04-30 17:20:28', '2025-04-30 17:20:28'),
(351, 26, '2025-04-30 23:22:58', '2025-04-30 23:22:58'),
(352, 26, '2025-04-30 23:23:26', '2025-04-30 23:23:26'),
(353, 24, '2025-05-01 13:12:37', '2025-05-01 13:12:37'),
(354, 24, '2025-05-01 16:41:58', '2025-05-01 16:41:58'),
(355, 42, '2025-05-01 21:39:34', '2025-05-01 21:39:34'),
(356, 56, '2025-05-01 22:31:31', '2025-05-01 22:31:31'),
(357, 24, '2025-05-02 11:03:43', '2025-05-02 11:03:43'),
(358, 24, '2025-05-02 11:20:39', '2025-05-02 11:20:39'),
(359, 24, '2025-05-02 11:20:45', '2025-05-02 11:20:45'),
(360, 24, '2025-05-02 11:26:11', '2025-05-02 11:26:11'),
(361, 24, '2025-05-02 11:40:40', '2025-05-02 11:40:40'),
(362, 24, '2025-05-02 11:59:57', '2025-05-02 11:59:57'),
(363, 24, '2025-05-02 12:05:27', '2025-05-02 12:05:27'),
(364, 24, '2025-05-02 13:09:25', '2025-05-02 13:09:25'),
(365, 42, '2025-05-02 15:35:18', '2025-05-02 15:35:18'),
(366, 50, '2025-05-02 15:48:10', '2025-05-02 15:48:10'),
(367, 29, '2025-05-02 15:48:26', '2025-05-02 15:48:26'),
(368, 37, '2025-05-02 15:48:34', '2025-05-02 15:48:34'),
(369, 38, '2025-05-02 15:48:40', '2025-05-02 15:48:40'),
(370, 31, '2025-05-02 15:50:35', '2025-05-02 15:50:35'),
(371, 48, '2025-05-02 15:52:30', '2025-05-02 15:52:30'),
(372, 52, '2025-05-02 15:52:34', '2025-05-02 15:52:34'),
(373, 52, '2025-05-02 15:52:53', '2025-05-02 15:52:53'),
(374, 31, '2025-05-02 15:53:14', '2025-05-02 15:53:14'),
(375, 26, '2025-05-02 15:53:18', '2025-05-02 15:53:18'),
(376, 31, '2025-05-02 15:53:24', '2025-05-02 15:53:24'),
(377, 44, '2025-05-02 16:29:48', '2025-05-02 16:29:48'),
(378, 31, '2025-05-02 17:43:22', '2025-05-02 17:43:22'),
(379, 31, '2025-05-02 17:55:51', '2025-05-02 17:55:51'),
(380, 26, '2025-05-02 19:07:08', '2025-05-02 19:07:08'),
(381, 31, '2025-05-02 19:20:46', '2025-05-02 19:20:46'),
(382, 24, '2025-05-02 19:20:56', '2025-05-02 19:20:56'),
(383, 52, '2025-05-02 19:21:27', '2025-05-02 19:21:27'),
(384, 45, '2025-05-02 19:22:06', '2025-05-02 19:22:06'),
(385, 56, '2025-05-02 22:01:41', '2025-05-02 22:01:41'),
(386, 24, '2025-05-03 15:33:56', '2025-05-03 15:33:56'),
(387, 42, '2025-05-03 16:03:32', '2025-05-03 16:03:32'),
(388, 37, '2025-05-03 16:03:58', '2025-05-03 16:03:58'),
(389, 38, '2025-05-03 16:04:15', '2025-05-03 16:04:15'),
(390, 24, '2025-05-05 11:16:47', '2025-05-05 11:16:47'),
(391, 24, '2025-05-05 11:16:51', '2025-05-05 11:16:51'),
(392, 24, '2025-05-05 13:13:31', '2025-05-05 13:13:31'),
(393, 42, '2025-05-05 17:30:31', '2025-05-05 17:30:31'),
(394, 29, '2025-05-05 17:31:56', '2025-05-05 17:31:56'),
(395, 30, '2025-05-05 17:32:43', '2025-05-05 17:32:43'),
(396, 40, '2025-05-05 21:14:09', '2025-05-05 21:14:09'),
(397, 24, '2025-05-06 11:02:52', '2025-05-06 11:02:52'),
(398, 24, '2025-05-06 11:06:52', '2025-05-06 11:06:52'),
(399, 24, '2025-05-06 11:19:51', '2025-05-06 11:19:51'),
(400, 24, '2025-05-06 11:20:02', '2025-05-06 11:20:02'),
(401, 24, '2025-05-06 11:26:21', '2025-05-06 11:26:21'),
(402, 24, '2025-05-06 11:26:37', '2025-05-06 11:26:37'),
(403, 24, '2025-05-06 11:31:14', '2025-05-06 11:31:14'),
(404, 24, '2025-05-06 11:40:30', '2025-05-06 11:40:30'),
(405, 24, '2025-05-06 11:40:39', '2025-05-06 11:40:39'),
(406, 24, '2025-05-06 11:45:32', '2025-05-06 11:45:32'),
(407, 24, '2025-05-06 12:24:51', '2025-05-06 12:24:51'),
(408, 24, '2025-05-06 12:49:02', '2025-05-06 12:49:02'),
(409, 24, '2025-05-06 13:15:48', '2025-05-06 13:15:48'),
(410, 24, '2025-05-06 13:15:58', '2025-05-06 13:15:58'),
(411, 30, '2025-05-06 13:16:28', '2025-05-06 13:16:28'),
(412, 37, '2025-05-06 13:16:41', '2025-05-06 13:16:41'),
(413, 24, '2025-05-06 15:25:25', '2025-05-06 15:25:25'),
(414, 26, '2025-05-06 15:25:30', '2025-05-06 15:25:30'),
(415, 26, '2025-05-06 15:26:36', '2025-05-06 15:26:36'),
(416, 31, '2025-05-06 15:27:05', '2025-05-06 15:27:05'),
(417, 30, '2025-05-06 15:27:07', '2025-05-06 15:27:07'),
(418, 26, '2025-05-06 15:27:21', '2025-05-06 15:27:21'),
(419, 26, '2025-05-06 15:27:56', '2025-05-06 15:27:56'),
(420, 26, '2025-05-06 15:32:03', '2025-05-06 15:32:03'),
(421, 24, '2025-05-06 17:03:56', '2025-05-06 17:03:56'),
(422, 24, '2025-05-06 17:13:37', '2025-05-06 17:13:37'),
(423, 24, '2025-05-06 19:03:39', '2025-05-06 19:03:39'),
(424, 42, '2025-05-06 19:09:38', '2025-05-06 19:09:38'),
(425, 24, '2025-05-07 11:05:53', '2025-05-07 11:05:53'),
(426, 52, '2025-05-07 11:33:40', '2025-05-07 11:33:40'),
(427, 24, '2025-05-07 12:12:31', '2025-05-07 12:12:31'),
(428, 24, '2025-05-07 12:13:30', '2025-05-07 12:13:30'),
(429, 50, '2025-05-07 12:18:30', '2025-05-07 12:18:30'),
(430, 26, '2025-05-07 12:58:06', '2025-05-07 12:58:06'),
(431, 26, '2025-05-07 12:59:20', '2025-05-07 12:59:20'),
(432, 24, '2025-05-07 13:12:42', '2025-05-07 13:12:42'),
(433, 30, '2025-05-08 02:57:10', '2025-05-08 02:57:10'),
(434, 24, '2025-05-08 12:56:15', '2025-05-08 12:56:15'),
(435, 31, '2025-05-08 14:52:11', '2025-05-08 14:52:11'),
(436, 26, '2025-05-08 15:25:44', '2025-05-08 15:25:44'),
(437, 42, '2025-05-08 16:05:29', '2025-05-08 16:05:29'),
(438, 30, '2025-05-08 17:20:58', '2025-05-08 17:20:58'),
(439, 30, '2025-05-08 17:22:51', '2025-05-08 17:22:51'),
(440, 47, '2025-05-08 20:40:44', '2025-05-08 20:40:44'),
(441, 56, '2025-05-08 22:07:44', '2025-05-08 22:07:44'),
(442, 24, '2025-05-09 10:57:50', '2025-05-09 10:57:50'),
(443, 24, '2025-05-09 11:48:31', '2025-05-09 11:48:31'),
(444, 24, '2025-05-09 12:22:08', '2025-05-09 12:22:08'),
(445, 40, '2025-05-09 17:16:36', '2025-05-09 17:16:36'),
(446, 40, '2025-05-10 11:44:50', '2025-05-10 11:44:50'),
(447, 42, '2025-05-10 13:07:56', '2025-05-10 13:07:56'),
(448, 42, '2025-05-10 13:09:34', '2025-05-10 13:09:34'),
(449, 56, '2025-05-10 17:55:49', '2025-05-10 17:55:49'),
(450, 42, '2025-05-11 09:57:52', '2025-05-11 09:57:52'),
(451, 51, '2025-05-11 13:04:51', '2025-05-11 13:04:51'),
(452, 42, '2025-05-11 16:52:40', '2025-05-11 16:52:40'),
(453, 24, '2025-05-12 13:01:22', '2025-05-12 13:01:22'),
(454, 26, '2025-05-12 14:11:29', '2025-05-12 14:11:29'),
(455, 40, '2025-05-13 00:11:08', '2025-05-13 00:11:08'),
(456, 40, '2025-05-13 07:27:45', '2025-05-13 07:27:45'),
(457, 24, '2025-05-13 11:47:53', '2025-05-13 11:47:53'),
(458, 24, '2025-05-13 11:56:50', '2025-05-13 11:56:50'),
(459, 26, '2025-05-13 12:14:13', '2025-05-13 12:14:13'),
(460, 32, '2025-05-13 12:20:55', '2025-05-13 12:20:55'),
(461, 26, '2025-05-13 12:21:05', '2025-05-13 12:21:05'),
(462, 26, '2025-05-13 12:23:10', '2025-05-13 12:23:10'),
(463, 24, '2025-05-13 13:28:53', '2025-05-13 13:28:53'),
(464, 24, '2025-05-13 14:03:50', '2025-05-13 14:03:50'),
(465, 24, '2025-05-13 14:18:26', '2025-05-13 14:18:26'),
(466, 24, '2025-05-13 19:05:22', '2025-05-13 19:05:22'),
(467, 56, '2025-05-13 22:19:51', '2025-05-13 22:19:51'),
(468, 37, '2025-05-14 11:55:26', '2025-05-14 11:55:26'),
(469, 24, '2025-05-14 11:57:19', '2025-05-14 11:57:19'),
(470, 47, '2025-05-14 13:56:40', '2025-05-14 13:56:40'),
(471, 47, '2025-05-14 13:57:03', '2025-05-14 13:57:03'),
(472, 47, '2025-05-14 13:59:28', '2025-05-14 13:59:28'),
(473, 44, '2025-05-14 20:14:33', '2025-05-14 20:14:33'),
(474, 47, '2025-05-14 22:43:27', '2025-05-14 22:43:27'),
(475, 24, '2025-05-15 12:31:45', '2025-05-15 12:31:45'),
(476, 42, '2025-05-15 13:21:44', '2025-05-15 13:21:44'),
(477, 37, '2025-05-15 13:22:23', '2025-05-15 13:22:23'),
(478, 39, '2025-05-15 13:22:40', '2025-05-15 13:22:40'),
(479, 30, '2025-05-15 13:22:58', '2025-05-15 13:22:58'),
(480, 29, '2025-05-15 13:23:16', '2025-05-15 13:23:16'),
(481, 24, '2025-05-15 13:25:47', '2025-05-15 13:25:47'),
(482, 37, '2025-05-15 13:25:58', '2025-05-15 13:25:58'),
(483, 47, '2025-05-15 14:00:18', '2025-05-15 14:00:18'),
(484, 41, '2025-05-15 14:12:35', '2025-05-15 14:12:35'),
(485, 24, '2025-05-15 14:48:15', '2025-05-15 14:48:15'),
(486, 47, '2025-05-15 16:43:32', '2025-05-15 16:43:32'),
(487, 24, '2025-05-15 17:56:27', '2025-05-15 17:56:27'),
(488, 26, '2025-05-15 17:59:27', '2025-05-15 17:59:27'),
(489, 26, '2025-05-15 17:59:28', '2025-05-15 17:59:28'),
(490, 30, '2025-05-15 17:59:40', '2025-05-15 17:59:40'),
(491, 31, '2025-05-15 17:59:46', '2025-05-15 17:59:46'),
(492, 30, '2025-05-15 18:00:43', '2025-05-15 18:00:43'),
(493, 45, '2025-05-15 18:01:19', '2025-05-15 18:01:19'),
(494, 48, '2025-05-15 18:01:27', '2025-05-15 18:01:27'),
(495, 50, '2025-05-15 18:01:33', '2025-05-15 18:01:33'),
(496, 50, '2025-05-15 18:01:41', '2025-05-15 18:01:41'),
(497, 52, '2025-05-15 18:02:11', '2025-05-15 18:02:11'),
(498, 54, '2025-05-15 18:02:14', '2025-05-15 18:02:14'),
(499, 56, '2025-05-15 18:02:18', '2025-05-15 18:02:18'),
(500, 26, '2025-05-15 18:02:33', '2025-05-15 18:02:33'),
(501, 30, '2025-05-15 18:02:38', '2025-05-15 18:02:38'),
(502, 24, '2025-05-15 18:05:55', '2025-05-15 18:05:55'),
(503, 24, '2025-05-15 18:06:18', '2025-05-15 18:06:18'),
(504, 47, '2025-05-15 18:48:50', '2025-05-15 18:48:50'),
(505, 47, '2025-05-15 19:07:28', '2025-05-15 19:07:28'),
(506, 47, '2025-05-15 21:04:34', '2025-05-15 21:04:34'),
(507, 47, '2025-05-15 21:52:12', '2025-05-15 21:52:12'),
(508, 29, '2025-05-16 03:20:30', '2025-05-16 03:20:30'),
(509, 30, '2025-05-16 06:11:42', '2025-05-16 06:11:42'),
(510, 24, '2025-05-16 06:11:53', '2025-05-16 06:11:53'),
(511, 24, '2025-05-16 11:02:47', '2025-05-16 11:02:47'),
(512, 24, '2025-05-16 11:35:11', '2025-05-16 11:35:11'),
(513, 47, '2025-05-16 18:54:24', '2025-05-16 18:54:24'),
(514, 42, '2025-05-17 12:20:01', '2025-05-17 12:20:01'),
(515, 26, '2025-05-18 13:04:35', '2025-05-18 13:04:35'),
(516, 50, '2025-05-18 16:01:45', '2025-05-18 16:01:45'),
(517, 50, '2025-05-18 16:28:31', '2025-05-18 16:28:31'),
(518, 50, '2025-05-18 17:17:58', '2025-05-18 17:17:58'),
(519, 42, '2025-05-18 17:45:52', '2025-05-18 17:45:52'),
(520, 50, '2025-05-18 20:27:05', '2025-05-18 20:27:05'),
(521, 50, '2025-05-18 20:39:26', '2025-05-18 20:39:26'),
(522, 50, '2025-05-18 20:39:33', '2025-05-18 20:39:33'),
(523, 50, '2025-05-18 22:09:15', '2025-05-18 22:09:15'),
(524, 50, '2025-05-18 22:23:32', '2025-05-18 22:23:32'),
(525, 50, '2025-05-18 22:24:44', '2025-05-18 22:24:44'),
(526, 50, '2025-05-18 23:00:39', '2025-05-18 23:00:39'),
(527, 47, '2025-05-19 10:57:46', '2025-05-19 10:57:46'),
(528, 24, '2025-05-19 11:03:29', '2025-05-19 11:03:29'),
(529, 47, '2025-05-19 11:14:25', '2025-05-19 11:14:25'),
(530, 24, '2025-05-19 13:17:14', '2025-05-19 13:17:14'),
(531, 41, '2025-05-19 15:48:19', '2025-05-19 15:48:19'),
(532, 24, '2025-05-20 10:56:03', '2025-05-20 10:56:03'),
(533, 24, '2025-05-20 11:22:32', '2025-05-20 11:22:32'),
(534, 24, '2025-05-20 13:11:49', '2025-05-20 13:11:49'),
(535, 24, '2025-05-20 13:36:36', '2025-05-20 13:36:36'),
(536, 24, '2025-05-20 13:36:42', '2025-05-20 13:36:42'),
(537, 24, '2025-05-20 13:50:58', '2025-05-20 13:50:58'),
(538, 44, '2025-05-20 16:42:22', '2025-05-20 16:42:22'),
(539, 56, '2025-05-20 22:38:33', '2025-05-20 22:38:33'),
(540, 24, '2025-05-21 12:57:51', '2025-05-21 12:57:51'),
(541, 52, '2025-05-21 21:44:54', '2025-05-21 21:44:54'),
(542, 24, '2025-05-22 15:06:00', '2025-05-22 15:06:00'),
(543, 26, '2025-05-22 15:53:44', '2025-05-22 15:53:44'),
(544, 26, '2025-05-22 15:59:17', '2025-05-22 15:59:17'),
(545, 56, '2025-05-22 22:42:54', '2025-05-22 22:42:54'),
(546, 24, '2025-05-23 11:07:15', '2025-05-23 11:07:15'),
(547, 24, '2025-05-23 12:15:12', '2025-05-23 12:15:12'),
(548, 51, '2025-05-23 17:24:01', '2025-05-23 17:24:01'),
(549, 56, '2025-05-23 22:25:40', '2025-05-23 22:25:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `radios`
--
ALTER TABLE `radios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `radios_cat`
--
ALTER TABLE `radios_cat`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`name`) USING BTREE;

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `radios`
--
ALTER TABLE `radios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `radios_cat`
--
ALTER TABLE `radios_cat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=550;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
