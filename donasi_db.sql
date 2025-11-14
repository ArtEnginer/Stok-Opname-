-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 14, 2025 at 08:54 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `donasi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `secret` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `secret2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text COLLATE utf8mb4_general_ci,
  `force_reset` tinyint(1) NOT NULL DEFAULT '0',
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'admin@gmail.com', NULL, '2025-11-14 14:28:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int UNSIGNED NOT NULL,
  `selector` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `hashedValidator` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_type` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `target_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `collected_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `donor_count` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `images` text COLLATE utf8mb4_general_ci COMMENT 'JSON array of additional images',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('draft','active','completed','cancelled') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `organizer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `organizer_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `organizer_email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `views` int NOT NULL DEFAULT '0',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `category_id`, `title`, `slug`, `short_description`, `description`, `target_amount`, `collected_amount`, `donor_count`, `image`, `images`, `start_date`, `end_date`, `status`, `is_featured`, `is_urgent`, `organizer_name`, `organizer_phone`, `organizer_email`, `views`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bantu Adik Rina Melawan Kanker Darah', 'bantu-adik-rina-melawan-kanker-darah-1763104720', 'Adik Rina (8 tahun) membutuhkan biaya pengobatan kanker darah sebesar Rp 200 juta', 'Adik Rina adalah seorang anak berusia 8 tahun yang sangat ceria dan pintar. Namun, takdir berkata lain ketika ia didiagnosa menderita Leukemia (kanker darah) pada bulan Januari 2024.\n\nKondisi Rina semakin memburuk dan membutuhkan pengobatan intensif berupa kemoterapi dan transplantasi sumsum tulang. Total biaya yang dibutuhkan mencapai Rp 200.000.000.\n\nOrangtua Rina hanyalah buruh pabrik dengan penghasilan pas-pasan. Mereka sudah menjual semua yang mereka miliki namun masih belum cukup untuk biaya pengobatan.\n\nMari kita bantu Adik Rina untuk mendapatkan pengobatan yang layak dan kembali bermain seperti anak seusianya.', '200000000.00', '45000000.00', 234, NULL, NULL, '2025-10-15', '2026-01-13', 'active', 1, 1, 'Yayasan Peduli Anak Indonesia', '081234567890', 'ypai@example.com', 5432, NULL, '2025-10-15 14:18:40', '2025-11-14 14:18:40'),
