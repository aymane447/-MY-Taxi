<?php
/**
 * Tableau de Bord Administrateur - MY TAXI
 */
require_once __DIR__ . '/../config.php';
requireAdmin();

$pdo = getDb();

// Statistiques
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn(),
    'confirmed' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'confirmed'")->fetchColumn(),
    'completed' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'completed'")->fetchColumn(),
    'cancelled' => $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'cancelled'")->fetchColumn(),
    'excursions' => $pdo->query("SELECT COUNT(*) FROM excursions")->fetchColumn(),
    'contacts' => $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
];

// Récupérer les réservations
$bookingsStmt = $pdo->query("SELECT * FROM bookings ORDER BY ride_date DESC, ride_time DESC, id DESC");
$bookings = $bookingsStmt->fetchAll();

// Récupérer les excursions
$excursionsStmt = $pdo->query("SELECT * FROM excursions ORDER BY id DESC LIMIT 50");
$excursions = $excursionsStmt->fetchAll();

// Récupérer les messages
$contactsStmt = $pdo->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 50");
$contacts = $contactsStmt->fetchAll();

// Récupérer les réglages
$settingsStmt = $pdo->query("SELECT * FROM settings");
$settingsRows = $settingsStmt->fetchAll();
$settings = [];
foreach ($settingsRows as $row) {
    $settings[$row['key_name']] = $row['value'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration · MY TAXI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" type="image/png" href="../assets/logo.png">
</head>
<body class="admin-body">

    <!-- En-tête Admin -->
    <header class="admin-header">
        <div class="header-container">
            <div class="brand-area">
                <a href="index.php" class="admin-logo">
                    <img src="../assets/logo-white.png" alt="MY TAXI Admin" height="38">
                </a>
                <span class="badge-dispatch">Centrale de Réservation & Dispatch</span>
            </div>
            
            <div class="user-area">
                <span class="admin-welcome">
                    <span class="pulse-dot"></span>
                    Connecté : <strong><?= htmlspecialchars($_SESSION['mytaxi_admin_name'] ?? 'Admin') ?></strong>
                </span>
                <a href="../" target="_blank" class="btn-sm btn-ghost">Voir le site public ↗</a>
                <a href="logout.php" class="btn-sm btn-danger-outline">Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <nav class="sidebar-nav">
                <a href="#tab-bookings" class="nav-item active" data-tab="tab-bookings">
                    <span class="icon">🚖</span>
                    <span>Réservations de Taxi</span>
                    <?php if ($stats['pending'] > 0): ?>
                        <span class="badge-count"><?= $stats['pending'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#tab-excursions" class="nav-item" data-tab="tab-excursions">
                    <span class="icon">🏔️</span>
                    <span>Excursions sur mesure</span>
                    <?php if ($stats['excursions'] > 0): ?>
                        <span class="badge-count subtle"><?= $stats['excursions'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#tab-contacts" class="nav-item" data-tab="tab-contacts">
                    <span class="icon">✉️</span>
                    <span>Messages de contact</span>
                    <?php if ($stats['contacts'] > 0): ?>
                        <span class="badge-count subtle"><?= $stats['contacts'] ?></span>
                    <?php endif; ?>
                </a>
                <a href="#tab-destinations" class="nav-item" data-tab="tab-destinations">
                    <span class="icon">📍</span>
                    <span>Villes & Lignes directes</span>
                </a>
                <a href="#tab-settings" class="nav-item" data-tab="tab-settings">
                    <span class="icon">⚙️</span>
                    <span>Paramètres & Numéros</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="hub-info">
                    <small>BASE CENTRALE</small>
                    <p>Aigle · Suisse</p>
                    <small>Disponibilité 24h/24, 7j/7</small>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            
            <!-- Toast notification container -->
            <div id="toast" class="toast"></div>

            <!-- Tab 1: Réservations de Taxi -->
            <section id="tab-bookings" class="tab-pane active">
                <div class="section-title-row">
                    <div>
                        <h2>Gestion des Courses & Réservations</h2>
                        <p class="subtitle">Suivi en direct des demandes de taxi, statuts et affectation des chauffeurs</p>
                    </div>
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="openManualBookingModal()">+ Ajouter une course (Appel)</button>
                        <a href="api.php?action=export_csv" class="btn btn-outline">📥 Exporter en CSV</a>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="kpi-grid">
                    <div class="kpi-card" data-filter="all">
                        <div class="kpi-icon orange">🚖</div>
                        <div class="kpi-details">
                            <span class="kpi-value" id="kpi-total"><?= $stats['total'] ?></span>
                            <span class="kpi-label">Total des courses</span>
                        </div>
                    </div>
                    <div class="kpi-card highlight-pending" data-filter="pending">
                        <div class="kpi-icon yellow">⏳</div>
                        <div class="kpi-details">
                            <span class="kpi-value" id="kpi-pending"><?= $stats['pending'] ?></span>
                            <span class="kpi-label">En attente de confirmation</span>
                        </div>
                    </div>
                    <div class="kpi-card" data-filter="confirmed">
                        <div class="kpi-icon green">✅</div>
                        <div class="kpi-details">
                            <span class="kpi-value" id="kpi-confirmed"><?= $stats['confirmed'] ?></span>
                            <span class="kpi-label">Courses confirmées</span>
                        </div>
                    </div>
                    <div class="kpi-card" data-filter="completed">
                        <div class="kpi-icon blue">🏁</div>
                        <div class="kpi-details">
                            <span class="kpi-value" id="kpi-completed"><?= $stats['completed'] ?></span>
                            <span class="kpi-label">Courses terminées</span>
                        </div>
                    </div>
                </div>

                <!-- Table Controls (Filter & Search) -->
                <div class="table-controls">
                    <div class="filter-tabs">
                        <button class="filter-btn active" data-status="all">Toutes (<?= $stats['total'] ?>)</button>
                        <button class="filter-btn" data-status="pending">En attente (<?= $stats['pending'] ?>)</button>
                        <button class="filter-btn" data-status="confirmed">Confirmées (<?= $stats['confirmed'] ?>)</button>
                        <button class="filter-btn" data-status="completed">Terminées (<?= $stats['completed'] ?>)</button>
                        <button class="filter-btn" data-status="cancelled">Annulées (<?= $stats['cancelled'] ?>)</button>
                    </div>
                    <div class="search-box">
                        <input type="text" id="bookingSearch" placeholder="🔍 Rechercher client, destination, téléphone, réf..." autocomplete="off">
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="data-table" id="bookingsTable">
                            <thead>
                                <tr>
                                    <th>Réf / Date</th>
                                    <th>Course (Date & Heure)</th>
                                    <th>Client & Contact</th>
                                    <th>Itinéraire</th>
                                    <th>Véhicule / Pass.</th>
                                    <th>Statut</th>
                                    <th>Chauffeur</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($bookings)): ?>
                                    <tr>
                                        <td colspan="8" class="empty-state">Aucune réservation pour le moment.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($bookings as $b): ?>
                                        <tr data-status="<?= htmlspecialchars($b['status']) ?>" id="row-booking-<?= $b['id'] ?>">
                                            <td>
                                                <strong class="ref-badge"><?= htmlspecialchars($b['ref_code']) ?></strong>
                                                <div class="date-sub"><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></div>
                                            </td>
                                            <td>
                                                <div class="ride-time-badge">
                                                    📅 <?= date('d.m.Y', strtotime($b['ride_date'])) ?>
                                                    <strong>⏰ <?= htmlspecialchars($b['ride_time']) ?></strong>
                                                </div>
                                                <?php if (!empty($b['flight_number'])): ?>
                                                    <span class="flight-tag">✈️ <?= htmlspecialchars($b['flight_number']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="customer-info">
                                                    <strong><?= htmlspecialchars($b['customer_name']) ?></strong>
                                                    <div class="contact-links">
                                                        <a href="tel:<?= htmlspecialchars($b['customer_phone']) ?>" class="tel-link" title="Appeler">📞 <?= htmlspecialchars($b['customer_phone']) ?></a>
                                                        <?php
                                                            $cleanWa = preg_replace('/[^0-9]/', '', $b['customer_phone']);
                                                            $waText = rawurlencode("Bonjour " . $b['customer_name'] . ", nous confirmons votre course MY TAXI (Réf: " . $b['ref_code'] . ") le " . date('d.m.Y', strtotime($b['ride_date'])) . " à " . $b['ride_time'] . ".");
                                                        ?>
                                                        <a href="https://wa.me/<?= $cleanWa ?>?text=<?= $waText ?>" target="_blank" class="wa-direct-btn" title="Contacter sur WhatsApp">💬 WhatsApp</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="route-display">
                                                    <div class="route-from"><span class="route-dot green"></span> <?= htmlspecialchars($b['pickup_location']) ?></div>
                                                    <div class="route-to"><span class="route-dot orange"></span> <?= htmlspecialchars($b['destination']) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="veh-type">
                                                    <?php if ($b['vehicle_type'] === 'van'): ?>
                                                        🚐 Van V-Class
                                                    <?php elseif ($b['vehicle_type'] === 'minibus'): ?>
                                                        🚌 Minibus VIP
                                                    <?php else: ?>
                                                        🚗 Berline E-Class
                                                    <?php endif; ?>
                                                </div>
                                                <small class="muted">👥 <?= $b['passengers'] ?> pers. · 🧳 <?= $b['luggage'] ?> bag.</small>
                                            </td>
                                            <td>
                                                <select class="status-select status-<?= htmlspecialchars($b['status']) ?>" onchange="updateBookingStatus(<?= $b['id'] ?>, this.value)">
                                                    <option value="pending" <?= $b['status'] === 'pending' ? 'selected' : '' ?>>⏳ En attente</option>
                                                    <option value="confirmed" <?= $b['status'] === 'confirmed' ? 'selected' : '' ?>>✅ Confirmée</option>
                                                    <option value="completed" <?= $b['status'] === 'completed' ? 'selected' : '' ?>>🏁 Terminée</option>
                                                    <option value="cancelled" <?= $b['status'] === 'cancelled' ? 'selected' : '' ?>>❌ Annulée</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="driver-input" value="<?= htmlspecialchars($b['driver_assigned'] ?? '') ?>" placeholder="Assigner..." onblur="assignDriver(<?= $b['id'] ?>, this.value)">
                                            </td>
                                            <td style="text-align: right; white-space: nowrap;">
                                                <button class="btn-action view" onclick='viewBookingDetails(<?= json_encode($b) ?>)' title="Voir fiche complète">👁️</button>
                                                <button class="btn-action delete" onclick="deleteBooking(<?= $b['id'] ?>)" title="Supprimer">🗑️</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Tab 2: Excursions sur mesure -->
            <section id="tab-excursions" class="tab-pane">
                <div class="section-title-row">
                    <div>
                        <h2>Demandes d'Excursions & Découverte</h2>
                        <p class="subtitle">Itinéraires touristiques demandés par les clients (Vignobles d'Aigle, Riviera, Alpes Vaudoises)</p>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID / Date</th>
                                    <th>Client</th>
                                    <th>Téléphone</th>
                                    <th>Idée & Destination</th>
                                    <th>Passagers</th>
                                    <th>Date souhaitée</th>
                                    <th>Détails & Souhaits</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($excursions)): ?>
                                    <tr>
                                        <td colspan="8" class="empty-state">Aucune demande d'excursion pour le moment.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($excursions as $ex): ?>
                                        <tr>
                                            <td>#<?= $ex['id'] ?><br><small class="muted"><?= date('d/m/Y', strtotime($ex['created_at'])) ?></small></td>
                                            <td><strong><?= htmlspecialchars($ex['customer_name']) ?></strong></td>
                                            <td>
                                                <a href="tel:<?= htmlspecialchars($ex['customer_phone']) ?>" class="tel-link"><?= htmlspecialchars($ex['customer_phone']) ?></a>
                                            </td>
                                            <td><span class="badge-tag"><?= htmlspecialchars($ex['destination_idea']) ?></span></td>
                                            <td>👥 <?= htmlspecialchars($ex['passengers']) ?> pers.</td>
                                            <td><?= !empty($ex['preferred_date']) ? date('d.m.Y', strtotime($ex['preferred_date'])) : 'À convenir' ?></td>
                                            <td style="max-width: 250px;"><?= nl2br(htmlspecialchars($ex['details'] ?? '')) ?></td>
                                            <td>
                                                <?php
                                                    $cleanWa = preg_replace('/[^0-9]/', '', $ex['customer_phone']);
                                                    $exWa = rawurlencode("Bonjour " . $ex['customer_name'] . ", nous avons bien reçu votre demande d'excursion pour '" . $ex['destination_idea'] . "' et serions ravis d'organiser votre parcours.");
                                                ?>
                                                <a href="https://wa.me/<?= $cleanWa ?>?text=<?= $exWa ?>" target="_blank" class="btn btn-sm btn-outline">💬 WhatsApp</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Tab 3: Messages de contact -->
            <section id="tab-contacts" class="tab-pane">
                <div class="section-title-row">
                    <div>
                        <h2>Messages Reçus depuis le Site</h2>
                        <p class="subtitle">Questions des clients, demandes de devis et partenariats</p>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Expéditeur</th>
                                    <th>Coordonnées</th>
                                    <th>Sujet</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contacts)): ?>
                                    <tr>
                                        <td colspan="6" class="empty-state">Aucun message de contact.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($contacts as $c): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                                            <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                                            <td>
                                                <?php if (!empty($c['phone'])): ?>
                                                    <div>📞 <a href="tel:<?= htmlspecialchars($c['phone']) ?>"><?= htmlspecialchars($c['phone']) ?></a></div>
                                                <?php endif; ?>
                                                <?php if (!empty($c['email'])): ?>
                                                    <div>✉️ <a href="mailto:<?= htmlspecialchars($c['email']) ?>"><?= htmlspecialchars($c['email']) ?></a></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?= htmlspecialchars($c['subject']) ?></strong></td>
                                            <td style="max-width: 320px;"><?= nl2br(htmlspecialchars($c['message'])) ?></td>
                                            <td>
                                                <?php if (!empty($c['email'])): ?>
                                                    <a href="mailto:<?= htmlspecialchars($c['email']) ?>?subject=Re: <?= urlencode($c['subject']) ?>" class="btn btn-sm btn-outline">Répondre</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Tab 4: Villes & Destintations -->
            <section id="tab-destinations" class="tab-pane">
                <div class="section-title-row">
                    <div>
                        <h2>Zones & Villes Desservies par MY TAXI</h2>
                        <p class="subtitle">Destinations clés affichées sur le site et prêtes pour la réservation rapide</p>
                    </div>
                </div>

                <div class="destinations-showcase-grid">
                    <?php
                    $villes = [
                        ['nom' => 'Aigle', 'type' => 'Base Principale & Gare CFF', 'desc' => 'Centre opérationnel, liaisons locales, Château d\'Aigle, vignobles, départs rapides.'],
                        ['nom' => 'Villars-sur-Ollon', 'type' => 'Station de Ski & Montagne', 'desc' => 'Chalets de montagne, hôtels de luxe, événements ski, écoles internationales.'],
                        ['nom' => 'Leysin', 'type' => 'Station Alpine', 'desc' => 'Écoles internationales, cliniques, tourisme alpin, transferts toutes saisons.'],
                        ['nom' => 'Chambéry', 'type' => 'Liaisons Transfrontalières (France)', 'desc' => 'Savoie, gares TGV et transferts internationaux confort.'],
                        ['nom' => 'Châtel France', 'type' => 'Portes du Soleil (France)', 'desc' => 'Accès domaine skiable international, liaisons directes depuis le Chablais.'],
                        ['nom' => 'Les Diablerets', 'type' => 'Glacier 3000 & Alpes Vaudoises', 'desc' => 'Station de ski, Glacier 3000, passerelle suspendue Peak Walk.'],
                        ['nom' => 'Gstaad', 'type' => 'Destination Prestige & Saanenland', 'desc' => 'Gstaad Palace, boutiques de luxe, chalets prestigieux, transferts VIP.'],
                        ['nom' => 'Genève', 'type' => 'Centre d\'Affaires & Diplomatie', 'desc' => 'ONU, banques privées, hôtels 5 étoiles du bord du lac Léman.'],
                        ['nom' => 'GVA (Aéroport de Genève)', 'type' => 'Aéroport International', 'desc' => 'Accueil personnalisé pancarte, suivi des vols en temps réel.'],
                        ['nom' => 'Zurich Aéroport (ZRH)', 'type' => 'Grand Hub International', 'desc' => 'Liaisons directes longue distance en berline et van haute performance.']
                    ];
                    foreach ($villes as $v):
                    ?>
                        <div class="dest-card">
                            <div class="dest-header">
                                <span class="dest-pin">📍</span>
                                <div>
                                    <h3><?= htmlspecialchars($v['nom']) ?></h3>
                                    <span class="dest-badge"><?= htmlspecialchars($v['type']) ?></span>
                                </div>
                            </div>
                            <p class="dest-desc"><?= htmlspecialchars($v['desc']) ?></p>
                            <div class="dest-footer">
                                <span class="status-active">● Service Actif 24/7</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Tab 5: Paramètres & Numéros -->
            <section id="tab-settings" class="tab-pane">
                <div class="section-title-row">
                    <div>
                        <h2>Configuration du Service</h2>
                        <p class="subtitle">Mise à jour des coordonnées et des accès</p>
                    </div>
                </div>

                <div class="settings-grid">
                    <div class="settings-card">
                        <h3>Coordonnées de l'Entreprise</h3>
                        <form id="settingsForm" onsubmit="saveSettings(event)">
                            <div class="form-group">
                                <label>Numéro de téléphone appel</label>
                                <input type="text" name="phone" value="<?= htmlspecialchars($settings['phone_number'] ?? PHONE_NUMBER) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Numéro WhatsApp (avec indicatif pays, sans +)</label>
                                <input type="text" name="whatsapp" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? WHATSAPP_NUMBER) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email de contact</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($settings['email'] ?? CONTACT_EMAIL) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Bandeau d'information haut de page</label>
                                <input type="text" name="notice" value="<?= htmlspecialchars($settings['header_notice'] ?? 'Disponible 24/7 à Aigle, Stations Alpines & Transferts Aéroports') ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer les coordonnées</button>
                        </form>
                    </div>

                    <div class="settings-card">
                        <h3>Sécurité & Mot de passe Admin</h3>
                        <form id="passwordForm" onsubmit="changePassword(event)">
                            <div class="form-group">
                                <label>Mot de passe actuel</label>
                                <input type="password" name="current_password" required placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label>Nouveau mot de passe</label>
                                <input type="password" name="new_password" required minlength="6" placeholder="Au moins 6 caractères">
                            </div>
                            <button type="submit" class="btn btn-outline">Modifier le mot de passe</button>
                        </form>

                        <div class="db-info-box">
                            <h4>Base de Données Active</h4>
                            <p><strong>Moteur :</strong> SQLite PDO (Zéro configuration)</p>
                            <p><strong>Fichier :</strong> <code><?= htmlspecialchars(SQLITE_FILE) ?></code></p>
                            <p><small>Pour exporter sur MySQL en hébergement mutualisé, importez le fichier <code>database/schema.sql</code>.</small></p>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- MODAL : Nouvelle Course Manuelle -->
    <div id="manualModal" class="admin-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>Ajouter une course manuellement (Appel téléphonique)</h3>
                <button type="button" class="modal-close" onclick="closeManualBookingModal()">×</button>
            </div>
            <form id="manualBookingForm" onsubmit="submitManualBooking(event)">
                <div class="modal-body">
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Nom du Client *</label>
                            <input type="text" name="name" required placeholder="Ex: Jean Dupont">
                        </div>
                        <div class="form-group">
                            <label>Téléphone Client *</label>
                            <input type="text" name="phone" required placeholder="+41 79 123 45 67">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Lieu de départ (Prise en charge) *</label>
                            <input type="text" name="pickup" required placeholder="Ex: Gare d'Aigle">
                        </div>
                        <div class="form-group">
                            <label>Destination *</label>
                            <input type="text" name="destination" required placeholder="Ex: Villars-sur-Ollon">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Date de la course *</label>
                            <input type="date" name="date" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label>Heure *</label>
                            <input type="time" name="time" required value="<?= date('H:i', strtotime('+1 hour')) ?>">
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label>Véhicule</label>
                            <select name="vehicle">
                                <option value="sedan">Berline Mercedes E-Class</option>
                                <option value="van">Van Mercedes V-Class (jusqu'à 7 places)</option>
                                <option value="minibus">Minibus VIP (jusqu'à 16 places)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nombre de passagers</label>
                            <input type="number" name="passengers" value="1" min="1" max="16">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Statut initial</label>
                        <select name="status">
                            <option value="confirmed" selected>Confirmée</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Notes / Détails</label>
                        <textarea name="notes" placeholder="Bagages, skis, instructions pour le chauffeur..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" onclick="closeManualBookingModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer la course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL : Détail Réservation & Dispatch -->
    <div id="detailsModal" class="admin-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="detailTitle">Fiche de Course</h3>
                <button type="button" class="modal-close" onclick="closeDetailsModal()">×</button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Rempli en Javascript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="window.print()">🖨️ Imprimer bon de course</button>
                <button type="button" class="btn btn-primary" onclick="closeDetailsModal()">Fermer</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>
