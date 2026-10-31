<?php
/**
 * Script de migration des données SQLite vers Supabase
 * Usage : php migrate_to_supabase.php
 */

require_once __DIR__ . '/config.php';

echo "=== MIGRATION SQLITE VERS SUPABASE ===\n\n";

// 1. Connexion SQLite
try {
    $sqlite = new PDO('sqlite:' . SQLITE_FILE);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlite->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "1. Connexion SQLite locale : OK\n";
} catch (Exception $e) {
    die("ERREUR SQLite : " . $e->getMessage() . "\n");
}

// 2. Connexion Supabase
try {
    $dsn = 'pgsql:host=' . SUPABASE_HOST . ';port=' . SUPABASE_PORT . ';dbname=' . SUPABASE_DB . ';sslmode=require';
    $supabase = new PDO($dsn, SUPABASE_USER, SUPABASE_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "2. Connexion Supabase (PostgreSQL) : OK\n\n";
} catch (Exception $e) {
    die("ERREUR Supabase : Impossible de se connecter avec les identifiants actuels.\nVérifiez SUPABASE_HOST et SUPABASE_PASS dans config.php.\nMessage : " . $e->getMessage() . "\n");
}

// 3. Migration des Bookings
echo "3. Migration des réservations...\n";
$bookings = $sqlite->query("SELECT * FROM bookings")->fetchAll();
$insBooking = $supabase->prepare("
    INSERT INTO bookings (ref_code, customer_name, customer_phone, customer_email, pickup_location, destination, ride_date, ride_time, vehicle_type, passengers, luggage, flight_number, notes, fare_estimate, status, driver_assigned, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON CONFLICT (ref_code) DO NOTHING
");

$cntBookings = 0;
foreach ($bookings as $b) {
    $insBooking->execute([
        $b['ref_code'], $b['customer_name'], $b['customer_phone'], $b['customer_email'],
        $b['pickup_location'], $b['destination'], $b['ride_date'], $b['ride_time'],
        $b['vehicle_type'], $b['passengers'], $b['luggage'], $b['flight_number'],
        $b['notes'], $b['fare_estimate'], $b['status'], $b['driver_assigned'],
        $b['created_at']
    ]);
    $cntBookings++;
}
echo "   -> $cntBookings réservation(s) copiée(s) vers Supabase avec succès.\n\n";

// 4. Migration des Admins
echo "4. Migration des administrateurs...\n";
$admins = $sqlite->query("SELECT * FROM admins")->fetchAll();
$insAdmin = $supabase->prepare("
    INSERT INTO admins (username, password_hash, display_name, email, created_at)
    VALUES (?, ?, ?, ?, ?)
    ON CONFLICT (username) DO NOTHING
");
$cntAdmins = 0;
foreach ($admins as $a) {
    $insAdmin->execute([
        $a['username'], $a['password_hash'], $a['display_name'], $a['email'], $a['created_at']
    ]);
    $cntAdmins++;
}
echo "   -> $cntAdmins administrateur(s) copié(s) vers Supabase.\n\n";

echo "=== MIGRATION TERMINÉE AVEC SUCCÈS ! ===\n";

