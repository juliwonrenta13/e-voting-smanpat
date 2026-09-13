-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: evoting_smanpat
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `evoting_smanpat`
--

/*!40000 DROP DATABASE IF EXISTS `evoting_smanpat`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `evoting_smanpat` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `evoting_smanpat`;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `position_id` bigint unsigned NOT NULL,
  `candidate_number` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vision` text COLLATE utf8mb4_unicode_ci,
  `mission` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `candidates_position_id_candidate_number_unique` (`position_id`,`candidate_number`),
  CONSTRAINT `candidates_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates`
--

LOCK TABLES `candidates` WRITE;
/*!40000 ALTER TABLE `candidates` DISABLE KEYS */;
INSERT INTO `candidates` VALUES (1,1,1,'Ahmad Fauzi Pratama',NULL,'Mewujudkan OSIS SMAN 4 yang inklusif, inovatif, berintegritas, dan tanggap terhadap aspirasi generasi digital.','1. Meningkatkan kualitas kegiatan ekstrakurikuler berbasis teknologi dan minat bakat.\n2. Mengoptimalkan wadah aspirasi siswa secara transparan dan berkala.\n3. Membangun kolaborasi erat antarekskul dan organisasi sekolah.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(2,1,2,'Clarissa Putri Maharani',NULL,'Mewujudkan lingkungan sekolah yang harmonis, berprestasi unggul di kancah nasional, dan berkarakter Profil Pelajar Pancasila.','1. Mengembangkan program mentoring akademik dan kepemimpinan sebaya.\n2. Menggalakkan kepedulian lingkungan hidup dan gerakan ramah lingkungan di sekolah.\n3. Memperkuat sinergi antara siswa, guru, dan alumni.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(3,2,1,'Dinda Aurelia Zahra',NULL,'Administrasi OSIS yang transparan, terintegrasi digital, dan responsif.','1. Digitalisasi sistem persuratan dan notulensi rapat organisasi.\n2. Publikasi berkala buletin kegiatan OSIS secara digital.\n3. Pengelolaan arsip kegiatan sekolah yang rapi dan mudah diakses.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(4,2,2,'Bintang Ramadhan',NULL,'Sistem kesekretariatan yang rapi, akuntabel, dan mendukung koordinasi aktif antardivisi.','1. Standarisasi format proposal dan laporan pertanggungjawaban kegiatan.\n2. Optimalisasi platform komunikasi internal pengurus OSIS.\n3. Pelatihan penulisan dan administrasi untuk perwakilan kelas.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(5,3,1,'Fathir Nugraha',NULL,'Pengelolaan keuangan OSIS yang amanah, transparan, dan produktif.','1. Publikasi laporan kas berkala setiap akhir bulan melalui papan informasi digital.\n2. Efisiensi alokasi dana untuk mendukung program kerja siswa berprestasi.\n3. Inovasi penggalangan dana kreatif melalui kewirausahaan siswa.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(6,3,2,'Nadia Syahira',NULL,'Akuntabilitas finansial tanpa celah dengan pencatatan digital real-time.','1. Penerapan pembukuan berbasis aplikasi spreadsheet terpadu.\n2. Perencanaan anggaran berbasis prioritas kebutuhan peserta didik.\n3. Pendampingan administrasi kas untuk seluruh unit ekstrakurikuler.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(7,4,1,'Rian Aditya Saputra',NULL,'MPK sebagai pilar pengawas yang kritis, objektif, dan memperjuangkan hak suara seluruh siswa.','1. Menjalankan fungsi pengawasan dan evaluasi program OSIS secara objektif.\n2. Mengadakan forum dengar pendapat umum bersama perwakilan kelas setiap semester.\n3. Menampung dan mengadvokasi kebutuhan sarana belajar siswa ke pihak sekolah.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(8,4,2,'Salma Kirana',NULL,'Menjadikan MPK lembaga permusyawaratan yang berwibawa, solutif, dan mengedepankan musyawarah mufakat.','1. Peningkatan disiplin dan peran aktif ketua kelas dalam legislasi sekolah.\n2. Sosialisasi regulasi dan tata tertib sekolah dengan pendekatan persuasif.\n3. Menjembatani aspirasi siswa dan dewan guru secara konstruktif.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(9,5,1,'Kevin Pratama Wijaya',NULL,'Dokumentasi dan legislasi MPK yang tertib, modern, dan terdokumentasi akurat.','1. Penyusunan risalah sidang dan notulensi evaluasi kerja yang transparan.\n2. Pengarsipan dokumen kebijakan perwakilan kelas berbasis cloud storage.\n3. Penyelenggaraan kuesioner evaluasi kinerja berkala untuk siswa.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(10,5,2,'Hana Fitria Ningsih',NULL,'Komunikasi legislatif yang efektif antara perwakilan kelas dan badan pengurus harian.','1. Rekapitulasi usulan aspirasi kelas secara terstruktur dan cepat tanggap.\n2. Publikasi hasil sidang umum MPK kepada seluruh warga sekolah.\n3. Pembuatan agenda kerja dan jadwal pengawasan terkoordinasi.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(11,6,1,'Rizky Alamsyah',NULL,'Transparansi anggaran pengawasan dan operasional perwakilan kelas yang bertanggung jawab.','1. Manajemen anggaran sidang dan musyawarah perwakilan kelas yang hemat dan efektif.\n2. Laporan keuangan terbuka dan diaudit bersama pembina MPK.\n3. Pendampingan audit keuangan terhadap program kerja OSIS.','active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(12,6,2,'Tiara Anindya',NULL,'Tata kelola anggaran MPK yang profesional, disiplin, dan terbuka.','1. Penyusunan Rencana Anggaran Biaya (RAB) tahunan secara terperinci.\n2. Pemeriksaan realisasi anggaran setiap kegiatan kesiswaan.\n3. Penyediaan laporan pertanggungjawaban keuangan yang ringkas dan mudah dipahami.','active','2026-09-12 02:59:39','2026-09-12 02:59:39');
/*!40000 ALTER TABLE `candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `election_settings`
--

DROP TABLE IF EXISTS `election_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `election_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pemilihan Pengurus OSIS & MPK SMAN 4',
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2026/2027',
  `status` enum('draft','scheduled','open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_settings`
--

LOCK TABLES `election_settings` WRITE;
/*!40000 ALTER TABLE `election_settings` DISABLE KEYS */;
INSERT INTO `election_settings` VALUES (1,'Pemilihan Pengurus OSIS & MPK SMAN 4','2026/2027','open','2026-09-12 00:00:00','2026-09-19 23:59:59','2026-09-12 02:59:39','2026-09-12 02:59:39');
/*!40000 ALTER TABLE `election_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_01_01_000001_create_election_settings_table',1),(6,'2024_01_01_000002_create_positions_table',1),(7,'2024_01_01_000003_create_candidates_table',1),(8,'2024_01_01_000004_create_voters_table',1),(9,'2024_01_01_000005_create_votes_table',1),(10,'2024_01_01_000006_create_audit_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section` enum('OSIS','MPK') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'OSIS','Ketua OSIS',1,'active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(2,'OSIS','Sekretaris OSIS',2,'active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(3,'OSIS','Bendahara OSIS',3,'active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(4,'MPK','Ketua MPK',4,'active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(5,'MPK','Sekretaris MPK',5,'active','2026-09-12 02:59:39','2026-09-12 02:59:39'),(6,'MPK','Bendahara MPK',6,'active','2026-09-12 02:59:39','2026-09-12 02:59:39');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Panitia Pemilu SMAN 4','admin@smanpat.sch.id',NULL,'$2y$10$UPNO15pTwQbAcLeqSwHxNOWdeh9tuG2QfPVnz9rgVuveK72J0qvV2','admin',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('unused','used','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unused',
  `voted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voters_token_unique` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voters`
--

LOCK TABLES `voters` WRITE;
/*!40000 ALTER TABLE `voters` DISABLE KEYS */;
INSERT INTO `voters` VALUES (1,'VOTE8881','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(2,'VOTE8882','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(3,'VOTE8883','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(4,'VOTE8884','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(5,'VOTE8885','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(6,'VOTE8886','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(7,'VOTE8887','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(8,'VOTE8888','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(9,'VOTE8889','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(10,'VOTE8890','unused',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39'),(11,'USED7777','used','2026-09-12 08:59:39','2026-09-12 02:59:39','2026-09-12 02:59:39'),(12,'DISA9999','disabled',NULL,'2026-09-12 02:59:39','2026-09-12 02:59:39');
/*!40000 ALTER TABLE `voters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voter_id` bigint unsigned NOT NULL,
  `position_id` bigint unsigned NOT NULL,
  `candidate_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_voter_position_vote` (`voter_id`,`position_id`),
  KEY `votes_position_id_foreign` (`position_id`),
  KEY `votes_candidate_id_foreign` (`candidate_id`),
  CONSTRAINT `votes_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `votes_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `votes_voter_id_foreign` FOREIGN KEY (`voter_id`) REFERENCES `voters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'evoting_smanpat'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-12 19:03:50
