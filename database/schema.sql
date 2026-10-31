-- Schéma SQL pour MY TAXI (Compatible SQLite & MySQL)
CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ref_code VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    customer_email VARCHAR(150),
    pickup_location VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    ride_date DATE NOT NULL,
    ride_time VARCHAR(20) NOT NULL,
    vehicle_type VARCHAR(100) NOT NULL DEFAULT 'sedan',
    passengers INTEGER NOT NULL DEFAULT 1,
    luggage INTEGER NOT NULL DEFAULT 1,
    flight_number VARCHAR(50),
    notes TEXT,
    fare_estimate VARCHAR(50),
    status VARCHAR(30) NOT NULL DEFAULT 'pending', -- pending, confirmed, completed, cancelled
    driver_assigned VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS excursions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    destination_idea VARCHAR(255) NOT NULL,
    preferred_date DATE,
    passengers INTEGER DEFAULT 2,
    details TEXT,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    email VARCHAR(150),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'unread',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    key_name VARCHAR(50) PRIMARY KEY,
    value TEXT
);

-- Données initiales pour les paramètres
INSERT OR IGNORE INTO settings (key_name, value) VALUES
('company_name', 'MY TAXI'),
('phone_number', '+41 79 845 62 70'),
('phone_display', '079 845 62 70'),
('whatsapp_number', '41798456270'),
('email', 'contact@mytaxi-aigle.ch'),
('base_city', 'Aigle'),
('currency', 'CHF'),
('header_notice', 'Disponible 24/7 à Aigle, Stations Alpines & Transferts Aéroports');
