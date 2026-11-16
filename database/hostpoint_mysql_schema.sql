-- ============================================================
-- Schéma MySQL / MariaDB pour Hostpoint (MY TAXI)
-- À importer directement dans phpMyAdmin (Bouton bleu PHPMYADMIN)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Table des réservations (Bookings)
CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ref_code` VARCHAR(50) NOT NULL UNIQUE,
    `customer_name` VARCHAR(150) NOT NULL,
    `customer_phone` VARCHAR(50) NOT NULL,
    `customer_email` VARCHAR(150) DEFAULT NULL,
    `pickup_location` VARCHAR(255) NOT NULL,
    `destination` VARCHAR(255) NOT NULL,
    `ride_date` DATE NOT NULL,
    `ride_time` VARCHAR(20) NOT NULL,
    `vehicle_type` VARCHAR(50) NOT NULL DEFAULT 'sedan',
    `passengers` INT NOT NULL DEFAULT 1,
    `luggage` INT NOT NULL DEFAULT 1,
    `flight_number` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `fare_estimate` VARCHAR(100) DEFAULT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
    `driver_assigned` VARCHAR(100) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table des demandes d'excursions
CREATE TABLE IF NOT EXISTS `excursions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(150) NOT NULL,
    `customer_phone` VARCHAR(50) NOT NULL,
    `destination_idea` VARCHAR(255) NOT NULL,
    `preferred_date` DATE DEFAULT NULL,
    `passengers` INT DEFAULT 2,
    `details` TEXT DEFAULT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table des messages de contact
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `subject` VARCHAR(200) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'unread',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table des administrateurs
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Table des paramètres (Settings)
CREATE TABLE IF NOT EXISTS `settings` (
    `key_name` VARCHAR(50) PRIMARY KEY,
    `value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compte Administrateur par défaut (admin / taxi2026)
INSERT INTO `admins` (`username`, `password_hash`, `display_name`, `email`)
VALUES ('admin', '$2y$10$us1ayuzU7o/zyIF6R/N8WOEaHKwX8r2Z.KzuaHzoCyEk3U/GZAPkG', 'Administrateur MY TAXI', 'my-taxiAigle@hotmail.com')
ON DUPLICATE KEY UPDATE `email` = 'my-taxiAigle@hotmail.com';

-- Paramètres initiaux
INSERT INTO `settings` (`key_name`, `value`) VALUES
('company_name', 'MY TAXI'),
('phone_number', '+41 79 266 61 21'),
('phone_display', '+41 79 266 61 21'),
('whatsapp_number', '41792666121'),
('email', 'my-taxiAigle@hotmail.com'),
('base_city', 'Aigle'),
('currency', 'CHF'),
('header_notice', 'Disponible 24/7 à Aigle, Stations Alpines & Transferts Aéroports')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

SET FOREIGN_KEY_CHECKS = 1;

