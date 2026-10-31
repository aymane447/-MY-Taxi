<?php
/**
 * API Backend - Traitement des formulaires et réservations
 * MY TAXI
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mailer.php';

$response = [
    'success' => false,
    'message' => 'Action invalide'
];

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// 1. Enregistrement d'une réservation de taxi
if ($action === 'book') {
    $pickup = isset($_POST['pickup']) ? cleanInput($_POST['pickup']) : '';
    $destination = isset($_POST['destination']) ? cleanInput($_POST['destination']) : '';
    $date = isset($_POST['date']) ? cleanInput($_POST['date']) : '';
    $time = isset($_POST['time']) ? cleanInput($_POST['time']) : '';
    $name = isset($_POST['name']) ? cleanInput($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $vehicle = isset($_POST['vehicle']) ? cleanInput($_POST['vehicle']) : 'sedan';
    $passengers = isset($_POST['passengers']) ? intval($_POST['passengers']) : 1;
    $luggage = isset($_POST['luggage']) ? intval($_POST['luggage']) : 1;
    $flightNumber = isset($_POST['flight_number']) ? cleanInput($_POST['flight_number']) : '';
    $notes = isset($_POST['notes']) ? cleanInput($_POST['notes']) : '';

    if (empty($pickup) || empty($destination) || empty($date) || empty($time) || empty($name) || empty($phone)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Veuillez remplir tous les champs obligatoires (Lieu de départ, destination, date, heure, nom et téléphone).'
        ]);
        exit;
    }

    try {
        $pdo = getDb();
        $refCode = generateRefCode();

        // Vérifier unicité du code
        $chk = $pdo->prepare("SELECT id FROM bookings WHERE ref_code = ?");
        $chk->execute([$refCode]);
        if ($chk->fetch()) {
            $refCode = 'MT-' . date('y') . '-' . mt_rand(10000, 99999);
        }

        $stmt = $pdo->prepare("
            INSERT INTO bookings (
                ref_code, customer_name, customer_phone, customer_email,
                pickup_location, destination, ride_date, ride_time,
                vehicle_type, passengers, luggage, flight_number, notes,
                fare_estimate, status, driver_assigned
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', '')
        ");

        $stmt->execute([
            $refCode,
            $name,
            $phone,
            $email,
            $pickup,
            $destination,
            $date,
            $time,
            $vehicle,
            $passengers,
            $luggage,
            $flightNumber,
            $notes,
            'Sur devis direct'
        ]);

        $bookingId = $pdo->lastInsertId();

        // ── Envoi des emails automatiques ──
        $emailData = [
            'ref_code'      => $refCode,
            'name'          => $name,
            'phone'         => $phone,
            'email'         => $email,
            'pickup'        => $pickup,
            'destination'   => $destination,
            'date'          => $date,
            'time'          => $time,
            'vehicle'       => $vehicle,
            'passengers'    => $passengers,
            'luggage'       => $luggage,
            'flight_number' => $flightNumber,
            'notes'         => $notes,
        ];

        // Email au chauffeur / dispatch
        $emailSentNotify = sendBookingNotification($emailData);

        // Email de confirmation au client (si email fourni)
        $emailSentClient = sendBookingConfirmation($emailData);

        // Préparation du message WhatsApp pré-formaté
        $vehicleLabel = [
            'sedan' => 'Berline Mercedes E-Class (1-4 pers.)',
            'van' => 'Van Mercedes V-Class (Jusqu\'à 7 pers.)',
            'minibus' => 'Minibus VIP (Jusqu\'à 16 pers.)'
        ][$vehicle] ?? $vehicle;

        $formattedDate = date('d.m.Y', strtotime($date));
        $whatsappMsg = "Bonjour MY TAXI,\n\nJe souhaite réserver une course :\n\n"
            . "📌 Réf : {$refCode}\n"
            . "👤 Nom : {$name}\n"
            . "📞 Tél : {$phone}\n"
            . "🚖 Prise en charge : {$pickup}\n"
            . "🎯 Destination : {$destination}\n"
            . "📅 Date : {$formattedDate}\n"
            . "⏰ Heure : {$time}\n"
            . "🚘 Véhicule : {$vehicleLabel}\n"
            . "👥 Passagers : {$passengers} | Bagages : {$luggage}\n";

        if (!empty($flightNumber)) {
            $whatsappMsg .= "✈️ Vol / Train : {$flightNumber}\n";
        }
        if (!empty($notes)) {
            $whatsappMsg .= "📝 Remarques : {$notes}\n";
        }
        $whatsappMsg .= "\nMerci de me confirmer la disponibilité et le tarif !";

        $whatsappUrl = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . rawurlencode($whatsappMsg);

        echo json_encode([
            'success' => true,
            'message' => 'Votre demande de réservation a été enregistrée avec succès ! Notre chauffeur vous contactera très rapidement pour confirmation.',
            'ref_code' => $refCode,
            'booking_id' => $bookingId,
            'whatsapp_url' => $whatsappUrl,
            'summary' => [
                'name' => $name,
                'pickup' => $pickup,
                'destination' => $destination,
                'date' => $formattedDate,
                'time' => $time,
                'vehicle' => $vehicleLabel
            ],
            'email_sent' => [
                'notification' => $emailSentNotify,
                'confirmation' => $emailSentClient
            ]
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la réservation : ' . $e->getMessage()]);
        exit;
    }
}

// 2. Enregistrement d'une excursion sur mesure
if ($action === 'excursion') {
    $name = isset($_POST['name']) ? cleanInput($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $idea = isset($_POST['idea']) ? cleanInput($_POST['idea']) : '';
    $date = isset($_POST['date']) ? cleanInput($_POST['date']) : null;
    $passengers = isset($_POST['passengers']) ? intval($_POST['passengers']) : 2;
    $details = isset($_POST['details']) ? cleanInput($_POST['details']) : '';

    if (empty($name) || empty($phone) || empty($idea)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Veuillez indiquer au minimum votre nom, téléphone et l\'idée de votre excursion.']);
        exit;
    }

    try {
        $pdo = getDb();
        $stmt = $pdo->prepare("
            INSERT INTO excursions (customer_name, customer_phone, destination_idea, preferred_date, passengers, details, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$name, $phone, $idea, $date, $passengers, $details]);

        // ── Email notification excursion ──
        sendExcursionNotification([
            'name'       => $name,
            'phone'      => $phone,
            'idea'       => $idea,
            'date'       => $date,
            'passengers' => $passengers,
            'details'    => $details,
        ]);

        $dateFormatted = !empty($date) ? date('d.m.Y', strtotime($date)) : 'À convenir';
        $whatsappMsg = "Bonjour MY TAXI,\n\nJe souhaite organiser une excursion sur mesure :\n\n"
            . "👤 Nom : {$name}\n"
            . "📞 Téléphone : {$phone}\n"
            . "🗺️ Idée : {$idea}\n"
            . "👥 Nombre de personnes : {$passengers}\n"
            . "📅 Date souhaitée : {$dateFormatted}\n";
        if (!empty($details)) {
            $whatsappMsg .= "📝 Détails : {$details}\n";
        }
        $whatsappMsg .= "\nPouvez-vous me proposer un itinéraire et un tarif ? Merci !";

        $whatsappUrl = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . rawurlencode($whatsappMsg);

        echo json_encode([
            'success' => true,
            'message' => 'Votre demande d\'excursion a bien été transmise ! Nous allons vous préparer une proposition personnalisée.',
            'whatsapp_url' => $whatsappUrl
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()]);
        exit;
    }
}

// 3. Message de contact
if ($action === 'contact') {
    $name = isset($_POST['name']) ? cleanInput($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? cleanInput($_POST['subject']) : 'Demande générale';
    $message = isset($_POST['message']) ? cleanInput($_POST['message']) : '';

    if (empty($name) || (empty($phone) && empty($email)) || empty($message)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Veuillez renseigner votre nom, un moyen de contact et votre message.']);
        exit;
    }

    try {
        $pdo = getDb();
        $stmt = $pdo->prepare("INSERT INTO contacts (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $subject, $message]);

        // ── Email notification contact ──
        sendContactNotification([
            'name'    => $name,
            'phone'   => $phone,
            'email'   => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Merci pour votre message ! Notre équipe MY TAXI vous répondra dans les plus brefs délais.'
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Action inconnue']);
