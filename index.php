<?php
/**
 * Page d'Accueil Principale - MY TAXI
 * Taxi officiel à Aigle, Stations Alpines & Transferts Aéroports
 */
require_once __DIR__ . '/config.php';

// Récupérer les paramètres du site
$pdo = getDb();
$settingsStmt = $pdo->query("SELECT * FROM settings");
$settings = [];
while ($row = $settingsStmt->fetch()) {
    $settings[$row['key_name']] = $row['value'];
}

$phoneDisplay = $settings['phone_display'] ?? PHONE_DISPLAY;
$phoneClean = $settings['phone_number'] ?? PHONE_NUMBER;
$whatsapp = $settings['whatsapp_number'] ?? WHATSAPP_NUMBER;
$headerNotice = $settings['header_notice'] ?? 'Disponible 24/7 à Aigle, Stations Alpines & Transferts Aéroports';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>MY TAXI | Taxi à Aigle & Région · Disponible 24/7 · Transferts Aéroports</title>
    <meta name="description" content="MY TAXI à Aigle et alentours 24h/24, 7j/7. Trajets quotidiens, stations de ski (Villars, Leysin, Les Diablerets, Châtel, Gstaad) et aéroports (Genève GVA, Zurich ZRH, Chambéry). Réservez en ligne ou au 079 845 62 70.">
    <meta name="keywords" content="taxi aigle, taxi villars, taxi leysin, taxi gva geneve, taxi zrh zurich, taxi les diablerets, taxi gstaad, taxi chatel, taxi chambery, chauffeur chablais">
    <meta name="author" content="MY TAXI">
    <meta name="theme-color" content="#0E0F13">
    
    <!-- Open Graph / Réseaux Sociaux -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="MY TAXI | Taxi à Aigle & Stations de Montagne · 24/7">
    <meta property="og:description" content="Votre chauffeur privé à Aigle, Villars, Leysin, Châtel, Diablerets, Gstaad et transferts aéroports Genève GVA et Zurich.">
    <meta property="og:image" content="assets/logo.png">

    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="icon" type="image/png" href="assets/logo.png">

    <!-- Schema.org TaxiService JSON°-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TaxiService",
      "name": "MY TAXI",
      "image": "assets/logo.png",
      "telephone": "+41798456270",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Aigle",
        "postalCode": "1860",
        "addressCountry": "CH"
      },
      "areaServed": [
        "Aigle", "Villars-sur-Ollon", "Leysin", "Chambéry",
        "Châtel", "Les Diablerets", "Gstaad", "Genève", "GVA", "Zurich Airport"
      ],
      "openingHours": "Mo-Su 00:00-24:00"
    }
    </script>