(2, 2, 'Beasiswa untuk 100 Anak Kurang Mampu', 'beasiswa-untuk-100-anak-kurang-mampu-1763104720', 'Program beasiswa pendidikan untuk 100 anak dari keluarga prasejahtera', 'Indonesia memiliki banyak anak-anak cerdas yang terpaksa putus sekolah karena keterbatasan biaya. Melalui program ini, kami ingin memberikan beasiswa pendidikan kepada 100 anak dari keluarga kurang mampu.\n\nBeasiswa ini akan mencakup:\n- Biaya sekolah selama 1 tahun\n- Seragam dan perlengkapan sekolah\n- Buku pelajaran\n- Uang saku bulanan\n\nDengan target dana Rp 500 juta, setiap anak akan mendapat bantuan senilai Rp 5 juta per tahun.\n\nMari bersama-sama memberikan kesempatan kepada anak-anak Indonesia untuk meraih mimpi mereka melalui pendidikan!', '500000000.00', '185000000.00', 876, NULL, NULL, '2025-10-25', '2026-01-23', 'active', 1, 0, 'Komunitas Pendidikan Indonesia', '081234567891', 'kpi@example.com', 8234, NULL, '2025-10-25 14:18:40', '2025-11-14 14:18:40'),
(3, 3, 'Bantu Korban Banjir Bandang di Sumatra', 'bantu-korban-banjir-bandang-sumatra-1763104720', 'Ribuan warga kehilangan rumah akibat banjir bandang, mari kita ulurkan tangan', 'Banjir bandang yang terjadi di Sumatra telah menghancurkan ratusan rumah dan merenggut harta benda ribuan warga. Mereka kini mengungsi dan membutuhkan bantuan segera.\n\nBantuan yang dibutuhkan:\n- Makanan dan air bersih\n- Pakaian dan selimut\n- Obat-obatan\n- Tenda darurat\n- Biaya pembangunan rumah sementara\n\nSetiap donasi Anda akan sangat berarti bagi mereka yang kehilangan segalanya. Mari kita tunjukkan kepedulian kita sebagai sesama anak bangsa.', '1000000000.00', '320000000.00', 1543, NULL, NULL, '2025-11-09', '2025-12-09', 'active', 1, 1, 'PMI Sumatra', '081234567892', 'pmi.sumatra@example.com', 12544, NULL, '2025-11-09 14:18:40', '2025-11-14 15:33:57'),
(4, 4, 'Renovasi Panti Asuhan Cahaya Kasih', 'renovasi-panti-asuhan-cahaya-kasih-1763104720', 'Panti asuhan dengan 50 anak membutuhkan renovasi mendesak', 'Panti Asuhan Cahaya Kasih menampung 50 anak yatim piatu. Bangunan panti yang sudah berusia 40 tahun kini dalam kondisi memprihatinkan dengan atap bocor, tembok retak, dan kamar mandi rusak.\n\nRencana renovasi meliputi:\n- Perbaikan atap dan plafon\n- Pengecatan ulang\n- Renovasi kamar mandi\n- Perbaikan sistem listrik\n- Pembuatan ruang belajar yang layak\n\nDengan kondisi bangunan yang baik, anak-anak dapat tumbuh dan belajar dengan lebih nyaman dan aman.', '150000000.00', '67000000.00', 342, NULL, NULL, '2025-10-30', '2025-12-29', 'active', 0, 0, 'Panti Asuhan Cahaya Kasih', '081234567893', 'cahayakasih@example.com', 3421, NULL, '2025-10-30 14:18:40', '2025-11-14 14:18:40'),
(5, 5, 'Program Penanaman 10.000 Pohon', 'program-penanaman-10000-pohon-1763104720', 'Mari bersama menanam 10.000 pohon untuk masa depan yang lebih hijau', 'Indonesia kehilangan jutaan hektar hutan setiap tahunnya. Melalui program ini, kami akan menanam 10.000 pohon di berbagai wilayah yang mengalami deforestasi.\n\nProgram ini mencakup:\n- Pembelian bibit pohon berkualitas\n- Penanaman dengan melibatkan masyarakat lokal\n- Perawatan pohon selama 2 tahun pertama\n- Monitoring pertumbuhan pohon\n\nSetiap Rp 50.000 dapat menanam 1 pohon. Mari kita ciptakan masa depan yang lebih hijau untuk generasi mendatang!', '500000000.00', '125000000.00', 2500, NULL, NULL, '2025-11-04', '2026-02-02', 'active', 1, 0, 'Yayasan Hijau Indonesia', '081234567894', 'hijau.indonesia@example.com', 6789, NULL, '2025-11-04 14:18:40', '2025-11-14 14:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_updates`
--

CREATE TABLE `campaign_updates` (
  `id` int UNSIGNED NOT NULL,
  `campaign_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kesehatan', 'kesehatan', 'Donasi untuk kesehatan dan pengobatan', 'fa-heart-pulse', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40'),
(2, 'Pendidikan', 'pendidikan', 'Donasi untuk pendidikan dan beasiswa', 'fa-graduation-cap', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40'),
(3, 'Bencana Alam', 'bencana-alam', 'Bantuan untuk korban bencana alam', 'fa-house-tsunami', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40'),
(4, 'Kemanusiaan', 'kemanusiaan', 'Donasi untuk kegiatan kemanusiaan', 'fa-hands-holding-child', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40'),
(5, 'Lingkungan', 'lingkungan', 'Donasi untuk pelestarian lingkungan', 'fa-leaf', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40'),
(6, 'Pemberdayaan', 'pemberdayaan', 'Donasi untuk pemberdayaan masyarakat', 'fa-handshake', 1, '2025-11-14 14:18:40', '2025-11-14 14:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int UNSIGNED NOT NULL,
  `campaign_id` int UNSIGNED NOT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int UNSIGNED NOT NULL,
  `campaign_id` int UNSIGNED NOT NULL,
  `donor_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `donor_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `donor_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_proof` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `verified_by` int UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000001', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1763104672, 1),
(2, '2024-01-01-000002', 'App\\Database\\Migrations\\CreateCampaignsTable', 'default', 'App', 1763104673, 1),
(3, '2024-01-01-000003', 'App\\Database\\Migrations\\CreateDonationsTable', 'default', 'App', 1763104673, 1),
(4, '2024-07-08-120959', 'App\\Database\\Migrations\\AuthMigration', 'default', 'App', 1763104675, 1),
(5, '2024-07-10-064057', 'App\\Database\\Migrations\\CreateCiSessionsTable', 'default', 'App', 1763104690, 2),
(6, '2024-01-01-000004', 'App\\Database\\Migrations\\CreateCampaignUpdatesTable', 'default', 'App', 1763107080, 3),
(7, '2024-01-01-000005', 'App\\Database\\Migrations\\CreateCommentsTable', 'default', 'App', 1763107080, 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `context` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `type` varchar(31) COLLATE utf8mb4_general_ci DEFAULT 'string',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `picture` varchar(256) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg?w=740&t=st=1685421850~exp=1685422450~hmac=eb42cbd8af487fbbbd99b365d786a5de743cc58fd7e31cdcfd0367ae97fdcd71',
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `campaigns_category_id_foreign` (`category_id`);

--
-- Indexes for table `campaign_updates`
--
ALTER TABLE `campaign_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_updates_campaign_id_foreign` (`campaign_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_campaign_id_foreign` (`campaign_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `donations_campaign_id_foreign` (`campaign_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `campaign_updates`
--
ALTER TABLE `campaign_updates`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `campaign_updates`
--
ALTER TABLE `campaign_updates`
  ADD CONSTRAINT `campaign_updates_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
