<?php
/**
 * Module d'envoi d'emails - MY TAXI
 * Utilise PHPMailer avec Gmail SMTP (App Password)
 * Design Premium & Chauffeur Privé
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Créer et configurer une instance PHPMailer SMTP
 */
function createMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    // Configuration SMTP
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->Encoding   = 'base64';

    // Expéditeur
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);

    // Attacher le logo officiel en image intégrée (CID)
    $logoPath = __DIR__ . '/assets/logo-white.png';
    if (file_exists($logoPath)) {
        try {
            $mail->addEmbeddedImage($logoPath, 'mytaxi_logo', 'logo-white.png', 'base64', 'image/png');
        } catch (\Exception $e) {
            // Ignorer si déjà attaché
        }
    }

    return $mail;
}

/**
 * Template HTML de base pour tous les emails
 */
function emailTemplate(string $title, string $bodyContent, string $footerNote = ''): string {
    $year = date('Y');
    $companyName = COMPANY_NAME;
    $companyAddr = COMPANY_ADDRESS;
    $phone = PHONE_DISPLAY;

    return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
<style>
  body { margin:0; padding:0; background:#0E0F13; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; }
  .email-container { max-width:620px; margin:24px auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.25); border: 1px solid #23252E; }
  .header-bar { background: linear-gradient(180deg, #0E0F13 0%, #17181F 100%); padding: 32px 24px 22px 24px; text-align: center; border-bottom: 3px solid #EB8125; }
  .header-sub { color: #EB8125; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-top: 10px; }
  .content-wrap { padding: 32px 28px; background: #ffffff; }
  .route-card { background: #FAFAFB; border: 1px solid #E8E8ED; border-radius: 12px; padding: 20px; margin: 20px 0; }
  .route-point { display: flex; align-items: flex-start; margin-bottom: 12px; }
  .badge-icon { display: inline-block; width: 34px; height: 34px; line-height: 34px; text-align: center; border-radius: 8px; background: #FFF4E8; border: 1px solid #FFE2CA; font-size: 15px; margin-right: 12px; flex-shrink: 0; vertical-align: middle; }
  .info-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
  .info-table tr { border-bottom: 1px solid #F0F0F4; }
  .info-table tr:last-child { border-bottom: none; }
  .info-table td { padding: 12px 10px; font-size: 14px; vertical-align: middle; }
  .info-label { width: 170px; color: #64748B; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
  .info-value { color: #0F172A; font-weight: 600; font-size: 14px; }
  .btn-call { display: inline-block; background: #EB8125; color: #ffffff !important; text-decoration: none; padding: 13px 26px; border-radius: 8px; font-size: 14px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 4px 12px rgba(235,129,37,0.3); margin: 6px 4px; }
  .btn-whatsapp { display: inline-block; background: #25D366; color: #ffffff !important; text-decoration: none; padding: 13px 26px; border-radius: 8px; font-size: 14px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 4px 12px rgba(37,211,102,0.3); margin: 6px 4px; }
  .ref-pill { display: inline-block; background: #EB8125; color: #ffffff; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; }
  .status-pill { display: inline-block; background: #FFF4E8; color: #EB8125; border: 1px solid #FFE2CA; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-left: 6px; }
  .footer-wrap { background: #0E0F13; padding: 26px 24px; text-align: center; border-top: 1px solid #23252E; }
  .footer-wrap p { color: #8F93A0; font-size: 12px; margin: 4px 0; line-height: 1.6; }
  .footer-wrap a { color: #EB8125; text-decoration: none; font-weight: 600; }
  @media (max-width: 600px) {
    .content-wrap { padding: 22px 16px; }
    .info-table td { display: block; width: 100% !important; padding: 6px 0; }
    .info-label { padding-top: 10px; border-bottom: none; }
  }
</style>
</head>
<body style="margin:0; padding:20px 10px; background:#0E0F13;">
  <div class="email-container" style="max-width:620px; margin:0 auto; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.35);">
    
    <!-- HEADER AVEC VRAI LOGO OFFICIEL -->
    <div class="header-bar" style="background:#0E0F13; padding:32px 24px 22px 24px; text-align:center; border-bottom:3px solid #EB8125;">
      <img src="cid:mytaxi_logo" alt="MY TAXI" style="max-height:56px; max-width:210px; width:auto; height:auto; display:block; margin:0 auto 10px auto; border:0;" />
      <div style="color:#EB8125; font-size:11px; font-weight:800; letter-spacing:2px; text-transform:uppercase;">
        Service Officiel de Taxi &bull; Aigle &amp; R&eacute;gion &bull; 24h/24
      </div>
    </div>

    <!-- CORPS DU MESSAGE -->
    <div class="content-wrap" style="padding:32px 28px; background:#ffffff;">
      {$bodyContent}
    </div>

    <!-- FOOTER NOIR ÉLÉGANT -->
    <div class="footer-wrap" style="background:#0E0F13; padding:24px 20px; text-align:center; border-top:1px solid #1F212A;">
      {$footerNote}
      <p style="color:#ffffff; font-size:13px; font-weight:700; margin:4px 0;">{$companyName}</p>
      <p style="color:#8F93A0; font-size:12px; margin:3px 0;">{$companyAddr}</p>
      <p style="color:#8F93A0; font-size:12px; margin:6px 0 10px 0;">
        T&eacute;l : <a href="tel:+41792666121" style="color:#EB8125; font-weight:700;">{$phone}</a>
        &nbsp;&bull;&nbsp;
        WhatsApp : <a href="https://wa.me/41792666121" style="color:#25D366; font-weight:700;">+41 79 266 61 21</a>
      </p>
      <p style="color:#565A68; font-size:11px; margin:10px 0 0 0;">&copy; {$year} MY TAXI &bull; Tous droits r&eacute;serv&eacute;s</p>
    </div>

  </div>
</body>
</html>
HTML;
}

// ──────────────────────────────────────────────────────────────────────
// 1. NOTIFICATION AU CHAUFFEUR / DISPATCH (Nouvelle réservation reçue)
// ──────────────────────────────────────────────────────────────────────

function sendBookingNotification(array $booking): bool {
    if (!SMTP_ENABLED) return false;

    $vehicleLabels = [
        'sedan'   => 'Mercedes-Benz Classe E (1 à 4 pers.)',
        'van'     => 'Mercedes-Benz Classe V (Jusqu\'à 7 pers.)',
    ];
    $vehicleLabel = $vehicleLabels[$booking['vehicle']] ?? $booking['vehicle'];
    $formattedDate = date('d.m.Y', strtotime($booking['date']));
    $ref = htmlspecialchars($booking['ref_code']);
    $name = htmlspecialchars($booking['name']);
    $phone = htmlspecialchars($booking['phone']);
    $phoneClean = preg_replace('/[^0-9+]/', '', $phone);
    $email = htmlspecialchars($booking['email'] ?? '—');
    $pickup = htmlspecialchars($booking['pickup']);
    $dest = htmlspecialchars($booking['destination']);
    $time = htmlspecialchars($booking['time']);
    $pax = intval($booking['passengers']);
    $lug = intval($booking['luggage']);
    $flight = !empty($booking['flight_number']) ? htmlspecialchars($booking['flight_number']) : '—';
    $notes = !empty($booking['notes']) ? htmlspecialchars($booking['notes']) : '—';

    $body = <<<HTML
<!-- EN-TÊTE DU STATUT DE LA COURSE -->
<div style="text-align:center; margin-bottom:24px;">
  <span class="ref-pill" style="background:#EB8125; color:#ffffff; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:700; letter-spacing:0.5px;">R&eacute;f : {$ref}</span>
  <span class="status-pill" style="background:#FFF4E8; color:#EB8125; border:1px solid #FFE2CA; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:700;">En attente de confirmation</span>
  <h2 style="color:#0E0F13; font-size:22px; font-weight:800; margin:14px 0 6px 0;">Nouvelle R&eacute;servation Re&ccedil;ue</h2>
  <p style="color:#64748B; font-size:14px; margin:0;">Une demande de course vient d'&ecirc;tre enregistr&eacute;e sur le site web.</p>
</div>

<!-- ITINÉRAIRE EN CARTE MISE EN VALEUR -->
<div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:18px 20px; margin:22px 0;">
  <div style="font-size:11px; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:#94A3B8; margin-bottom:12px;">Itin&eacute;raire demand&eacute;</div>
  
  <table style="width:100%; border-collapse:collapse;">
    <tr>
      <td style="width:36px; vertical-align:top; padding-bottom:12px;">
        <span style="display:inline-block; width:26px; height:26px; line-height:26px; text-align:center; background:#E0F2FE; color:#0284C7; border-radius:50%; font-weight:800; font-size:11px;">A</span>
      </td>
      <td style="vertical-align:top; padding-bottom:12px;">
        <div style="font-size:11px; text-transform:uppercase; color:#64748B; font-weight:700;">D&eacute;part (Prise en charge)</div>
        <div style="font-size:15px; color:#0F172A; font-weight:700; margin-top:2px;">{$pickup}</div>
      </td>
    </tr>
    <tr>
      <td style="width:36px; vertical-align:top;">
        <span style="display:inline-block; width:26px; height:26px; line-height:26px; text-align:center; background:#FFEDD5; color:#EA580C; border-radius:50%; font-weight:800; font-size:11px;">B</span>
      </td>
      <td style="vertical-align:top;">
        <div style="font-size:11px; text-transform:uppercase; color:#64748B; font-weight:700;">Destination</div>
        <div style="font-size:15px; color:#0F172A; font-weight:700; margin-top:2px;">{$dest}</div>
      </td>
    </tr>
  </table>
</div>

<!-- TABLEAU DES DÉTAILS AVEC BADGES PROPRES (STYLE ÉLÉGANT) -->
<table class="info-table" style="width:100%; border-collapse:collapse;">
  <tr>
    <td class="info-label" style="padding:12px 10px; width:160px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      Client
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:15px; border-bottom:1px solid #F0F0F4;">
      {$name}
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      T&eacute;l&eacute;phone
    </td>
    <td class="info-value" style="padding:12px 10px; border-bottom:1px solid #F0F0F4;">
      <a href="tel:{$phoneClean}" style="color:#EB8125; text-decoration:none; font-weight:700; font-size:15px;">{$phone}</a>
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      Email
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; border-bottom:1px solid #F0F0F4;">
      {$email}
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      Date &amp; Heure
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">
      {$formattedDate} &agrave; {$time}
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      V&eacute;hicule
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">
      {$vehicleLabel}
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      Passagers &amp; Bagages
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; font-size:14px; border-bottom:1px solid #F0F0F4;">
      {$pax} passager(s) &bull; {$lug} bagage(s)
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      N&deg; Vol / Train
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; font-size:14px; border-bottom:1px solid #F0F0F4;">
      {$flight}
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase;">
      <span class="badge-icon" style="background:#FFF4E8; border:1px solid #FFE2CA; color:#EB8125; display:inline-block; width:28px; height:28px; line-height:28px; text-align:center; border-radius:6px; font-size:13px; font-weight:bold; margin-right:6px; vertical-align:middle;">&bull;</span>
      Remarques
    </td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:500; font-size:14px;">
      {$notes}
    </td>
  </tr>
</table>

<!-- ACTIONS RAPIDES : APPEL & WHATSAPP -->
<div style="text-align:center; margin:30px 0 10px 0;">
  <a href="tel:{$phoneClean}" class="btn-call" style="background:#EB8125; color:#ffffff !important; text-decoration:none; padding:13px 26px; border-radius:8px; font-size:14px; font-weight:700; display:inline-block; margin:6px 4px;">
    Appeler le client
  </a>
  <a href="https://wa.me/{$phoneClean}" class="btn-whatsapp" style="background:#25D366; color:#ffffff !important; text-decoration:none; padding:13px 26px; border-radius:8px; font-size:14px; font-weight:700; display:inline-block; margin:6px 4px;">
    Contacter sur WhatsApp
  </a>
</div>

<p style="text-align:center; color:#94A3B8; font-size:12px; margin-top:16px;">
  Connectez-vous &agrave; l'administration MY TAXI pour confirmer la course ou lui assigner un chauffeur.
</p>
HTML;

    $html = emailTemplate("Nouvelle Réservation {$ref}", $body);

    try {
        $mail = createMailer();
        $mail->addAddress(SMTP_NOTIFY_TO);
        $mail->isHTML(true);
        $mail->Subject = "Nouvelle Réservation {$ref} — {$name} · {$pickup} → {$dest}";
        $mail->Body    = $html;
        $mail->AltBody = "Nouvelle réservation {$ref}\nClient: {$booking['name']}\nTél: {$booking['phone']}\n{$booking['pickup']} → {$booking['destination']}\nDate: {$formattedDate} à {$booking['time']}\nVéhicule: {$vehicleLabel}";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("[MY TAXI MAILER] Erreur notification booking: " . $e->getMessage());
        return false;
    }
}

// ──────────────────────────────────────────────────────────────────────
// 2. CONFIRMATION CLIENT (Email de confirmation au passager)
// ──────────────────────────────────────────────────────────────────────

function sendBookingConfirmation(array $booking): bool {
    if (!SMTP_ENABLED) return false;
    if (empty($booking['email'])) return false;

    $vehicleLabels = [
        'sedan'   => 'Berline Mercedes Classe E',
        'van'     => 'Van Mercedes Classe V',
        'minibus' => 'Minibus VIP',
    ];
    $vehicleLabel = $vehicleLabels[$booking['vehicle']] ?? $booking['vehicle'];
    $formattedDate = date('d.m.Y', strtotime($booking['date']));
    $ref = htmlspecialchars($booking['ref_code']);
    $name = htmlspecialchars($booking['name']);
    $pickup = htmlspecialchars($booking['pickup']);
    $dest = htmlspecialchars($booking['destination']);
    $time = htmlspecialchars($booking['time']);

    $body = <<<HTML
<div style="text-align:center; margin-bottom:24px;">
  <span class="ref-pill" style="background:#EB8125; color:#ffffff; padding:6px 14px; border-radius:20px; font-size:13px; font-weight:700;">R&eacute;f&eacute;rence : {$ref}</span>
  <h2 style="color:#0E0F13; font-size:22px; font-weight:800; margin:14px 0 6px 0;">Merci pour votre r&eacute;servation, {$name} !</h2>
  <p style="color:#64748B; font-size:14px; margin:0;">Votre demande de course a bien &eacute;t&eacute; enregistr&eacute;e. Notre centrale va la traiter imm&eacute;diatement.</p>
</div>

<!-- RÉCAPITULATIF DE L'ITINÉRAIRE -->
<div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:18px 20px; margin:22px 0;">
  <div style="font-size:11px; font-weight:800; letter-spacing:1px; text-transform:uppercase; color:#94A3B8; margin-bottom:12px;">R&eacute;capitulatif de votre course</div>
  
  <table style="width:100%; border-collapse:collapse;">
    <tr>
      <td style="width:36px; vertical-align:top; padding-bottom:12px;">
        <span style="display:inline-block; width:26px; height:26px; line-height:26px; text-align:center; background:#E0F2FE; color:#0284C7; border-radius:50%; font-weight:800; font-size:11px;">A</span>
      </td>
      <td style="vertical-align:top; padding-bottom:12px;">
        <div style="font-size:11px; text-transform:uppercase; color:#64748B; font-weight:700;">Prise en charge</div>
        <div style="font-size:15px; color:#0F172A; font-weight:700; margin-top:2px;">{$pickup}</div>
      </td>
    </tr>
    <tr>
      <td style="width:36px; vertical-align:top;">
        <span style="display:inline-block; width:26px; height:26px; line-height:26px; text-align:center; background:#FFEDD5; color:#EA580C; border-radius:50%; font-weight:800; font-size:11px;">B</span>
      </td>
      <td style="vertical-align:top;">
        <div style="font-size:11px; text-transform:uppercase; color:#64748B; font-weight:700;">Destination</div>
        <div style="font-size:15px; color:#0F172A; font-weight:700; margin-top:2px;">{$dest}</div>
      </td>
    </tr>
  </table>
</div>

<table class="info-table" style="width:100%; border-collapse:collapse;">
  <tr>
    <td class="info-label" style="padding:12px 10px; width:160px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Date &amp; Heure</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">{$formattedDate} &agrave; {$time}</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">V&eacute;hicule</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">{$vehicleLabel}</td>
  </tr>
</table>

<div style="background:#FFF9F2; border-left:3px solid #EB8125; padding:16px 20px; border-radius:0 8px 8px 0; margin:24px 0;">
  <div style="font-weight:700; color:#0E0F13; font-size:14px; margin-bottom:6px;">Prochaines &eacute;tapes</div>
  <p style="color:#555966; font-size:13px; line-height:1.7; margin:0;">
    Notre chauffeur va vous contacter par t&eacute;l&eacute;phone ou WhatsApp afin de confirmer la disponibilit&eacute; et le tarif. <strong>Le jour de votre d&eacute;part (le {$formattedDate})</strong>, votre chauffeur sera pr&eacute;sent <strong>5 minutes avant l'heure pr&eacute;vue</strong> &agrave; votre adresse de prise en charge.
  </p>
</div>

<div style="text-align:center; margin:24px 0 10px 0;">
  <a href="tel:+41792666121" class="btn-call" style="background:#EB8125; color:#ffffff !important; text-decoration:none; padding:13px 26px; border-radius:8px; font-size:14px; font-weight:700; display:inline-block; margin:6px 4px;">
    Nous contacter : 079 266 61 21
  </a>
</div>
HTML;

    $footer = '<p style="font-size:11px; color:#8F93A0;">Cet email atteste de la prise en compte de votre demande. Notre chauffeur vous contactera sous peu pour confirmation finale.</p>';
    $html = emailTemplate("Confirmation {$ref}", $body, $footer);

    try {
        $mail = createMailer();
        $mail->addAddress($booking['email'], $booking['name']);
        $mail->isHTML(true);
        $mail->Subject = "Confirmation de demande de course {$ref} — MY TAXI Aigle";
        $mail->Body    = $html;
        $mail->AltBody = "Bonjour {$booking['name']},\n\nVotre demande de réservation {$ref} a bien été reçue.\n{$booking['pickup']} → {$booking['destination']}\nDate: {$formattedDate} à {$booking['time']}\n\nNotre chauffeur vous contactera rapidement.\n\nMY TAXI · " . PHONE_DISPLAY;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("[MY TAXI MAILER] Erreur confirmation client: " . $e->getMessage());
        return false;
    }
}

// ──────────────────────────────────────────────────────────────────────
// 3. NOTIFICATION MESSAGE DE CONTACT
// ──────────────────────────────────────────────────────────────────────

function sendContactNotification(array $contact): bool {
    if (!SMTP_ENABLED) return false;

    $name = htmlspecialchars($contact['name']);
    $phone = htmlspecialchars($contact['phone'] ?? '—');
    $phoneClean = preg_replace('/[^0-9+]/', '', $phone);
    $email = htmlspecialchars($contact['email'] ?? '—');
    $subject = htmlspecialchars($contact['subject'] ?? 'Demande générale');
    $message = nl2br(htmlspecialchars($contact['message']));

    $body = <<<HTML
<div style="text-align:center; margin-bottom:24px;">
  <span class="ref-pill" style="background:#0E0F13; color:#ffffff; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700;">FORMULAIRE DE CONTACT</span>
  <h2 style="color:#0E0F13; font-size:22px; font-weight:800; margin:14px 0 6px 0;">Nouveau Message Re&ccedil;u</h2>
  <p style="color:#64748B; font-size:14px; margin:0;">Un visiteur vous a envoy&eacute; un message depuis le site web.</p>
</div>

<table class="info-table" style="width:100%; border-collapse:collapse;">
  <tr>
    <td class="info-label" style="padding:12px 10px; width:140px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Exp&eacute;diteur</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">{$name}</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">T&eacute;l&eacute;phone</td>
    <td class="info-value" style="padding:12px 10px; border-bottom:1px solid #F0F0F4;">
      <a href="tel:{$phoneClean}" style="color:#EB8125; text-decoration:none; font-weight:700;">{$phone}</a>
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Email</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; border-bottom:1px solid #F0F0F4;">
      <a href="mailto:{$email}" style="color:#EB8125; text-decoration:none;">{$email}</a>
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase;">Sujet</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700;">{$subject}</td>
  </tr>
</table>

<div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:18px 20px; margin:22px 0;">
  <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#94A3B8; font-weight:800; margin-bottom:8px;">Message du client :</div>
  <div style="color:#1E293B; font-size:14px; line-height:1.7;">{$message}</div>
</div>

<div style="text-align:center; margin:24px 0 10px 0;">
  <a href="tel:{$phoneClean}" class="btn-call" style="background:#EB8125; color:#ffffff !important; text-decoration:none; padding:13px 26px; border-radius:8px; font-size:14px; font-weight:700; display:inline-block; margin:6px 4px;">
    R&eacute;pondre par t&eacute;l&eacute;phone
  </a>
</div>
HTML;

    $html = emailTemplate("Message de {$name}", $body);

    try {
        $mail = createMailer();
        $mail->addAddress(SMTP_NOTIFY_TO);
        if (!empty($contact['email'])) {
            $mail->addReplyTo($contact['email'], $contact['name']);
        }
        $mail->isHTML(true);
        $mail->Subject = "Message de {$contact['name']} — {$subject}";
        $mail->Body    = $html;
        $mail->AltBody = "Message de contact\nNom: {$contact['name']}\nTél: {$contact['phone']}\nEmail: {$contact['email']}\nSujet: {$contact['subject']}\n\n{$contact['message']}";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("[MY TAXI MAILER] Erreur notification contact: " . $e->getMessage());
        return false;
    }
}

// ──────────────────────────────────────────────────────────────────────
// 4. NOTIFICATION DEMANDE D'EXCURSION
// ──────────────────────────────────────────────────────────────────────

function sendExcursionNotification(array $excursion): bool {
    if (!SMTP_ENABLED) return false;

    $name = htmlspecialchars($excursion['name']);
    $phone = htmlspecialchars($excursion['phone']);
    $phoneClean = preg_replace('/[^0-9+]/', '', $phone);
    $idea = htmlspecialchars($excursion['idea']);
    $date = !empty($excursion['date']) ? date('d.m.Y', strtotime($excursion['date'])) : 'À convenir';
    $pax = intval($excursion['passengers'] ?? 2);
    $details = !empty($excursion['details']) ? nl2br(htmlspecialchars($excursion['details'])) : '—';

    $body = <<<HTML
<div style="text-align:center; margin-bottom:24px;">
  <span class="ref-pill" style="background:#EB8125; color:#ffffff; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700;">EXCURSION TOURISTIQUE</span>
  <h2 style="color:#0E0F13; font-size:22px; font-weight:800; margin:14px 0 6px 0;">Nouvelle Demande d'Excursion</h2>
  <p style="color:#64748B; font-size:14px; margin:0;">Un client souhaite organiser une journ&eacute;e ou un circuit sur mesure.</p>
</div>

<table class="info-table" style="width:100%; border-collapse:collapse;">
  <tr>
    <td class="info-label" style="padding:12px 10px; width:140px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Client</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:14px; border-bottom:1px solid #F0F0F4;">{$name}</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">T&eacute;l&eacute;phone</td>
    <td class="info-value" style="padding:12px 10px; border-bottom:1px solid #F0F0F4;">
      <a href="tel:{$phoneClean}" style="color:#EB8125; text-decoration:none; font-weight:700;">{$phone}</a>
    </td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Circuit Souhait&eacute;</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:700; font-size:15px; border-bottom:1px solid #F0F0F4;">{$idea}</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Date pr&eacute;f&eacute;r&eacute;e</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; border-bottom:1px solid #F0F0F4;">{$date}</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase; border-bottom:1px solid #F0F0F4;">Personnes</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:600; border-bottom:1px solid #F0F0F4;">{$pax} personne(s)</td>
  </tr>
  <tr>
    <td class="info-label" style="padding:12px 10px; color:#64748B; font-weight:700; font-size:12px; text-transform:uppercase;">D&eacute;tails / Envies</td>
    <td class="info-value" style="padding:12px 10px; color:#0F172A; font-weight:500;">{$details}</td>
  </tr>
</table>

<div style="text-align:center; margin:26px 0 10px 0;">
  <a href="tel:{$phoneClean}" class="btn-call" style="background:#EB8125; color:#ffffff !important; text-decoration:none; padding:13px 26px; border-radius:8px; font-size:14px; font-weight:700; display:inline-block; margin:6px 4px;">
    Appeler le client pour &eacute;tablir un devis
  </a>
</div>
HTML;

    $html = emailTemplate("Excursion — {$name}", $body);

    try {
        $mail = createMailer();
        $mail->addAddress(SMTP_NOTIFY_TO);
        $mail->isHTML(true);
        $mail->Subject = "Demande d'excursion — {$excursion['name']} · {$excursion['idea']}";
        $mail->Body    = $html;
        $mail->AltBody = "Demande d'excursion\nClient: {$excursion['name']}\nTél: {$excursion['phone']}\nIdée: {$excursion['idea']}\nDate: {$date}\nPersonnes: {$pax}";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("[MY TAXI MAILER] Erreur notification excursion: " . $e->getMessage());
        return false;
    }
}
