<?php
/**
 * Admin API - Gestion AJAX du tableau de bord MY TAXI
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

// Vérification de sécurité
if (!isAdminLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Session expirée ou non autorisée']);
    exit;
}

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
$pdo = getDb();

// 1. Exporter en CSV
if ($action === 'export_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=my-taxi-courses-' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 pour Excel

    fputcsv($output, ['Référence', 'Client', 'Téléphone', 'Email', 'Prise en charge', 'Destination', 'Date', 'Heure', 'Véhicule', 'Passagers', 'Bagages', 'Vol/Train', 'Statut', 'Chauffeur', 'Date Création']);

    $stmt = $pdo->query("SELECT * FROM bookings ORDER BY id DESC");
    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['ref_code'],
            $row['customer_name'],
            $row['customer_phone'],
            $row['customer_email'],
            $row['pickup_location'],
            $row['destination'],
            $row['ride_date'],
            $row['ride_time'],
            $row['vehicle_type'],
            $row['passengers'],
            $row['luggage'],
            $row['flight_number'],
            $row['status'],
            $row['driver_assigned'],
            $row['created_at']
        ]);
    }
    fclose($output);
    exit;
}

// Pour les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// 2. Mettre à jour le statut d'une réservation
if ($action === 'update_status') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? cleanInput($_POST['status']) : '';

    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (!$id || !in_array($status, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Statut mis à jour avec succès',
        'status' => $status
    ]);
    exit;
}

// 3. Assigner un chauffeur / note
if ($action === 'assign_driver') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $driver = isset($_POST['driver']) ? cleanInput($_POST['driver']) : '';

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID manquant']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE bookings SET driver_assigned = ? WHERE id = ?");
    $stmt->execute([$driver, $id]);

    echo json_encode(['success' => true, 'message' => 'Chauffeur assigné avec succès']);
    exit;
}

// 4. Supprimer une réservation
if ($action === 'delete_booking') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID manquant']);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Course supprimée avec succès']);
    exit;
}

// 5. Ajouter une réservation manuelle (ex: prise par téléphone)
if ($action === 'add_manual_booking') {
    $name = isset($_POST['name']) ? cleanInput($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $pickup = isset($_POST['pickup']) ? cleanInput($_POST['pickup']) : '';
    $destination = isset($_POST['destination']) ? cleanInput($_POST['destination']) : '';
    $date = isset($_POST['date']) ? cleanInput($_POST['date']) : '';
    $time = isset($_POST['time']) ? cleanInput($_POST['time']) : '';
    $vehicle = isset($_POST['vehicle']) ? cleanInput($_POST['vehicle']) : 'sedan';
    $passengers = isset($_POST['passengers']) ? intval($_POST['passengers']) : 1;
    $status = isset($_POST['status']) ? cleanInput($_POST['status']) : 'confirmed';
    $notes = isset($_POST['notes']) ? cleanInput($_POST['notes']) : 'Course ajoutée manuellement';

    if (empty($name) || empty($phone) || empty($pickup) || empty($destination) || empty($date) || empty($time)) {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir les informations obligatoires']);
        exit;
    }

    $refCode = generateRefCode();
    $stmt = $pdo->prepare("
        INSERT INTO bookings (ref_code, customer_name, customer_phone, pickup_location, destination, ride_date, ride_time, vehicle_type, passengers, status, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$refCode, $name, $phone, $pickup, $destination, $date, $time, $vehicle, $passengers, $status, $notes]);

    echo json_encode(['success' => true, 'message' => 'Course manuelle ajoutée avec succès !', 'ref' => $refCode]);
    exit;
}

// 6. Mettre à jour les paramètres généraux
if ($action === 'save_settings') {
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $whatsapp = isset($_POST['whatsapp']) ? cleanInput($_POST['whatsapp']) : '';
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $notice = isset($_POST['notice']) ? cleanInput($_POST['notice']) : '';

    $stmt = $pdo->prepare("INSERT OR REPLACE INTO settings (key_name, value) VALUES (?, ?)");
    $stmt->execute(['phone_number', $phone]);
    $stmt->execute(['whatsapp_number', $whatsapp]);
    $stmt->execute(['email', $email]);
    $stmt->execute(['header_notice', $notice]);

    echo json_encode(['success' => true, 'message' => 'Paramètres enregistrés']);
    exit;
}

// 7. Mettre à jour le mot de passe admin
if ($action === 'update_password') {
    $current = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new = isset($_POST['new_password']) ? $_POST['new_password'] : '';

    if (strlen($new) < 6) {
        echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe doit comporter au moins 6 caractères']);
        exit;
    }

    $adminId = $_SESSION['mytaxi_admin_id'];
    $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = ?");
    $stmt->execute([$adminId]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect']);
        exit;
    }

    $newHash = password_hash($new, PASSWORD_DEFAULT);
    $upd = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
    $upd->execute([$newHash, $adminId]);

    echo json_encode(['success' => true, 'message' => 'Mot de passe modifié avec succès']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
