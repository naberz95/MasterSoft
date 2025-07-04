-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for proyectoactas
CREATE DATABASE IF NOT EXISTS `proyectoactas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `proyectoactas`;

-- Dumping structure for table proyectoactas.actas
CREATE TABLE IF NOT EXISTS `actas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_id` bigint unsigned NOT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `lugar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad_id` bigint unsigned NOT NULL,
  `empresa_id` bigint unsigned NOT NULL,
  `proyecto_id` bigint unsigned NOT NULL,
  `version_id` bigint unsigned DEFAULT NULL,
  `objetivo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `agenda` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `desarrollo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `conclusiones` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `proxima_reunion` date DEFAULT NULL,
  `firmante_empresa_id` bigint unsigned NOT NULL,
  `firmante_gp_id` bigint unsigned NOT NULL,
  `facturable` tinyint(1) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `actas_numero_unique` (`numero`),
  KEY `actas_tipo_id_foreign` (`tipo_id`),
  KEY `actas_ciudad_id_foreign` (`ciudad_id`),
  KEY `actas_empresa_id_foreign` (`empresa_id`),
  KEY `actas_proyecto_id_foreign` (`proyecto_id`),
  KEY `actas_firmante_empresa_id_foreign` (`firmante_empresa_id`),
  KEY `actas_firmante_gp_id_foreign` (`firmante_gp_id`),
  KEY `actas_version_id_index` (`version_id`),
  CONSTRAINT `actas_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_firmante_empresa_id_foreign` FOREIGN KEY (`firmante_empresa_id`) REFERENCES `personas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_firmante_gp_id_foreign` FOREIGN KEY (`firmante_gp_id`) REFERENCES `personas` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_proyecto_id_foreign` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_tipo_id_foreign` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_acta` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `actas_version_id_foreign` FOREIGN KEY (`version_id`) REFERENCES `versions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.acta_persona
CREATE TABLE IF NOT EXISTS `acta_persona` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `acta_id` bigint unsigned NOT NULL,
  `persona_id` bigint unsigned NOT NULL,
  `empresa_id` bigint unsigned DEFAULT NULL,
  `asistio` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acta_persona_acta_id_foreign` (`acta_id`),
  KEY `acta_persona_persona_id_foreign` (`persona_id`),
  KEY `acta_persona_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `acta_persona_acta_id_foreign` FOREIGN KEY (`acta_id`) REFERENCES `actas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `acta_persona_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `acta_persona_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.ciudades
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ciudades_nombre_unique` (`nombre`),
  KEY `ciudades_pais_id_foreign` (`pais_id`),
  CONSTRAINT `ciudades_pais_id_foreign` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.compromisos
CREATE TABLE IF NOT EXISTS `compromisos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `acta_id` bigint unsigned NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acta_persona_id` bigint unsigned NOT NULL,
  `fecha` date DEFAULT NULL,
  `estado` enum('Pendiente','En proceso','Cumplido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compromisos_acta_id_foreign` (`acta_id`),
  KEY `compromisos_acta_persona_id_foreign` (`acta_persona_id`),
  CONSTRAINT `compromisos_acta_id_foreign` FOREIGN KEY (`acta_id`) REFERENCES `actas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compromisos_acta_persona_id_foreign` FOREIGN KEY (`acta_persona_id`) REFERENCES `acta_persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.consecutivos_acta
CREATE TABLE IF NOT EXISTS `consecutivos_acta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anio` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mes` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consecutivo` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consecutivos_acta_anio_mes_unique` (`anio`,`mes`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_empresa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresas_nit_unique` (`nit`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.paises
CREATE TABLE IF NOT EXISTS `paises` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paises_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iniciales` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firma_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarjeta_profesional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_tarjeta` date DEFAULT NULL,
  `cedula` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_expedicion_cedula` date DEFAULT NULL,
  `lugar_expedicion_cedula` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personas_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `personas_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyectos_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `proyectos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.resumenes
CREATE TABLE IF NOT EXISTS `resumenes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `acta_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `horas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `facturable` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resumenes_acta_id_foreign` (`acta_id`),
  CONSTRAINT `resumenes_acta_id_foreign` FOREIGN KEY (`acta_id`) REFERENCES `actas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.tipos_acta
CREATE TABLE IF NOT EXISTS `tipos_acta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipos_acta_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table proyectoactas.versions
CREATE TABLE IF NOT EXISTS `versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` int NOT NULL DEFAULT '1' COMMENT 'Número de versión (1, 2, 3, etc.)',
  `fecha_creacion` date NOT NULL COMMENT 'Fecha de creación de la versión',
  `descripcion_cambio` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Descripción de los cambios realizados',
  `revisado_por` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de quien revisó',
  `fecha_revision` date DEFAULT NULL COMMENT 'Fecha de revisión',
  `aprobado_por` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de quien aprobó',
  `fecha_aprobado` date DEFAULT NULL COMMENT 'Fecha de aprobación',
  `estado` enum('Pendiente','Revisado','Aprobado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente' COMMENT 'Estado actual del documento',
  `fecha_aprobacion_documento` date DEFAULT NULL COMMENT 'Fecha de aprobación final del documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `versions_version_index` (`version`),
  KEY `versions_estado_index` (`estado`),
  KEY `versions_fecha_creacion_index` (`fecha_creacion`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
