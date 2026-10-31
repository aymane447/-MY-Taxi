-- ============================================================
-- Schéma PostgreSQL pour Supabase (MY TAXI)
-- À exécuter dans : Supabase Dashboard > SQL Editor > New Query
-- ============================================================

-- 1. Table des réservations (Bookings)
CREATE TABLE IF NOT EXISTS bookings (
    id BIGSERIAL PRIMARY KEY,
    ref_code VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_email VARCHAR(150),
    pickup_location VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    ride_date DATE NOT NULL,
    ride_time VARCHAR(20) NOT NULL,
    vehicle_type VARCHAR(50) NOT NULL DEFAULT 'sedan',
    passengers INT NOT NULL DEFAULT 1,
    luggage INT NOT NULL DEFAULT 1,
    flight_number VARCHAR(100),
    notes TEXT,
    fare_estimate VARCHAR(100),
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    driver_assigned VARCHAR(100),
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 2. Table des demandes d'excursions
CREATE TABLE IF NOT EXISTS excursions (
    id BIGSERIAL PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    destination_idea VARCHAR(255) NOT NULL,
    preferred_date DATE,
    passengers INT DEFAULT 2,
    details TEXT,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 3. Table des messages de contact
CREATE TABLE IF NOT EXISTS contacts (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(150),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'unread',
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 4. Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 5. Table des paramètres
CREATE TABLE IF NOT EXISTS settings (
    key_name VARCHAR(100) PRIMARY KEY,
    value TEXT
);

-- 6. Créer le compte admin par défaut (admin / taxi2026) s'il n'existe pas
INSERT INTO admins (username, password_hash, display_name, email)
VALUES ('admin', '$2y$10$eE1t2oE7MvhV2j38N0D7Q.WzXbEeVtS3R6kYQf7pM5XgPjJbZqP9K', 'Administrateur MY TAXI', 'aymanedoublani2004@gmail.com')
ON CONFLICT (username) DO NOTHING;