</head>
<body>

   

    <!-- N°avigation principale (Inspirée de la maquette de référence avec sélecteur de langue et actions directes) -->
    <header class="site-header" id="accueil">
        <div class="nav-container">
            <a href="admin/login.php" class="brand-wrapper" aria-label="Espace Administrateur - MY TAXI" title="Connexion Administration">
                <img src="assets/logo-white.png" alt="MY TAXI" class="brand-logo-img">
            </a>

            <nav>
                <ul class="nav-menu">
                    <li class="mobile-lang-item">
                        <div class="mobile-lang-picker" aria-label="Langue">
                            <button type="button" class="lang-choice active" data-lang="FR">FR</button>
                            <button type="button" class="lang-choice" data-lang="EN">EN</button>
                            <button type="button" class="lang-choice" data-lang="RU">RU</button>
                        </div>
                    </li>
                    <li><a href="#qui-sommes-nous" data-i18n="nav_about">Qui sommes-nous</a></li>
                    <li><a href="#services" data-i18n="nav_services">Services</a></li>
                    <li><a href="#villes" data-i18n="nav_destinations">Destinations</a></li>
                    <li><a href="#vehicules" data-i18n="nav_fleet">Notre Flotte</a></li>
                    <li><a href="#excursions" data-i18n="nav_excursions">Excursions</a></li>
                    <li><a href="#contact" data-i18n="nav_contact">Contact</a></li>
                </ul>
            </nav>

            <!-- Actions du Header -->
            <div class="header-cta-group">
                <!-- Sélecteur de langue fidèle à la référence (FR / EN / RU) -->
                <div class="header-lang-picker" aria-label="Langue">
                    <button type="button" class="lang-choice active" data-lang="FR">FR</button>
                    <button type="button" class="lang-choice" data-lang="EN">EN</button>
                    <button type="button" class="lang-choice" data-lang="RU">RU</button>
                </div>

                <!-- Bouton Appel Rapide (Cercle Orange avec icône téléphone SVG fine) -->
                <a href="tel:<?= $phoneClean ?>" class="header-icon-btn" title="Appeler le <?= htmlspecialchars($phoneDisplay) ?>" aria-label="Appeler">
                    <svg class="icon-phone" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </a>

                <!-- Bouton Réserver (Pilule Orange avec texte noir/foncé comme dans la référence) -->
                <a href="#reserver" class="btn btn-header-reserve">
                    <span data-i18n="nav_reserve">Réserver</span>
                </a>

                <button type="button" class="mobile-nav-toggle" aria-label="Ouvrir le menu">☰</button>
            </div>
        </div>
    </header>

    <main>
        <!-- Section Hero Plein Écran (Style Grand Format Inspiré de la Maquette de Référence) -->
        <section class="hero-fullscreen-section">
            <div class="hero-bg-overlay"></div>
            <div class="hero-content-wrap">
                <div class="hero-main-column">
                    <!-- Badge Chauffeur disponible en direct -->
                    <div class="hero-status-pill">
                        <span class="live-indicator"></span>
                        <span data-i18n="hero_status">Un chauffeur disponible maintenant · 24h/24</span>
                    </div>

                    <!-- Titre principal imposant et percutant -->
                    <h1 class="hero-giant-title">
                        <span data-i18n="hero_title_1">Votre taxi à</span><br>
                        <span data-i18n="hero_title_2">Aigle,</span><br>
                        <span class="highlight" data-i18n="hero_title_3">partout en Suisse.</span>
                    </h1>

                    <!-- Sous-titre descriptif -->
                    <p class="hero-description-text" data-i18n="hero_desc">
                        Transferts privés vers les aéroports, les stations de ski et toutes les destinations de Suisse. Des chauffeurs expérimentés, des véhicules haut de gamme, une réponse rapide.
                    </p>

                    <!-- Ligne des 3 Boutons d'Action Principaux avec Icônes Vectorielles Raffinées -->
                    <div class="hero-actions-row">
                        <a href="tel:<?= $phoneClean ?>" class="btn-hero-primary">
                            <svg class="icon-phone" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span data-i18n="hero_btn_call">Appeler maintenant</span>
                        </a>

                        <a href="https://wa.me/<?= $whatsapp ?>?text=Bonjour%20MY%20TAXI,%20je%20souhaite%20r%C3%A9server%20une%20course." target="_blank" rel="noopener" class="btn-hero-whatsapp">
                            <svg class="icon-whatsapp" viewBox="0 0 24 24" width="19" height="19" fill="currentColor" aria-hidden="true">
                                <path d="M20.52 3.48A11.93 11.93 0 0 0 12.07 0C5.46 0 .09 5.37.09 11.98c0 2.11.55 4.17 1.6 5.99L0 24l6.21-1.63a11.96 11.96 0 0 0 5.86 1.52h.01c6.61 0 11.98-5.37 11.98-11.98 0-3.2-.1.25-1.24-6.22-3.43zM12.07 21.9a9.92 9.92 0 0 1-5.06-1.39l-.36-.22-3.76.99 1-3.66-.24-.38a9.92 9.92 0 0 1-1.52-5.26c0-5.49 4.47-9.96 9.97-9.96 2.66 0 5.16 1.04 7.04 2.92a9.92 9.92 0 0 1 2.91 7.04c0 5.49-4.47 9.96-9.97 9.96zm5.46-7.46c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.89-.8-1.5-1.78-1.67-2.08-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/>
                            </svg>
                            <span data-i18n="hero_btn_whatsapp">WhatsApp</span>
                        </a>

                        <a href="#reserver" class="btn-hero-glass">
                            <svg class="icon-calendar" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span data-i18n="hero_btn_book">Réserver en ligne</span>
                        </a>
                    </div>

                    <!-- Barre de Réassurance / Statistiques Clés (Avis, Disponibilité, Langues) -->
                    <div class="hero-trust-bar">
                        <div class="trust-metric-box">
                            <div class="metric-score-row">
                                <span class="metric-big-num">5,0</span>
                                <span class="metric-stars">★★★★★</span>
                            </div>
                            <span class="metric-caption" data-i18n="trust_reviews">88 avis Google</span>
                        </div>

                        <div class="trust-divider"></div>

                        <div class="trust-metric-box">
                            <span class="metric-big-num">24h/24</span>
                            <span class="metric-caption" data-i18n="trust_247">7 jours sur 7</span>
                        </div>

                        <div class="trust-divider"></div>

                        <div class="trust-metric-box">
                            <span class="metric-big-num">5</span>
                            <span class="metric-caption" data-i18n="trust_langs">parlées par nos chauffeurs</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Réservation Dédiée (Accessible en 1 clic via 'Réserver en ligne') -->
        <section class="section booking-standalone-section" id="reserver">
            <div class="section-container">
                <div class="booking-standalone-grid">
                    <!-- Colonne Gauche : Formulaire de Réservation -->
                    <div class="booking-form-col">
                        <div class="hero-booking-card">
                            <div class="booking-header">
                                <span class="booking-kicker" data-i18n="book_kicker">RÉSERVATION RAPIDE 24/7</span>
                                <h2 data-i18n="book_title">Où pouvons-nous vous conduire ?</h2>
                                <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;" data-i18n="book_desc">
                                    Indiquez votre itinéraire pour une prise en charge immédiate ou planifiée.
                                </p>
                            </div>

                            <!-- Villes en accès direct -->
                            <div class="hero-pills-label" data-i18n="book_pills_label">Destinations fréquentes (Cliquez pour sélectionner) :</div>
                            <div class="hero-destination-pills">
                                <button type="button" class="dest-pill" data-dest="Aigle Gare CFF"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> Aigle</button>
                                <button type="button" class="dest-pill" data-dest="Villars-sur-Ollon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l4-8 4 4 3-6 4 10"/><path d="M3 17h18"/></svg> Villars-sur-Ollon</button>
                                <button type="button" class="dest-pill" data-dest="Leysin"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l4-8 4 4 3-6 4 10"/><path d="M3 17h18"/></svg> Leysin</button>
                                <button type="button" class="dest-pill" data-dest="Les Diablerets"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12l5-8 5 8"/><path d="M12 12l5-8 5 8"/><line x1="2" y1="12" x2="22" y2="12"/></svg> Les Diablerets</button>
                                <button type="button" class="dest-pill" data-dest="Gstaad"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Gstaad</button>
                                <button type="button" class="dest-pill" data-dest="Genève Aéroport (GVA)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg> GVA (Genève)</button>
                                <button type="button" class="dest-pill" data-dest="Zurich Aéroport (ZRH)"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg> Zurich Aéroport</button>
                                <button type="button" class="dest-pill" data-dest="Châtel France"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg> Châtel France</button>
                                <button type="button" class="dest-pill" data-dest="Chambéry France"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg> Chambéry</button>
                            </div>

                            <form id="bookingForm" autocomplete="on">
                                <div class="form-group">
                                    <label for="pickupInput" data-i18n="book_pickup_label">Lieu de prise en charge *</label>
                                    <input type="text" id="pickupInput" name="pickup" class="form-input" placeholder="Adresse, gare, hôtel, aéroport…" data-i18n-ph="book_pickup_ph" required maxlength="200" value="Aigle">
                                </div>

                                <div class="form-group">
                                    <label for="destinationInput" data-i18n="book_dest_label">Destination *</label>
                                    <input type="text" id="destinationInput" name="destination" class="form-input" placeholder="Où souhaitez-vous aller ?" data-i18n-ph="book_dest_ph" required maxlength="200">
                                </div>

                                <div class="form-row-2">
                                    <div class="form-group">
                                        <label for="rideDate" data-i18n="book_date_label">Date de la course *</label>
                                        <input type="date" id="rideDate" name="date" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="rideTime" data-i18n="book_time_label">Heure de départ *</label>
                                        <input type="time" id="rideTime" name="time" class="form-input" required value="12:00">
                                    </div>
                                </div>

                                <div class="form-row-2">
                                    <div class="form-group">
                                        <label for="vehicleSelect" data-i18n="book_vehicle_label">Catégorie de véhicule</label>
                                        <select id="vehicleSelect" name="vehicle" class="form-select">
                                            <option value="van" data-i18n="opt_vehicle_van">Mercedes-Benz Classe V · Jusqu'à 7 pers.</option>
                                            <option value="sedan" data-i18n="opt_vehicle_sedan">Mercedes-Benz Classe E · 1 à 4 pers.</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="passengerCount" data-i18n="book_passengers_label">Passagers & Bagages</label>
                                        <div class="form-row-2">
                                            <select id="passengerCount" name="passengers" class="form-select">
                                                <option value="1">1 passager</option>
                                                <option value="2">2 passagers</option>
                                                <option value="3">3 passagers</option>
                                                <option value="4">4 passagers</option>
                                                <option value="5">5 passagers</option>
                                                <option value="6">6 passagers</option>
                                                <option value="7">7 passagers</option>
                                                <option value="8">8+ groupe</option>
                                            </select>
                                            <select name="luggage" class="form-select">
                                                <option value="1">1 bagage</option>
                                                <option value="2">2 bagages</option>
                                                <option value="3">3 bagages</option>
                                                <option value="4">4+ bagages</option>
                                                <option value="5">Skis / Équipement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row-2">
                                    <div class="form-group">
                                        <label for="customerName" data-i18n="book_name_label">Votre Nom complet *</label>
                                        <input type="text" id="customerName" name="name" class="form-input" placeholder="Ex: Jean Martin" data-i18n-ph="book_name_ph" required minlength="2" maxlength="100" autocomplete="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPhone" data-i18n="book_phone_label">Numéro de téléphone *</label>
                                        <input type="tel" id="customerPhone" name="phone" class="form-input" placeholder="+41 79 123 45 67" data-i18n-ph="book_phone_ph" required minlength="6" autocomplete="tel">
                                    </div>
                                </div>

                                <div class="form-row-2">
                                    <div class="form-group">
                                        <label for="customerEmail" data-i18n="book_email_label">Adresse email (pour la confirmation)</label>
                                        <input type="email" id="customerEmail" name="email" class="form-input" placeholder="votre.email@exemple.com" data-i18n-ph="book_email_ph" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" autocomplete="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="rideNotes" data-i18n="book_notes_label">N° de vol / Remarques (optionnel)</label>
                                        <input type="text" id="rideNotes" name="notes" class="form-input" placeholder="Ex: Vol LX 2812, siège bébé, skis..." data-i18n-ph="book_notes_ph">
                                    </div>
                                </div>

                                <div class="booking-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                                        <span data-i18n="book_btn_submit">Confirmer ma demande de course</span>
                                    </button>
                                    <button type="button" id="btnSubmitWhatsApp" class="btn btn-whatsapp">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.93 11.93 0 0012.07 0C5.46 0 .09 5.37.09 11.98c0 2.11.55 4.17 1.6 5.99L0 24l6.21-1.63a11.96 11.96 0 005.86 1.52h.01c6.61 0 11.98-5.37 11.98-11.98 0-3.2-1.24-6.22-3.54-8.43zm-8.45 18.42a9.92 9.92 0 01-5.06-1.39l-.36-.22-3.76.99 1-3.66-.24-.38a9.92 9.92 0 01-1.52-5.26c0-5.49 4.47-9.96 9.97-9.96 2.66 0 5.16 1.04 7.04 2.92a9.92 9.92 0 012.91 7.04c0 5.49-4.47 9.96-9.98 9.92zm5.46-7.46c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.89-.8-1.5-1.78-1.67-2.08-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/></svg>
                                        <span data-i18n="book_btn_wa">Réserver via WhatsApp</span>
                                    </button>
                                </div>

                                <p class="booking-disclaimer" data-i18n="book_disclaimer">
                                    Votre demande est immédiatement transmise à notre centrale. Confirmation rapide par WhatsApp ou appel.
                                </p>
                            </form>
                        </div>
                    </div>

                    <!-- Colonne Droite : Reassurance & Avantages Chauffeur Privé -->
                    <div class="booking-perks-col">
                        <div class="booking-perk-card">
                            <span class="perk-badge" data-i18n="perks_badge">SERVICE OFFICIEL SUISSE</span>
                            <h3 data-i18n="perks_title">Pourquoi choisir MY TAXI ?</h3>
                            <ul class="perk-list">
                                <li>
                                    <div class="perk-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                    <div>
                                        <strong data-i18n="perk_1_title">Ponctualité garantie</strong>
                                        <p data-i18n="perk_1_desc">Votre chauffeur se présente 5 minutes à l'avance à votre adresse ou hall d'arrivée.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="perk-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    </div>
                                    <div>
                                        <strong data-i18n="perk_2_title">Chauffeurs professionnels certifiés</strong>
                                        <p data-i18n="perk_2_desc">Conduite souple et sécurisée sur route alpine, discrétion assurée.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="perk-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l4-8 4 4 3-6 4 10"/><path d="M3 17h18"/></svg>
                                    </div>
                                    <div>
                                        <strong data-i18n="perk_3_title">Spécialistes stations alpines</strong>
                                        <p data-i18n="perk_3_desc">Véhicules équipés neige vers Villars, Leysin, Les Diablerets, Gstaad et Châtel.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="perk-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    </div>
                                    <div>
                                        <strong data-i18n="perk_4_title">Paiement flexible à bord</strong>
                                        <p data-i18n="perk_4_desc">Cartes bancaires, Visa, Mastercard, American Express, Twint ou espèces.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="perk-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                                    </div>
                                    <div>
                                        <strong data-i18n="perk_5_title">Accueil aéroport personnalisé</strong>
                                        <p data-i18n="perk_5_desc">Prise en charge avec pancarte nominative à Genève (GVA) et Zurich (ZRH).</p>
                                    </div>
                                </li>
                            </ul>

                            <div class="booking-help-box">
                                <p data-i18n="perk_call_prompt">Une question ou un départ immédiat ?</p>
                                <a href="tel:<?= $phoneClean ?>" class="booking-direct-call">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                    <strong><?= htmlspecialchars($phoneDisplay) ?></strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bandeau de réassurance -->
        <section class="feature-strip">
            <div class="strip-container">
                <div class="strip-item">
                    <div class="strip-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <strong data-i18n="strip_247_t">Disponible 24/7</strong>
                        <span data-i18n="strip_247_d">Jour et nuit, 365 jours par an</span>
                    </div>
                </div>
                <div class="strip-item">
                    <div class="strip-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <strong data-i18n="strip_dist_t">Aigle & Toutes distances</strong>
                        <span data-i18n="strip_dist_d">Chablais, stations et aéroports</span>
                    </div>
                </div>
                <div class="strip-item">
                    <div class="strip-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <strong data-i18n="strip_fleet_t">Flotte Mercedes Moderne</strong>
                        <span data-i18n="strip_fleet_d">Berlines, Vans 7 places & Minibus</span>
                    </div>
                </div>
                <div class="strip-item">
                    <div class="strip-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    </div>
                    <div>
                        <strong data-i18n="strip_contact_t">Contact Direct Chauffeur</strong>
                        <span data-i18n="strip_contact_d">Appel immédiat ou WhatsApp</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Qui sommes-nous / Présentation de My Taxi SARL -->
        <section class="section about-section" id="qui-sommes-nous">
            <div class="section-container">
                <div class="about-grid">
                    <!-- Colonne Visuelle avec photo du Mercedes V-Class et badge flottant officiel -->
                    <div class="about-visual-col">
                        <div class="about-visual-card">
                            <img src="assets/hero-mercedes.webp" alt="My Taxi SARL - Véhicules Mercedes avec chauffeur à Aigle" class="about-image">

                            <!-- Badge flottant officiel avec adresse Avenue des Alpes 28 -->
                            <div class="about-floating-badge">
                                <div class="badge-icon-box" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11.1 2 11.5 2 12v4c0 .6.4 1 1 1h2"/>
                                        <circle cx="7" cy="17" r="2"/>
                                        <path d="M9 17h6"/>
                                        <circle cx="17" cy="17" r="2"/>
                                    </svg>
                                </div>
                                <div class="badge-text">
                                    <strong class="badge-company" data-i18n="about_badge_company">My Taxi SARL</strong>
                                    <span class="badge-address" data-i18n="about_badge_addr">Entreprise locale · Avenue des Alpes 28, 1860 Aigle</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne Contenu et caractéristiques -->
                    <div class="about-content-col">
                        <span class="about-kicker" data-i18n="about_kicker">QUI VOUS CONDUIT</span>
                        <h2 class="about-title" data-i18n="about_title">Une équipe d'ici, que vous pouvez appeler.</h2>

                        <div class="about-text-body">
                            <p data-i18n="about_p1">
                                My Taxi SARL est une entreprise basée à Aigle spécialisée dans l'exploitation d'entreprises de taxis. Ses services incluent le transport de personnes, le déménagement de marchandises, ainsi que la location de véhicules avec chauffeur.
                            </p>
                            <p data-i18n="about_p2">
                                L'entreprise est décrite comme ponctuelle et serviable, disposant d'une forte adresse Avenue des Alpes 28, 1860 Aigle. Quand vous appelez, vous parlez directement à nos chauffeurs, pas à une plateforme ni à un centre d'appel.
                            </p>
                        </div>

                        <div class="about-features-list">
                            <div class="about-feature-item">
                                <div class="feature-check-circle" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div class="feature-body">
                                    <strong data-i18n="about_f1_t">Toujours ponctuels</strong>
                                    <p data-i18n="about_f1_d">Pour un avion, nous prévoyons toujours de la marge.</p>
                                </div>
                            </div>

                            <div class="about-feature-item">
                                <div class="feature-check-circle" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div class="feature-body">
                                    <strong data-i18n="about_f2_t">Chauffeurs expérimentés</strong>
                                    <p data-i18n="about_f2_d">De nombreuses années sur les routes de montagne, été comme hiver.</p>
                                </div>
                            </div>

                            <div class="about-feature-item">
                                <div class="feature-check-circle" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div class="feature-body">
                                    <strong data-i18n="about_f3_t">Prix annoncé avant le départ</strong>
                                    <p data-i18n="about_f3_d">Vous connaissez le tarif avant de monter.</p>
                                </div>
                            </div>

                            <div class="about-feature-item">
                                <div class="feature-check-circle" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <div class="feature-body">
                                    <strong data-i18n="about_f4_t">Véhicules propres et confortables</strong>
                                    <p data-i18n="about_f4_d">Berline, van V-Class ou véhicule VIP.</p>
                                </div>
                            </div>
                        </div>

                        <div class="about-cta-row">
                            <a href="tel:<?= $phoneClean ?>" class="btn-about-call" aria-label="Appeler My Taxi au <?= htmlspecialchars($phoneDisplay) ?>">
                                <span><span data-i18n="about_call_btn">Nous appeler : </span><?= htmlspecialchars($phoneDisplay) ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Ligne des 4 Statistiques Clés (Identique à la maquette) -->
                <div class="about-stats-strip">
                    <div class="about-stat-box">
                        <span class="about-stat-num">88</span>
                        <span class="about-stat-label" data-i18n="about_stat_1">avis Google à 5 étoiles</span>
                    </div>
                    <div class="about-stat-box">
                        <span class="about-stat-num">24/7</span>
                        <span class="about-stat-label" data-i18n="about_stat_2">disponibles jour et nuit</span>
                    </div>
                    <div class="about-stat-box">
                        <span class="about-stat-num">5</span>
                        <span class="about-stat-label" data-i18n="about_stat_3">langues parlées</span>
                    </div>
                    <div class="about-stat-box">
                        <span class="about-stat-num">11</span>
                        <span class="about-stat-label" data-i18n="about_stat_4">destinations régulières</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Villes Desservies Spécifiées -->
        <section class="section destinations-section" id="villes">
            <div class="section-container">
                <div class="section-heading-row">
                    <div>
                        <span class="section-overline" data-i18n="dest_overline">RÉGION & LIAISONS DIRECTES</span>
                        <h2 class="section-title" data-i18n="dest_title">Nos Villes & Destinations Clés</h2>
                    </div>
                    <p class="section-desc" data-i18n="dest_desc">
                        Basés à Aigle, nous assurons l'ensemble de vos déplacements en Suisse romande, vers les stations de ski alpines et les grands aéroports internationaux.
                    </p>
                </div>

                <div class="cities-grid">
                    <!-- 1. Aigle -->
                    <div class="city-card" data-dest="Aigle (Gare CFF / Centre)">
                        <div>
                            <span class="city-badge-hub">Base Principale</span>
                            <h3>Aigle</h3>
                            <p>Centre névralgique, Gare CFF, Château d'Aigle, vignobles du Chablais et liaisons quotidiennes.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 2. Villars-sur-Ollon -->
                    <div class="city-card" data-dest="Villars-sur-Ollon">
                        <div>
                            <span class="city-badge-hub">Station de Ski</span>
                            <h3>Villars-sur-Ollon</h3>
                            <p>Hôtels de luxe, chalets privés, événements d'hiver, écoles internationales (Beau Soleil, Aiglon).</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 3. Leysin -->
                    <div class="city-card" data-dest="Leysin">
                        <div>
                            <span class="city-badge-hub">Station Alpine</span>
                            <h3>Leysin</h3>
                            <p>Station ensoleillée, universités et écoles internationales (LAS, SHMS), randonnées et ski.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 4. Les Diablerets -->
                    <div class="city-card" data-dest="Les Diablerets">
                        <div>
                            <span class="city-badge-hub">Glacier 3000</span>
                            <h3>Les Diablerets</h3>
                            <p>Le village des Diablerets, accès à Glacier 3000, Peak Walk by Tissot et pistes alpines.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 5. Gstaad -->
                    <div class="city-card" data-dest="Gstaad">
                        <div>
                            <span class="city-badge-hub">Prestige & Luxe</span>
                            <h3>Gstaad</h3>
                            <p>Saanenland, Gstaad Palace, boutiques haut de gamme, chalets prestigieux et clientèle VIP.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 6. Genève -->
                    <div class="city-card" data-dest="Genève Centre">
                        <div>
                            <span class="city-badge-hub">Affaires & Diplomatie</span>
                            <h3>Genève</h3>
                            <p>Centre d'affaires, institutions internationales (ONU), quais du lac Léman et hôtels 5 étoiles.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 7. GVA (Genève Aéroport) -->
                    <div class="city-card" data-dest="Genève Aéroport (GVA)">
                        <div>
                            <span class="city-badge-hub">Hub International</span>
                            <h3>GVA (Genève Aéroport)</h3>
                            <p>Transfert ponctuel aéroport, accueil pancarte aux arrivées, suivi de vol et prise en charge des bagages.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 8. Zurich Aéroport -->
                    <div class="city-card" data-dest="Zurich Aéroport (ZRH)">
                        <div>
                            <span class="city-badge-hub">Liaison Longue Distance</span>
                            <h3>Zurich Aéroport</h3>
                            <p>Liaison directe depuis le Chablais et les Alpes vers le premier aéroport de Suisse (ZRH).</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 9. Châtel France -->
                    <div class="city-card" data-dest="Châtel France (Portes du Soleil)">
                        <div>
                            <span class="city-badge-hub">France Voisine</span>
                            <h3>Châtel France</h3>
                            <p>Au cœur des Portes du Soleil, transferts transfrontaliers directs pour skieurs et vacanciers.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>

                    <!-- 10. Chambéry -->
                    <div class="city-card" data-dest="Chambéry France">
                        <div>
                            <span class="city-badge-hub">Savoie France</span>
                            <h3>Chambéry</h3>
                            <p>Liaison transfrontalière Savoie, gare TGV et accès aux grandes vallées alpines françaises.</p>
                        </div>
                        <span class="city-action" data-i18n="dest_btn_choose">Choisir cette destination ↗</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Services -->
        <section class="section" id="services">
            <div class="section-container">
                <div class="section-heading-row">
                    <div>
                        <span class="section-overline" data-i18n="serv_overline">CHAQUE TRAJET, LE MÊME SOIN</span>
                        <h2 class="section-title" data-i18n="serv_title">La liberté d'aller où vous voulez.</h2>
                    </div>
                    <p class="section-desc" data-i18n="serv_desc">
                        Un rendez-vous médical, un départ en vacances ou un retour de soirée tardif : contactez-nous pour organiser votre trajet à toute heure.
                    </p>
                </div>

                <div class="services-grid">
                    <article class="service-card">
                        <span class="service-num">01</span>
                        <div class="service-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <h3 data-i18n="serv_1_t">Trajets du quotidien</h3>
                        <p data-i18n="serv_1_d">Pour vous déplacer facilement à Aigle et dans tout le Chablais : rendez-vous, shopping, courses privées et soirées.</p>
                        <a href="#reserver" class="service-link" data-i18n="serv_1_btn">Planifier une course ↗</a>
                    </article>

                    <article class="service-card">
                        <span class="service-num">02</span>
                        <div class="service-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <h3 data-i18n="serv_2_t">Gares & Aéroports</h3>
                        <p data-i18n="serv_2_d">Commencez ou terminez votre voyage en toute sérénité. Navettes directes vers Genève (GVA), Zurich (ZRH) et gares CFF.</p>
                        <a href="#reserver" class="service-link" data-i18n="serv_2_btn">Planifier un transfert ↗</a>
                    </article>

                    <article class="service-card">
                        <span class="service-num">03</span>
                        <div class="service-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l4-8 4 4 3-6 4 10"/><path d="M3 17h18"/></svg>
                        </div>
                        <h3 data-i18n="serv_3_t">Stations & Hautes Montagnes</h3>
                        <p data-i18n="serv_3_d">Véhicules équipés pour les conditions hivernales vers Villars, Leysin, Les Diablerets, Châtel et Gstaad.</p>
                        <a href="#reserver" class="service-link" data-i18n="serv_3_btn">Rejoindre la station ↗</a>
                    </article>

                    <article class="service-card">
                        <span class="service-num">04</span>
                        <div class="service-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                        </div>
                        <h3 data-i18n="serv_4_t">Excursions sur mesure</h3>
                        <p data-i18n="serv_4_d">Envie de découvrir le vignoble vaudois ou le lac Léman ? Choisissez votre itinéraire et partons ensemble.</p>
                        <a href="#excursions" class="service-link" data-i18n="serv_4_btn">Voir les excursions ↗</a>
                    </article>
                </div>
            </div>
        </section>

        <!-- Section Flotte de Véhicules -->
        <section class="section fleet-section" id="vehicules">
            <div class="section-container">
                <div class="section-heading-row">
                    <div>
                        <span class="section-overline" data-i18n="fleet_overline">CONFORT & SÉCURITÉ EN MONTAGNE</span>
                        <h2 class="section-title" data-i18n="fleet_title">À chaque trajet, le bon véhicule.</h2>
                    </div>
                    <p class="section-desc" data-i18n="fleet_desc">
                        Seul, en famille ou en groupe d'amis ? Découvrez notre flotte de véhicules récents Mercedes-Benz parfaitement entretenus.
                    </p>
                </div>

                <!-- Onglets Flotte -->
                <div class="fleet-tabs">
                    <button type="button" class="fleet-tab-btn active" data-panel="panel-van" data-i18n="fleet_tab_1">
                        01 · Mercedes-Benz Classe V
                    </button>
                    <button type="button" class="fleet-tab-btn" data-panel="panel-sedan" data-i18n="fleet_tab_2">
                        02 · Mercedes-Benz Classe E
                    </button>
                   
                </div>

                <!-- Panneau 1 : Van Mercedes -->
                <div class="fleet-panel active" id="panel-van">
                    <div class="fleet-panel-visual" data-lightbox-photo="assets/mercedes-v-class.jpg" data-lightbox-caption="Van Mercedes-Benz Classe V">
                        <img src="assets/mercedes-v-class.jpg" alt="Van Mercedes-Benz Classe V noir" loading="lazy">
                        <span class="fleet-zoom-badge">⌕ Agrandir la photo</span>
                    </div>
                    <div class="fleet-panel-copy">
                        <span class="fleet-panel-cat">N°OTRE SÉLECTION° / 01</span>
                        <h3>Mercedes-Benz Classe V</h3>
                        <div class="fleet-panel-heading">Voyager ensemble, en toute simplicité.</div>
                        <p>Idéal pour les familles, les transferts avec skis et valises volumineuses ou les petits groupes jusqu'à 7 passagers.</p>
                        <div class="fleet-tags">
                            <span class="fleet-tag">Familles & Petits groupes</span>
                            <span class="fleet-tag">Gares & Aéroports</span>
                            <span class="fleet-tag">Grand coffre à bagages</span>
                        </div>
                        <a href="#reserver" class="btn btn-primary">Réserver ce véhicule <span>↗</span></a>
                    </div>
                </div>

                <!-- Panneau 2 : Berline Mercedes -->
                <div class="fleet-panel" id="panel-sedan">
                    <div class="fleet-panel-visual" data-lightbox-photo="assets/mercedes-e-class.jpg" data-lightbox-caption="Mercedes-Benz Classe E">
                        <img src="assets/mercedes-e-class.jpg" alt="Mercedes-Benz Classe E noire" loading="lazy">
                        <span class="fleet-zoom-badge">⌕ Agrandir la photo</span>
                    </div>
                    <div class="fleet-panel-copy">
                        <span class="fleet-panel-cat">N°OTRE SÉLECTION° / 02</span>
                        <h3>Mercedes-Benz Classe E</h3>
                        <div class="fleet-panel-heading">Votre destination, à votre rythme.</div>
                        <p>Pour vos rendez-vous d'affaires, déplacements discrets ou trajets rapides de 1 à 4 personnes dans un confort absolu.</p>
                        <div class="fleet-tags">
                            <span class="fleet-tag">Trajets quotidiens</span>
                            <span class="fleet-tag">Voyages d'affaires</span>
                            <span class="fleet-tag">Confort feutré</span>
                        </div>
                        <a href="#reserver" class="btn btn-primary">Réserver ce véhicule <span>↗</span></a>
                    </div>
                </div>

                

                <!-- Galerie Photo complète des 10 véhicules -->
                <div class="gallery-toggle-bar">
                    <h3 style="font-size: 18px; margin-bottom: 6px;">Galerie Complète de la Flotte</h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Cliquez sur une image pour la visualiser en grand format :</p>
                    <div class="fleet-gallery-grid">
                        <div class="gallery-thumb" data-lightbox-photo="assets/mercedes-v-class.jpg" data-lightbox-caption="Mercedes-Benz Classe V vue panoramique sur les Alpes">
                            <img src="assets/mercedes-v-class.jpg" alt="Mercedes-Benz Classe V" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/mercedes-e-class.jpg" data-lightbox-caption="Mercedes-Benz Classe E Break">
                            <img src="assets/mercedes-e-class.jpg" alt="Mercedes-Benz Classe E" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/gallery-01.jpg" data-lightbox-caption="Mercedes-Benz Classe V Taxi officiel">
                            <img src="assets/gallery-01.jpg" alt="Mercedes-Benz Classe V Taxi" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/gallery-02.jpg" data-lightbox-caption="Intérieur cuir VIP et espace passagers">
                            <img src="assets/gallery-02.jpg" alt="Intérieur cuir VIP Classe V" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/gallery-03.jpg" data-lightbox-caption="Service VIP devant le Palace">
                            <img src="assets/gallery-03.jpg" alt="Mercedes devant le Palace" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/gallery-04.jpg" data-lightbox-caption="Nos véhicules en station d'hiver sur route enneigée">
                            <img src="assets/gallery-04.jpg" alt="Flotte de nuit sous la neige" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/gallery-05.jpg" data-lightbox-caption="Arrivée au Villars Palace">
                            <img src="assets/gallery-05.jpg" alt="Mercedes Classe E au Villars Palace" loading="lazy">
                        </div>
                        <div class="gallery-thumb" data-lightbox-photo="assets/wifi.jpeg" data-lightbox-caption="Wifi Gratuit">
                            <img src="assets/wifi.jpeg" loading="lazy" alt="Wifi Gratuit">
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

        <!-- Section Excursions sur mesure -->
        <section class="section excursions-section" id="excursions">
            <div class="section-container">
                <div class="excursion-showcase-row">
                    <div class="excursion-text">
                        <span class="section-overline" data-i18n="exc_overline">PLUS QU'UN TRAJET, UNE DÉCOUVERTE</span>
                        <h3 data-i18n="exc_title" data-i18n-html="true">Un dépaysement total.<br>Nous prenons soin de la route.</h3>
                        <p data-i18n="exc_desc">
                            Nous organisons des excursions privées au départ d'Aigle et de la région. En famille, entre amis ou en groupe, concevons ensemble une sortie adaptée à votre rythme et vos envies.
                        </p>
                        <a href="#organiser-excursion" class="btn btn-primary" data-i18n="exc_btn_plan">Planifier mon excursion ↗</a>
                    </div>
                    <div class="excursion-visual">
                        <img src="assets/vehicule-06.webp" alt="Véhicule MY TAXI sur une route alpine enneigée" loading="lazy">
                    </div>
                </div>

                <div class="excursion-cards-grid">
                    <article class="excursion-card">
                        <span class="excursion-tag">Culture & Vignobles</span>
                        <h4>Aigle & ses vignobles</h4>
                        <p>Visite du célèbre Château d'Aigle, dégustation dans les domaines viticoles du Chablais et balade panoramique.</p>
                        <button type="button" data-excursion-idea="Excursion Aigle et vignobles du Chablais">Choisir cette idée ↗</button>
                    </article>

                    <article class="excursion-card">
                        <span class="excursion-tag">Au bord de l'eau</span>
                        <h4>Montreux & Lac Léman</h4>
                        <p>La Riviera vaudoise, la promenade fleurie de Montreux, le Château de Chillon et les terrasses de Lavaux (UNESCO).</p>
                        <button type="button" data-excursion-idea="Excursion Riviera Montreux et Château de Chillon">Choisir cette idée ↗</button>
                    </article>

                    <article class="excursion-card">
                        <span class="excursion-tag">Prendre de l'altitude</span>
                        <h4>Évasion Alpine & Glacier 3000</h4>
                        <p>Les villages typiques des Alpes vaudoises, Villars, Les Diablerets, montée à Glacier 3000 et panorama sur le Cervin.</p>
                        <button type="button" data-excursion-idea="Évasion Alpine Les Diablerets et Glacier 3000">Choisir cette idée ↗</button>
                    </article>
                </div>

                <!-- Formulaire d'excursion -->
                <div class="excursion-form-card" id="organiser-excursion">
                    <div>
                        <span class="section-overline">MY TAXI · SUR MESURE</span>
                        <h3 style="font-size: 26px; margin-bottom: 12px;" data-i18n="exc_form_t">Une journée façonnée selon vos envies.</h3>
                        <p style="color: #9CA3AF; font-size: 14px;" data-i18n="exc_form_d">Indiquez-nous les étapes souhaitées et le nombre de participants. Nous conviendrons ensemble du parcours et du tarif forfaitaire.</p>
                        <ul class="excursion-points">
                            <li>Itinéraire 100% personnalisé et flexible</li>
                            <li>Véhicules spacieux avec chauffeur à disposition</li>
                            <li>Départs garantis depuis Aigle et environs</li>
                        </ul>
                    </div>

                    <form id="excursionForm">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label style="color: #D1D5DB;">Votre Nom *</label>
                                <input type="text" name="name" class="form-input" required placeholder="Ex: Claire Martin">
                            </div>
                            <div class="form-group">
                                <label style="color: #D1D5DB;">Téléphone *</label>
                                <input type="tel" name="phone" class="form-input" required placeholder="+41 79 000 00 00">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label style="color: #D1D5DB;">Date souhaitée</label>
                                <input type="date" id="excursionDate" name="date" class="form-input">
                            </div>
                            <div class="form-group">
                                <label style="color: #D1D5DB;">Nombre de personnes</label>
                                <input type="number" name="passengers" class="form-input" value="2" min="1" max="16">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="color: #D1D5DB;">Votre idée d'excursion & souhaits *</label>
                            <textarea id="excursionIdea" name="idea" class="form-textarea" rows="3" required placeholder="Destination, arrêts préférés, durée envisagée, vignobles, lac..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <span data-i18n="exc_btn_send">Envoyer ma demande d'excursion</span>
                            <span>↗</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Section 3 Étapes -->
        <section class="section steps-section">
            <div class="section-container">
                <div class="section-heading-row" style="text-align: center; justify-content: center;">
                    <div>
                        <span class="section-overline" data-i18n="steps_overline">SIMPLE & TRANSPARENT</span>
                        <h2 class="section-title" data-i18n="steps_title">Votre trajet en 3 étapes.</h2>
                    </div>
                </div>

                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h3 data-i18n="step_1_t">Indiquez votre itinéraire</h3>
                        <p data-i18n="step_1_d">Renseignez votre lieu de prise en charge, votre destination ainsi que la date et l'heure souhaitées sur notre formulaire.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h3 data-i18n="step_2_t">Confirmation rapide</h3>
                        <p data-i18n="step_2_d">Recevez votre référence de réservation et échangez en direct par téléphone ou WhatsApp pour finaliser les détails.</p>
                    </div>

                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h3 data-i18n="step_3_t">Voyagez en toute quiétude</h3>
                        <p data-i18n="step_3_d">Votre chauffeur vous attend ponctuellement au lieu convenu pour un voyage confortable et sécurisé.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Contact & Appel Direct -->
        <section class="contact-cta-section" id="contact">
            <div class="section-container">
                <span class="section-overline" data-i18n="contact_overline">JOUR ET NUIT · 24/7</span>
                <h2 data-i18n="contact_title">Un départ immédiat ? Parlons-en.</h2>
                <p data-i18n="contact_desc">Appelez-nous directement ou écrivez-nous sur WhatsApp pour une prise en charge rapide.</p>

                <div class="contact-actions">
                    <a href="https://wa.me/<?= $whatsapp ?>?text=Bonjour%20MY%20TAXI,%20je%20souhaite%20r%C3%A9server%20une%20course." target="_blank" rel="noopener" class="btn btn-whatsapp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.93 11.93 0 0012.07 0C5.46 0 .09 5.37.09 11.98c0 2.11.55 4.17 1.6 5.99L0 24l6.21-1.63a11.96 11.96 0 005.86 1.52h.01c6.61 0 11.98-5.37 11.98-11.98 0-3.2-1.24-6.22-3.54-8.43zm-8.45 18.42a9.92 9.92 0 01-5.06-1.39l-.36-.22-3.76.99 1-3.66-.24-.38a9.92 9.92 0 01-1.52-5.26c0-5.49 4.47-9.96 9.97-9.96 2.66 0 5.16 1.04 7.04 2.92a9.92 9.92 0 012.91 7.04c0 5.49-4.47 9.96-9.98 9.92zm5.46-7.46c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.89-.8-1.5-1.78-1.67-2.08-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/></svg>
                        <span data-i18n="contact_wa_btn">Écrire sur WhatsApp</span>
                    </a>
                    <a href="tel:<?= $phoneClean ?>" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                        <span data-i18n="contact_call_btn">Appeler le chauffeur</span>
                    </a>
                </div>

                <div>
                    <a href="tel:<?= $phoneClean ?>" class="direct-phone-large"><?= htmlspecialchars($phoneDisplay) ?></a>
                </div>
            </div>
        </section>
    </main>

    <!-- Pied de page -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-col">
                <a href="admin/login.php" title="Connexion Administration"><img src="assets/logo-white.png" alt="MY TAXI" style="height: 42px; margin-bottom: 14px; cursor: pointer;"></a>
                <p><strong>My Taxi SARL</strong> · Votre service de référence basé à Aigle. Transport de personnes, déménagement de marchandises et location de véhicules avec chauffeur disponible 24/7.</p>
            </div>

            <div class="footer-col">
                <h4>Villes Phares</h4>
                <ul class="footer-links">
                    <li><a href="#villes">Aigle (Base centrale)</a></li>
                    <li><a href="#villes">Villars-sur-Ollon & Leysin</a></li>
                    <li><a href="#villes">Les Diablerets & Gstaad</a></li>
                    <li><a href="#villes">Châtel & Chambéry (France)</a></li>
                    <li><a href="#villes">Genève (GVA) & Zurich (ZRH)</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>N°avigation & Services</h4>
                <ul class="footer-links">
                    <li><a href="#qui-sommes-nous">Qui sommes-nous</a></li>
                    <li><a href="#services">Trajets quotidiens</a></li>
                    <li><a href="#services">N°avettes Gares & Aéroports</a></li>
                    <li><a href="#vehicules">Berlines, Vans & Minibus</a></li>
                    <li><a href="#excursions">Excursions touristiques</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact & Adresse</h4>
                <ul class="footer-links">
                    <li style="color: #9CA3AF; display: flex; align-items: flex-start; gap: 6px; font-size: 13px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Avenue des Alpes 28, 1860 Aigle</span>
                    </li>
                    <li><a href="tel:<?= $phoneClean ?>">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg><?= htmlspecialchars($phoneDisplay) ?></a></li>
                    <li><a href="mailto:<?= htmlspecialchars(CONTACT_EMAIL) ?>">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg><?= htmlspecialchars(CONTACT_EMAIL) ?></a></li>
                    <li><a href="admin/login.php" style="color: var(--brand-orange); font-weight: 700;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>Espace Dispatch Chauffeur</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; <?= date('Y') ?> MY TAXI &middot; Aigle &amp; R&eacute;gion. Tous droits r&eacute;serv&eacute;s.</div>
            <div>Service de transport professionnel certifi&eacute;</div>
        </div>
    </footer>

    <!-- Barre d'action sticky sur mobile -->
    <div class="mobile-sticky-bar">
        <a href="tel:<?= $phoneClean ?>" class="btn btn-call-nav">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg> Appeler
        </a>
        <a href="#reserver" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Réserver
        </a>
    </div>

    <!-- Lightbox photo plein écran -->
    <div class="photo-lightbox" id="photoLightbox">
        <div class="lightbox-content">
            <button type="button" class="lightbox-close" id="lightboxClose">&times;</button>
            <img src="" alt="" id="lightboxImg">
            <div class="lightbox-caption" id="lightboxCaption"></div>
        </div>
    </div>

    <!-- Modal Succès de Réservation -->
    <div class="success-modal" id="bookingSuccessModal">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <h3>Demande Enregistrée !</h3>
            <p>Votre réservation a bien été enregistrée dans notre centrale MY TAXI.</p>

            <div class="booking-ref-box">
                <small>N°uméro de référence course :</small>
                <strong id="modalRefCode">MT-2026-0000</strong>
            </div>

            <p style="font-size: 13px; color: #4B5563; margin-bottom: 20px;">
                Vous pouvez également ouvrir WhatsApp directement pour transmettre les détails à votre chauffeur en 1 clic :
            </p>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="#" id="modalWhatsAppBtn" target="_blank" class="btn btn-whatsapp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.93 11.93 0 0012.07 0C5.46 0 .09 5.37.09 11.98c0 2.11.55 4.17 1.6 5.99L0 24l6.21-1.63a11.96 11.96 0 005.86 1.52h.01c6.61 0 11.98-5.37 11.98-11.98 0-3.2-1.24-6.22-3.54-8.43zm-8.45 18.42a9.92 9.92 0 01-5.06-1.39l-.36-.22-3.76.99 1-3.66-.24-.38a9.92 9.92 0 01-1.52-5.26c0-5.49 4.47-9.96 9.97-9.96 2.66 0 5.16 1.04 7.04 2.92a9.92 9.92 0 012.91 7.04c0 5.49-4.47 9.96-9.98 9.92zm5.46-7.46c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.89-.8-1.5-1.78-1.67-2.08-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/></svg>
                    <span>Ouvrir sur WhatsApp</span>
                </a>
                <button type="button" class="btn btn-dark" onclick="closeSuccessModal()">Terminer</button>
            </div>
        </div>
    </div>

    <!-- Scripts JS -->
    <script src="assets/js/translations.js?v=<?= time() ?>"></script>
    <script src="assets/js/main.js?v=<?= time() ?>"></script>
</body>
</html>
