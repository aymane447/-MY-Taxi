<?php
/**
 * Configuration & Connexion Base de Données - MY TAXI
 * Supporte SQLite out-of-the-box (zéro-config) et MySQL/MariaDB
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration Base de Données
define('DB_TYPE', 'sqlite'); // 'sqlite' ou 'mysql'
define('SQLITE_FILE', __DIR__ . '/database/mytaxi.sqlite');

// Paramètres MySQL (si DB_TYPE = 'mysql')
define('MYSQL_HOST', 'localhost');
define('MYSQL_PORT', '3306');
define('MYSQL_NAME', 'mytaxi_db');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');

// Paramètres de l'entreprise
define('SITE_NAME', 'MY TAXI');
define('COMPANY_NAME', 'My Taxi SARL');
define('COMPANY_ADDRESS', 'Avenue des Alpes 28, 1860 Aigle');
define('SITE_TAGLINE', 'Taxi à Aigle & Région · 24/7');
define('PHONE_NUMBER', '+41 79 266 61 21');
define('PHONE_CLEAN', '+41792666121');
define('PHONE_DISPLAY', '079 266 61 21');
define('WHATSAPP_NUMBER', '41792666121');
define('CONTACT_EMAIL', 'contact@mytaxi-aigle.ch');
define('DEFAULT_CURRENCY', 'CHF');

// ─── Configuration SMTP (Gmail App Password) ────────────────────────────
// ⚠️  Remplacez les valeurs ci-dessous par vos identifiants réels
define('SMTP_ENABLED',   true);                          // Activer/Désactiver l'envoi d'emails
define('SMTP_HOST',      'smtp.gmail.com');               // Serveur SMTP
define('SMTP_PORT',      587);                            // Port TLS
define('SMTP_SECURE',    'tls');                          // 'tls' ou 'ssl'
define('SMTP_USERNAME',  'aymanedoublani2004@gmail.com');         // Votre adresse Gmail
define('SMTP_PASSWORD',  'fwwx tgsk fhij nvnf');           // Mot de passe d'application 16 caractères
define('SMTP_FROM_EMAIL', 'aymanedoublani2004@gmail.com');        // Adresse expéditeur
define('SMTP_FROM_NAME',  'MY TAXI · Aigle');              // Nom affiché
define('SMTP_NOTIFY_TO',  'aymanedoublani2004@gmail.com');         // Email qui reçoit les notifications de réservation

/**
 * Obtenir la connexion PDO
 */
function getDb() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        if (DB_TYPE === 'sqlite') {
            $dir = dirname(SQLITE_FILE);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $pdo = new PDO('sqlite:' . SQLITE_FILE);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA journal_mode = WAL;');
        } else {
            $dsn = 'mysql:host=' . MYSQL_HOST . ';port=' . MYSQL_PORT . ';dbname=' . MYSQL_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, MYSQL_USER, MYSQL_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        initDatabase($pdo);
        return $pdo;
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage()));
    }
}

/**
 * Initialiser la base de données et insérer les données par défaut si besoin
 */
function initDatabase($pdo) {
    // Création des tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ref_code TEXT NOT NULL UNIQUE,
            customer_name TEXT NOT NULL,
            customer_phone TEXT NOT NULL,
            customer_email TEXT,
            pickup_location TEXT NOT NULL,
            destination TEXT NOT NULL,
            ride_date TEXT NOT NULL,
            ride_time TEXT NOT NULL,
            vehicle_type TEXT NOT NULL DEFAULT 'sedan',
            passengers INTEGER NOT NULL DEFAULT 1,
            luggage INTEGER NOT NULL DEFAULT 1,
            flight_number TEXT,
            notes TEXT,
            fare_estimate TEXT,
            status TEXT NOT NULL DEFAULT 'pending',
            driver_assigned TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS excursions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name TEXT NOT NULL,
            customer_phone TEXT NOT NULL,
            destination_idea TEXT NOT NULL,
            preferred_date TEXT,
            passengers INTEGER DEFAULT 2,
            details TEXT,
            status TEXT NOT NULL DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            phone TEXT,
            email TEXT,
            subject TEXT,
            message TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'unread',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            display_name TEXT NOT NULL,
            email TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS settings (
            key_name TEXT PRIMARY KEY,
            value TEXT
        );
    ");

    // Créer admin par défaut si inexistant (admin / taxi2026)
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM admins");
    $stmt->execute();
    if ($stmt->fetch()['cnt'] == 0) {
        $defaultHash = password_hash('taxi2026', PASSWORD_DEFAULT);
        $insertAdmin = $pdo->prepare("INSERT INTO admins (username, password_hash, display_name, email) VALUES (?, ?, ?, ?)");
        $insertAdmin->execute(['admin', $defaultHash, 'Administrateur MY TAXI', 'contact@mytaxi-aigle.ch']);
    }

    // Insérer des exemples de réservations si vide
    $stmtBookings = $pdo->prepare("SELECT COUNT(*) as cnt FROM bookings");
    $stmtBookings->execute();
    if ($stmtBookings->fetch()['cnt'] == 0) {
        $seedBookings = [
            [
                'ref_code' => 'MT-2026-1042',
                'customer_name' => 'Marc Dupont',
                'customer_phone' => '+41 78 612 45 90',
                'customer_email' => 'marc.dupont@bluewin.ch',
                'pickup_location' => 'Gare CFF d\'Aigle',
                'destination' => 'Villars-sur-Ollon (Hôtel du Golf)',
                'ride_date' => date('Y-m-d', strtotime('+1 day')),
                'ride_time' => '14:30',
                'vehicle_type' => 'van',
                'passengers' => 4,
                'luggage' => 4,
                'flight_number' => '',
                'notes' => 'Équipement de ski inclus',
                'fare_estimate' => 'Sur devis',
                'status' => 'confirmed',
                'driver_assigned' => 'Chauffeur 1'
            ],
            [
                'ref_code' => 'MT-2026-1043',
                'customer_name' => 'Sarah Jenkins',
                'customer_phone' => '+44 7911 123456',
                'customer_email' => 'sarah.j@gmail.com',
                'pickup_location' => 'Genève Aéroport (GVA) Terminal 1',
                'destination' => 'Gstaad Palace',
                'ride_date' => date('Y-m-d', strtotime('+2 days')),
                'ride_time' => '11:15',
                'vehicle_type' => 'sedan',
                'passengers' => 2,
                'luggage' => 3,
                'flight_number' => 'BA 728',
                'notes' => 'Accueil pancarte personnalisée',
                'fare_estimate' => 'VIP Transfert',
                'status' => 'pending',
                'driver_assigned' => ''
            ],
            [
                'ref_code' => 'MT-2026-1044',
                'customer_name' => 'Alexandre Favre',
                'customer_phone' => '+41 79 330 22 11',
                'customer_email' => 'a.favre@swissonline.ch',
                'pickup_location' => 'Aigle Centre',
                'destination' => 'Leysin (École Internationale)',
                'ride_date' => date('Y-m-d'),
                'ride_time' => '18:00',
                'vehicle_type' => 'sedan',
                'passengers' => 1,
                'luggage' => 1,
                'flight_number' => '',
                'notes' => 'Course urgente',
                'fare_estimate' => 'Standard',
                'status' => 'completed',
                'driver_assigned' => 'Chauffeur 1'
            ],
            [
                'ref_code' => 'MT-2026-1045',
                'customer_name' => 'Laurent Mercier',
                'customer_phone' => '+33 6 45 89 12 34',
                'customer_email' => 'laurent.m@orange.fr',
                'pickup_location' => 'Aigle',
                'destination' => 'Châtel France (Portes du Soleil)',
                'ride_date' => date('Y-m-d', strtotime('+3 days')),
                'ride_time' => '09:00',
                'vehicle_type' => 'minibus',
                'passengers' => 8,
                'luggage' => 8,
                'flight_number' => '',
                'notes' => 'Groupe séjour chalet',
                'fare_estimate' => 'Forfait Groupe',
                'status' => 'pending',
                'driver_assigned' => ''
            ]
        ];

        $ins = $pdo->prepare("INSERT INTO bookings (ref_code, customer_name, customer_phone, customer_email, pickup_location, destination, ride_date, ride_time, vehicle_type, passengers, luggage, flight_number, notes, fare_estimate, status, driver_assigned) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($seedBookings as $b) {
            $ins->execute([
                $b['ref_code'], $b['customer_name'], $b['customer_phone'], $b['customer_email'],
                $b['pickup_location'], $b['destination'], $b['ride_date'], $b['ride_time'],
                $b['vehicle_type'], $b['passengers'], $b['luggage'], $b['flight_number'],
                $b['notes'], $b['fare_estimate'], $b['status'], $b['driver_assigned']
            ]);
        }
    }
}

/**
 * Nettoyer les entrées
 */
function cleanInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Générer un code référence unique pour une réservation
 */
function generateRefCode() {
    return 'MT-' . date('y') . '-' . mt_rand(1000, 9999);
}

/**
 * Vérifier l'authentification de l'administrateur
 */
function isAdminLoggedIn() {
    return isset($_SESSION['mytaxi_admin_id']) && !empty($_SESSION['mytaxi_admin_id']);
}

/**
 * Exiger une connexion admin ou rediriger
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
