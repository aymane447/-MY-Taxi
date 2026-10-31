css = """\
/* ==========================================================================
   MY TAXI - Nouvelle Charte Graphique Premium
   Design : Luxe Nuit - Bleu Marine Profond - Or Champagne - Blanc Perle
   Police : Montserrat (Google Fonts)
   ========================================================================== */

:root {
    --navy:          #0A0E1A;
    --navy-mid:      #0F1525;
    --navy-card:     #141B2D;
    --navy-surface:  #1A2340;
    --navy-border:   rgba(255,255,255,0.08);
    --gold:          #C8A96E;
    --gold-light:    #E0C48A;
    --gold-dark:     #A8874A;
    --gold-glow:     rgba(200,169,110,0.28);
    --gold-subtle:   rgba(200,169,110,0.10);
    --white:         #FFFFFF;
    --pearl:         #F4F1EC;
    --text-muted:    #8E9BB8;
    --text-light:    #D8DEEC;
    --green-wa:      #25D366;
    --green-wa-h:    #1DB954;
    --emerald:       #10B981;
    --r-sm:   8px;
    --r-md:   14px;
    --r-lg:   22px;
    --r-xl:   32px;
    --r-full: 9999px;
    --shadow-sm:   0 2px 12px rgba(0,0,0,0.15);
    --shadow-md:   0 8px 32px rgba(0,0,0,0.25);
    --shadow-lg:   0 20px 60px rgba(0,0,0,0.35);
    --shadow-gold: 0 8px 30px rgba(200,169,110,0.40);
    --ease: cubic-bezier(0.22, 1, 0.36, 1);
    --t:    all 0.30s var(--ease);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; scroll-padding-top: 88px; font-size: 16px; }
body {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--navy);
    color: var(--text-light);
    line-height: 1.65;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
a { color: inherit; text-decoration: none; transition: var(--t); }
img { max-width: 100%; display: block; }
button, input, select, textarea { font: inherit; }

/* TOP BAR */
.top-notice-bar {
    background: var(--navy-mid);
    color: var(--text-muted);
    font-size: 11.5px;
    padding: 8px 5%;
    border-bottom: 1px solid var(--navy-border);
}
.top-notice-inner {
    max-width: 1300px; margin: 0 auto;
    display: flex; justify-content: space-between; align-items: center;
}
.notice-cities { display: flex; align-items: center; gap: 8px; }
.live-indicator {
    width: 7px; height: 7px;
    background: var(--emerald); border-radius: 50%;
    display: inline-block; box-shadow: 0 0 10px var(--emerald);
    animation: livePulse 2s infinite ease-in-out;
}
@keyframes livePulse {
    0%,100%{ opacity:1; transform:scale(1); }
    50%    { opacity:0.35; transform:scale(0.8); }
}
.admin-shortcut { color: var(--text-muted); font-size: 11px; display: flex; align-items: center; gap: 4px; opacity: 0.7; transition: var(--t); }
.admin-shortcut:hover { color: var(--gold); opacity: 1; }

/* HEADER */
header.site-header {
    position: sticky; top: 0; z-index: 100;
    background: rgba(10,14,26,0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(200,169,110,0.15);
    transition: background 0.3s ease, box-shadow 0.3s ease, padding 0.3s ease;
}
header.site-header.scrolled {
    background: rgba(10,14,26,0.97);
    box-shadow: 0 4px 30px rgba(0,0,0,0.5);
    border-bottom-color: rgba(200,169,110,0.30);
}
.nav-container {
    max-width: 1300px; margin: 0 auto;
    padding: 16px 5%;
    display: flex; align-items: center; justify-content: space-between; gap: 24px;
}
.brand-wrapper { display: flex; align-items: center; gap: 12px; }
.brand-logo-img { height: 46px; width: auto; object-fit: contain; transition: transform 0.25s var(--ease); }
.brand-logo-img:hover { transform: scale(1.04); filter: drop-shadow(0 0 8px rgba(200,169,110,0.4)); }
.nav-menu { display: flex; align-items: center; gap: 30px; list-style: none; }
.nav-menu a {
    color: var(--text-light); font-size: 13px; font-weight: 600;
    letter-spacing: 0.4px; position: relative; padding-bottom: 4px;
}
.nav-menu a::after {
    content: ''; position: absolute; bottom: 0; left: 0;
    width: 0; height: 2px; background: var(--gold); border-radius: 2px;
    transition: width 0.3s var(--ease);
}
.nav-menu a:hover { color: var(--gold-light); }
.nav-menu a:hover::after, .nav-menu a.nav-active::after { width: 100%; }
.nav-menu a.nav-active { color: var(--gold) !important; }
.header-cta-group { display: flex; align-items: center; gap: 10px; }
.header-lang-picker {
    display: inline-flex; align-items: center; height: 42px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: var(--r-full); padding: 4px; gap: 4px;
    backdrop-filter: blur(10px);
}
.lang-choice {
    background: transparent; border: none; color: var(--text-light);
    font-size: 12px; font-weight: 700;
    padding: 6px 14px; border-radius: var(--r-full);
    cursor: pointer; transition: var(--t); letter-spacing: 0.5px;
}
.lang-choice:hover { color: #fff; }
.lang-choice.active {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--navy) !important; font-weight: 800;
    box-shadow: 0 2px 12px var(--gold-glow);
}
.header-icon-btn {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    color: var(--navy);
    display: flex; align-items: center; justify-content: center;
    transition: var(--t); box-shadow: 0 4px 18px var(--gold-glow); flex-shrink: 0;
}
.header-icon-btn svg { stroke: var(--navy); display: block; transition: transform 0.2s; }
.header-icon-btn:hover { transform: translateY(-2px) scale(1.05); box-shadow: 0 8px 28px rgba(200,169,110,0.60); }
.header-icon-btn:hover svg { transform: scale(1.1); }
.btn.btn-header-reserve, .btn-header-reserve {
    background: transparent; color: var(--gold) !important;
    height: 42px; padding: 0 24px;
    border-radius: var(--r-full) !important;
    font-size: 13px; font-weight: 700; letter-spacing: 0.4px;
    border: 1.5px solid var(--gold); transition: var(--t);
    display: inline-flex; align-items: center; justify-content: center;
}
.btn.btn-header-reserve:hover, .btn-header-reserve:hover {
    background: var(--gold); color: var(--navy) !important;
    box-shadow: var(--shadow-gold); transform: translateY(-1px);
}
.mobile-nav-toggle { display: none; background: transparent; border: none; color: var(--gold); font-size: 22px; cursor: pointer; }

/* BUTTONS */
.btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 9px; padding: 13px 24px; border-radius: var(--r-sm);
    font-weight: 700; font-size: 14px; cursor: pointer; border: none;
    transition: var(--t); white-space: nowrap;
}
.btn-primary {
    background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 50%, var(--gold-light) 100%);
    color: var(--navy); box-shadow: 0 4px 16px var(--gold-glow);
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(200,169,110,0.50); color: var(--navy); }
.btn-dark { background: var(--navy-card); color: var(--white); border: 1px solid var(--navy-border); }
.btn-dark:hover { background: var(--navy-surface); border-color: var(--gold); }
.btn-whatsapp { background: var(--green-wa); color: #FFFFFF; box-shadow: 0 4px 14px rgba(37,211,102,0.30); }
.btn-whatsapp:hover { background: var(--green-wa-h); transform: translateY(-2px); }
.btn-outline { background: transparent; color: var(--gold); border: 1.5px solid var(--gold); }
.btn-outline:hover { background: var(--gold); color: var(--navy); }
.btn-call-nav { background: var(--navy-card); color: var(--white); padding: 10px 18px; font-size: 13px; font-weight: 700; }
.btn-call-nav:hover { background: var(--gold); color: var(--navy); }

/* HERO */
.hero-fullscreen-section {
    position: relative;
    min-height: clamp(620px, 88vh, 900px);
    display: flex; align-items: center;
    padding: 70px 5% 80px;
    background-color: var(--navy);
    background-image: url('../hero-bg.jpg');
    background-position: center right; background-size: cover;
    background-repeat: no-repeat; overflow: hidden;
}
.hero-fullscreen-section::before {
    content: ''; position: absolute; bottom: -120px; left: -80px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(200,169,110,0.06) 0%, transparent 70%);
    pointer-events: none; z-index: 1;
}
.hero-bg-overlay {
    position: absolute; inset: 0; pointer-events: none;
    background:
        linear-gradient(100deg, rgba(10,14,26,0.97) 0%, rgba(10,14,26,0.88) 40%, rgba(10,14,26,0.55) 68%, rgba(10,14,26,0.18) 100%),
        linear-gradient(180deg, rgba(10,14,26,0.65) 0%, transparent 28%, transparent 72%, rgba(10,14,26,0.97) 100%);
}
.hero-content-wrap { position: relative; z-index: 2; width: 100%; max-width: 1300px; margin: 0 auto; }
.hero-main-column { max-width: 680px; }
.hero-status-pill {
    display: inline-flex; align-items: center; gap: 10px;
    background: rgba(20,27,45,0.80); backdrop-filter: blur(14px);
    border: 1px solid rgba(200,169,110,0.30); color: var(--pearl);
    padding: 8px 20px; border-radius: var(--r-full);
    font-size: 12.5px; font-weight: 600; margin-bottom: 28px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.30), inset 0 1px 0 rgba(255,255,255,0.06);
}
.hero-giant-title {
    font-size: clamp(40px, 5.5vw, 74px);
    font-weight: 800; line-height: 1.06; letter-spacing: -2px;
    color: var(--white); margin-bottom: 24px;
    text-shadow: 0 4px 32px rgba(0,0,0,0.50);
}
.hero-giant-title .highlight {
    background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-light) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; display: block;
}
.hero-description-text {
    font-size: clamp(14px, 1.2vw, 17px); color: var(--text-light);
    line-height: 1.70; margin-bottom: 38px; max-width: 560px; font-weight: 400; opacity: 0.90;
}
.hero-actions-row { display: flex; flex-wrap: wrap; align-items: center; gap: 14px; margin-bottom: 52px; }
.btn-hero-primary {
    background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold) 50%, var(--gold-light) 100%);
    color: var(--navy); padding: 16px 32px; border-radius: var(--r-full);
    font-weight: 800; font-size: 15px; display: inline-flex; align-items: center; gap: 10px;
    box-shadow: 0 8px 28px var(--gold-glow); transition: var(--t);
}
.btn-hero-primary svg { stroke: var(--navy); flex-shrink: 0; }
.btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(200,169,110,0.65); color: var(--navy); filter: brightness(1.08); }
.btn-hero-whatsapp {
    background: var(--green-wa); color: #FFFFFF;
    padding: 16px 30px; border-radius: var(--r-full);
    font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; gap: 10px;
    box-shadow: 0 8px 24px rgba(37,211,102,0.35); transition: var(--t);
}
.btn-hero-whatsapp svg { fill: #FFFFFF; flex-shrink: 0; }
.btn-hero-whatsapp:hover { background: var(--green-wa-h); transform: translateY(-3px); box-shadow: 0 14px 32px rgba(37,211,102,0.50); color: #FFFFFF; }
.btn-hero-glass {
    background: rgba(255,255,255,0.06); backdrop-filter: blur(14px);
    border: 1.5px solid rgba(200,169,110,0.35); color: var(--gold-light);
    padding: 16px 30px; border-radius: var(--r-full);
    font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; gap: 10px;
    transition: var(--t);
}
.btn-hero-glass svg { stroke: var(--gold-light); flex-shrink: 0; }
.btn-hero-glass:hover { background: rgba(200,169,110,0.14); border-color: var(--gold); color: var(--gold); transform: translateY(-3px); }
.hero-trust-bar {
    display: flex; align-items: center; gap: 36px;
    padding-top: 28px; border-top: 1px solid rgba(200,169,110,0.20); max-width: 600px;
}
.trust-metric-box { display: flex; flex-direction: column; gap: 4px; }
.metric-score-row { display: flex; align-items: center; gap: 9px; }
.metric-big-num { font-size: 24px; font-weight: 800; color: var(--white); line-height: 1.1; }
.metric-stars { color: var(--gold); font-size: 14px; letter-spacing: 2px; }
.metric-caption { font-size: 11px; color: var(--text-muted); font-weight: 500; }
.trust-divider { width: 1px; height: 38px; background: rgba(200,169,110,0.20); }

/* BOOKING SECTION */
.booking-standalone-section {
    background: var(--navy-mid); padding: 80px 5% 90px;
    position: relative; border-bottom: 1px solid var(--navy-border);
}
.booking-standalone-section::before {
    content: ''; position: absolute; top: 0; right: 0; width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(200,169,110,0.05) 0%, transparent 70%); pointer-events: none;
}
.booking-standalone-grid { display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 44px; align-items: start; }
.hero-pills-label { font-size: 10.5px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 10px; font-weight: 700; }
.hero-destination-pills { display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 26px; }
.dest-pill {
    background: rgba(255,255,255,0.04); border: 1px solid rgba(200,169,110,0.25);
    color: var(--text-light); padding: 6px 14px; border-radius: var(--r-full);
    font-size: 12px; font-weight: 600; cursor: pointer; transition: var(--t);
    display: inline-flex; align-items: center; gap: 5px;
}
.dest-pill:hover, .dest-pill.active { background: var(--gold); color: var(--navy); border-color: var(--gold); transform: translateY(-2px); box-shadow: 0 4px 14px var(--gold-glow); }
.hero-booking-card {
    background: var(--navy-card); border: 1px solid rgba(200,169,110,0.18);
    border-radius: var(--r-lg); padding: 38px 34px;
    box-shadow: var(--shadow-lg); position: relative; overflow: hidden;
}
.hero-booking-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--gold-dark), var(--gold-light), var(--gold-dark));
}
.booking-header { margin-bottom: 22px; }
.booking-kicker { font-size: 10.5px; font-weight: 800; letter-spacing: 1.5px; color: var(--gold); text-transform: uppercase; display: block; margin-bottom: 6px; }
.booking-header h2 { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; color: var(--white); }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; letter-spacing: 0.5px; text-transform: uppercase; }
.form-input, .form-select, .form-textarea {
    width: 100%; padding: 13px 16px; background: rgba(255,255,255,0.04);
    border: 1px solid rgba(200,169,110,0.20); border-radius: var(--r-sm);
    color: var(--white); font-size: 14px; transition: var(--t);
}
.form-input::placeholder { color: var(--text-muted); }
.form-select option { background: var(--navy-card); color: var(--white); }
.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none; border-color: var(--gold);
    background: rgba(200,169,110,0.07); box-shadow: 0 0 0 3px rgba(200,169,110,0.10);
}
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.booking-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 22px; }
.booking-actions .btn { width: 100%; padding: 15px; font-size: 15px; border-radius: var(--r-full); }
.booking-disclaimer { font-size: 11px; color: var(--text-muted); text-align: center; line-height: 1.4; margin-top: 12px; }
.booking-perk-card {
    background: var(--navy-surface); border: 1px solid rgba(200,169,110,0.18);
    border-radius: var(--r-lg); padding: 36px 30px; box-shadow: var(--shadow-md);
    position: relative; overflow: hidden;
}
.booking-perk-card::after {
    content: ''; position: absolute; bottom: -60px; right: -60px; width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(200,169,110,0.06) 0%, transparent 70%); pointer-events: none;
}
.perk-badge { display: inline-block; font-size: 10.5px; font-weight: 800; letter-spacing: 1.5px; color: var(--gold); text-transform: uppercase; margin-bottom: 10px; }
.booking-perk-card h3 { font-size: 22px; font-weight: 800; margin-bottom: 22px; letter-spacing: -0.4px; color: var(--white); }
.perk-list { list-style: none; display: flex; flex-direction: column; gap: 20px; margin-bottom: 30px; }
.perk-list li { display: flex; gap: 14px; align-items: flex-start; }
.perk-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.20); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.perk-icon svg { stroke: var(--gold); }
.perk-list strong { display: block; font-size: 14px; color: var(--white); font-weight: 700; margin-bottom: 3px; }
.perk-list p { font-size: 12px; color: var(--text-muted); line-height: 1.5; }
.booking-help-box { background: rgba(200,169,110,0.06); border: 1px solid rgba(200,169,110,0.20); border-radius: var(--r-sm); padding: 18px; text-align: center; }
.booking-help-box p { font-size: 12px; color: var(--text-muted); margin-bottom: 6px; }
.booking-direct-call { display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 19px; color: var(--gold); font-weight: 800; transition: var(--t); }
.booking-direct-call:hover { color: var(--gold-light); transform: scale(1.03); }

/* FEATURE STRIP */
.feature-strip { background: var(--navy-card); border-top: 1px solid var(--navy-border); border-bottom: 1px solid var(--navy-border); }
.strip-container { max-width: 1300px; margin: 0 auto; padding: 28px 5%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.strip-item { display: flex; align-items: center; gap: 16px; }
.strip-icon { width: 48px; height: 48px; background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.strip-icon svg { stroke: var(--gold); }
.strip-item strong { display: block; font-size: 14px; font-weight: 700; color: var(--white); }
.strip-item span { font-size: 12px; color: var(--text-muted); }

/* ABOUT */
.about-section { background: var(--navy); color: var(--white); position: relative; padding: 110px 5%; border-top: 1px solid var(--navy-border); border-bottom: 1px solid var(--navy-border); overflow: hidden; }
.about-section::before { content: ''; position: absolute; top: 0; left: 15%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(200,169,110,0.05) 0%, transparent 70%); pointer-events: none; z-index: 1; }
.about-grid { display: grid; grid-template-columns: 460px 1fr; gap: 70px; align-items: center; position: relative; z-index: 2; }
.about-visual-col { position: relative; }
.about-visual-card { position: relative; border-radius: 28px; overflow: hidden; background: var(--navy-card); box-shadow: 0 28px 70px rgba(0,0,0,0.70), 0 0 0 1px rgba(200,169,110,0.12); }
.about-image { width: 100%; height: auto; display: block; object-fit: cover; aspect-ratio: 4 / 5.2; transition: transform 0.7s var(--ease); }
.about-visual-card:hover .about-image { transform: scale(1.04); }
.about-floating-badge { position: absolute; top: 18px; left: 18px; right: 18px; background: rgba(10,14,26,0.92); backdrop-filter: blur(24px); border: 1px solid rgba(200,169,110,0.40); border-radius: 16px; padding: 15px 20px; display: flex; align-items: center; gap: 14px; box-shadow: 0 10px 40px rgba(0,0,0,0.60), inset 0 1px 0 rgba(255,255,255,0.06); z-index: 3; }
.badge-icon-box { width: 46px; height: 46px; border-radius: 12px; background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-light) 100%); color: var(--navy); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 18px var(--gold-glow); }
.badge-icon-box::after { content: ''; position: absolute; inset: 0; border-radius: 12px; background: linear-gradient(135deg, rgba(255,255,255,0.20) 0%, transparent 60%); pointer-events: none; }
.badge-icon-box svg { display: block; stroke: var(--navy); }
.badge-text { display: flex; flex-direction: column; min-width: 0; }
.badge-company { color: var(--white); font-size: 15px; font-weight: 700; line-height: 1.2; }
.badge-address { color: var(--gold); font-size: 11.5px; line-height: 1.4; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; }
.about-content-col { position: relative; }
.about-kicker { color: var(--gold); font-size: 11px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; display: inline-block; margin-bottom: 14px; }
.about-title { font-size: clamp(32px, 3.5vw, 46px); font-weight: 800; line-height: 1.12; color: var(--white); letter-spacing: -1px; margin-bottom: 24px; }
.about-text-body { margin-bottom: 30px; }
.about-text-body p { font-size: 15px; line-height: 1.70; color: var(--text-light); margin-bottom: 14px; }
.about-text-body p:last-child { margin-bottom: 0; }
.about-text-body strong { color: var(--white); font-weight: 700; }
.about-features-list { display: flex; flex-direction: column; gap: 20px; margin-bottom: 38px; }
.about-feature-item { display: flex; align-items: flex-start; gap: 16px; }
.feature-check-circle { width: 26px; height: 26px; border-radius: 50%; background: rgba(200,169,110,0.12); border: 1.5px solid var(--gold); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; }
.feature-check-circle svg { stroke: var(--gold); }
.feature-body strong { font-size: 15px; font-weight: 700; color: var(--white); display: block; margin-bottom: 3px; }
.feature-body p { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin: 0; }
.about-cta-row { margin-top: 4px; }
.btn-about-call { display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: transparent; color: var(--gold) !important; font-weight: 800; font-size: 15px; padding: 15px 34px; border-radius: var(--r-full); border: 1.5px solid var(--gold); transition: var(--t); cursor: pointer; }
.btn-about-call:hover { background: var(--gold); color: var(--navy) !important; transform: translateY(-2px); box-shadow: var(--shadow-gold); }
.about-stats-strip { margin-top: 70px; padding-top: 52px; border-top: 1px solid rgba(200,169,110,0.15); display: grid; grid-template-columns: repeat(4, 1fr); gap: 36px; position: relative; z-index: 2; }
.about-stat-box { display: flex; flex-direction: column; }
.about-stat-num { font-size: clamp(40px, 4.5vw, 60px); font-weight: 800; line-height: 1; background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-light) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: -1.5px; margin-bottom: 8px; }
.about-stat-label { font-size: 13.5px; color: var(--text-muted); font-weight: 500; line-height: 1.4; }

/* SECTIONS */
.section { padding: 90px 5%; }
.section-container { max-width: 1300px; margin: 0 auto; }
.section-heading-row { display: flex; justify-content: space-between; align-items: flex-end; gap: 32px; margin-bottom: 52px; }
.section-overline { font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.8px; color: var(--gold); display: block; margin-bottom: 10px; }
.section-title { font-size: clamp(28px, 3.2vw, 44px); font-weight: 800; line-height: 1.15; letter-spacing: -0.8px; color: var(--white); }
.section-desc { color: var(--text-muted); font-size: 15px; max-width: 440px; }

/* DESTINATIONS */
.destinations-section { background: var(--navy-mid); border-top: 1px solid var(--navy-border); border-bottom: 1px solid var(--navy-border); }
.cities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
.city-card { background: var(--navy-card); border: 1px solid var(--navy-border); border-radius: var(--r-md); padding: 26px; transition: var(--t); cursor: pointer; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; }
.city-card::before { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent); opacity: 0; transition: opacity 0.3s; }
.city-card:hover { transform: translateY(-5px); border-color: rgba(200,169,110,0.35); box-shadow: var(--shadow-md); }
.city-card:hover::before { opacity: 1; }
.city-badge-hub { display: inline-block; background: rgba(200,169,110,0.10); color: var(--gold); font-size: 9.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; padding: 3px 9px; border-radius: 4px; margin-bottom: 14px; }
.city-card h3 { font-size: 18px; font-weight: 800; margin-bottom: 8px; color: var(--white); }
.city-card p { font-size: 13px; color: var(--text-muted); margin-bottom: 18px; line-height: 1.55; }
.city-action { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--gold); }

/* SERVICES */
.services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.service-card { background: var(--navy-card); border: 1px solid var(--navy-border); border-radius: var(--r-md); padding: 32px 26px; display: flex; flex-direction: column; align-items: flex-start; position: relative; transition: var(--t); }
.service-card:hover { transform: translateY(-5px); border-color: rgba(200,169,110,0.30); box-shadow: var(--shadow-md); }
.service-num { position: absolute; top: 24px; right: 24px; font-size: 11px; font-weight: 800; color: var(--text-muted); opacity: 0.5; }
.service-icon { width: 52px; height: 52px; background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.25); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; }
.service-icon svg { stroke: var(--gold); }
.service-card h3 { font-size: 18px; font-weight: 800; letter-spacing: -0.3px; margin-bottom: 10px; color: var(--white); }
.service-card p { font-size: 13px; color: var(--text-muted); line-height: 1.65; margin-bottom: 24px; flex: 1; }
.service-link { font-size: 12px; font-weight: 700; color: var(--gold); display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.20); border-radius: var(--r-full); transition: var(--t); }
.service-card:hover .service-link { background: var(--gold); color: var(--navy); border-color: var(--gold); }

/* EXCURSIONS */
.excursions-section { background: var(--navy-card); color: var(--white); border-top: 1px solid var(--navy-border); border-bottom: 1px solid var(--navy-border); }
.excursions-section .section-desc { color: var(--text-muted); }
.excursion-showcase-row { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; margin-bottom: 52px; }
.excursion-text h3 { font-size: 34px; font-weight: 800; line-height: 1.12; letter-spacing: -0.8px; margin: 14px 0 20px; color: var(--white); }
.excursion-text p { font-size: 15px; color: var(--text-muted); line-height: 1.75; margin-bottom: 26px; }
.excursion-visual { position: relative; border-radius: var(--r-lg); overflow: hidden; height: 420px; box-shadow: var(--shadow-lg); border: 1px solid rgba(200,169,110,0.12); }
.excursion-visual img { width: 100%; height: 100%; object-fit: cover; }
.excursion-cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 52px; }
.excursion-card { background: var(--navy-surface); border: 1px solid var(--navy-border); border-radius: var(--r-md); padding: 30px; display: flex; flex-direction: column; transition: var(--t); }
.excursion-card:hover { border-color: rgba(200,169,110,0.30); transform: translateY(-4px); box-shadow: var(--shadow-md); }
.excursion-tag { font-size: 10.5px; text-transform: uppercase; letter-spacing: 1.2px; color: var(--gold); font-weight: 700; margin-bottom: 9px; }
.excursion-card h4 { font-size: 20px; font-weight: 800; margin-bottom: 11px; color: var(--white); }
.excursion-card p { font-size: 13px; color: var(--text-muted); line-height: 1.65; margin-bottom: 22px; flex: 1; }
.excursion-card button { background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.25); color: var(--gold); padding: 8px 16px; border-radius: var(--r-full); font-size: 12px; font-weight: 700; cursor: pointer; align-self: flex-start; transition: var(--t); }
.excursion-card button:hover { background: var(--gold); color: var(--navy); }
.excursion-form-card { background: var(--navy-surface); border: 1px solid rgba(200,169,110,0.15); border-radius: var(--r-lg); padding: 40px; display: grid; grid-template-columns: 0.85fr 1.15fr; gap: 44px; align-items: center; }
.excursion-points { list-style: none; margin-top: 22px; }
.excursion-points li { font-size: 13px; color: var(--text-light); padding: 9px 0; border-top: 1px solid var(--navy-border); display: flex; align-items: center; gap: 11px; }
.excursion-points li::before { content: '\\2713'; color: var(--gold); font-weight: 800; }

/* FLEET */
.fleet-section { background: var(--navy-mid); }
.fleet-tabs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: rgba(255,255,255,0.03); border: 1px solid var(--navy-border); padding: 6px; border-radius: var(--r-sm); margin-bottom: 26px; }
.fleet-tab-btn { background: transparent; border: none; padding: 15px 18px; border-radius: 6px; font-size: 14px; font-weight: 700; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: var(--t); }
.fleet-tab-btn.active { background: var(--navy-card); color: var(--gold); border: 1px solid rgba(200,169,110,0.20); box-shadow: var(--shadow-sm); }
.fleet-panel { display: none; background: var(--navy-card); border: 1px solid var(--navy-border); border-radius: var(--r-lg); overflow: hidden; grid-template-columns: 1.2fr 1fr; min-height: 460px; }
.fleet-panel.active { display: grid; }
.fleet-panel-visual { position: relative; background: var(--navy-surface); cursor: zoom-in; }
.fleet-panel-visual img { width: 100%; height: 100%; object-fit: cover; }
.fleet-zoom-badge { position: absolute; bottom: 20px; left: 20px; background: rgba(10,14,26,0.88); color: var(--white); padding: 6px 16px; border-radius: var(--r-full); font-size: 11px; font-weight: 700; border: 1px solid rgba(200,169,110,0.20); }
.fleet-panel-copy { padding: 44px; display: flex; flex-direction: column; justify-content: center; }
.fleet-panel-cat { font-size: 10.5px; font-weight: 800; letter-spacing: 1.8px; color: var(--gold); text-transform: uppercase; margin-bottom: 9px; }
.fleet-panel-copy h3 { font-size: 32px; font-weight: 800; margin-bottom: 13px; color: var(--white); }
.fleet-panel-heading { font-size: 16px; font-weight: 600; color: var(--text-light); margin-bottom: 15px; }
.fleet-panel-copy p { font-size: 14px; color: var(--text-muted); line-height: 1.75; margin-bottom: 26px; }
.fleet-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 30px; }
.fleet-tag { background: rgba(200,169,110,0.10); border: 1px solid rgba(200,169,110,0.20); padding: 6px 13px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--gold-light); }
.gallery-toggle-bar { margin-top: 38px; padding-top: 26px; border-top: 1px solid var(--navy-border); }
.fleet-gallery-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-top: 22px; }
.gallery-thumb { position: relative; border-radius: var(--r-sm); overflow: hidden; height: 145px; cursor: zoom-in; background: var(--navy-surface); border: 1px solid var(--navy-border); transition: var(--t); }
.gallery-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s; }
.gallery-thumb:hover { border-color: rgba(200,169,110,0.30); }
.gallery-thumb:hover img { transform: scale(1.09); }

/* STEPS */
.steps-section { background: var(--navy-mid); border-top: 1px solid var(--navy-border); border-bottom: 1px solid var(--navy-border); }
.steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
.step-card { background: var(--navy-card); border: 1px solid var(--navy-border); border-radius: var(--r-md); padding: 36px 28px; transition: var(--t); }
.step-card:hover { border-color: rgba(200,169,110,0.25); transform: translateY(-4px); box-shadow: var(--shadow-md); }
.step-number { width: 44px; height: 44px; background: linear-gradient(135deg, var(--gold-dark) 0%, var(--gold-light) 100%); color: var(--navy); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; margin-bottom: 22px; box-shadow: 0 4px 14px var(--gold-glow); }
.step-card h3 { font-size: 18px; font-weight: 800; margin-bottom: 11px; color: var(--white); }
.step-card p { font-size: 13px; color: var(--text-muted); line-height: 1.65; }

/* CONTACT CTA */
.contact-cta-section { background: var(--navy-card); color: var(--white); text-align: center; padding: 90px 5%; position: relative; overflow: hidden; border-top: 1px solid var(--navy-border); }
.contact-cta-section::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 700px; height: 700px; background: radial-gradient(circle, rgba(200,169,110,0.06) 0%, transparent 65%); pointer-events: none; }
.contact-cta-section h2 { font-size: clamp(32px, 4vw, 52px); font-weight: 800; letter-spacing: -1.5px; margin: 16px 0; position: relative; z-index: 2; }
.contact-cta-section p { font-size: 16px; color: var(--text-muted); max-width: 500px; margin: 0 auto 36px; position: relative; z-index: 2; }
.contact-actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 14px; margin-bottom: 28px; position: relative; z-index: 2; }
.direct-phone-large { font-size: 28px; font-weight: 800; color: var(--gold); display: inline-block; transition: var(--t); position: relative; z-index: 2; }
.direct-phone-large:hover { color: var(--white); transform: scale(1.05); }

/* FOOTER */
footer.site-footer { background: #060810; color: var(--text-muted); padding: 64px 5% 40px; border-top: 1px solid var(--navy-border); font-size: 13px; }
.footer-container { max-width: 1300px; margin: 0 auto; display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 44px; }
.footer-col h4 { color: var(--white); font-size: 13px; font-weight: 700; margin-bottom: 18px; }
.footer-links { list-style: none; }
.footer-links li { margin-bottom: 11px; }
.footer-links a:hover { color: var(--gold); }
.footer-bottom { max-width: 1300px; margin: 0 auto; padding-top: 26px; border-top: 1px solid var(--navy-border); display: flex; justify-content: space-between; align-items: center; font-size: 12px; }

/* MOBILE STICKY */
.mobile-sticky-bar { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: rgba(10,14,26,0.97); backdrop-filter: blur(12px); border-top: 1px solid rgba(200,169,110,0.15); padding: 10px 16px calc(10px + env(safe-area-inset-bottom)); z-index: 1000; grid-template-columns: 1fr 1.2fr; gap: 10px; }
.mobile-sticky-bar .btn { padding: 12px; font-size: 13px; border-radius: var(--r-full); }

/* LIGHTBOX */
.photo-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.93); z-index: 2000; align-items: center; justify-content: center; padding: 24px; }
.photo-lightbox.active { display: flex; }
.lightbox-content { max-width: 1000px; width: 100%; position: relative; text-align: center; }
.lightbox-content img { max-height: 78vh; margin: 0 auto; border-radius: var(--r-md); box-shadow: var(--shadow-lg); }
.lightbox-caption { color: var(--white); margin-top: 16px; font-size: 15px; font-weight: 600; }
.lightbox-close { position: absolute; top: -44px; right: 0; background: transparent; border: none; color: var(--white); font-size: 32px; cursor: pointer; opacity: 0.7; transition: opacity 0.2s; }
.lightbox-close:hover { opacity: 1; }

/* SUCCESS MODAL */
.success-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 2000; align-items: center; justify-content: center; padding: 20px; }
.success-modal.active { display: flex; }
.success-card { background: var(--navy-card); border: 1px solid rgba(200,169,110,0.20); border-radius: var(--r-lg); padding: 40px 34px; max-width: 480px; width: 100%; text-align: center; box-shadow: var(--shadow-lg); animation: popIn 0.35s var(--ease); }
@keyframes popIn { from { transform: scale(0.88); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.success-icon { width: 68px; height: 68px; background: rgba(16,185,129,0.12); border: 1.5px solid rgba(16,185,129,0.30); color: var(--emerald); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px; }
.success-card h3 { font-size: 22px; font-weight: 800; margin-bottom: 9px; color: var(--white); }
.success-card p { font-size: 14px; color: var(--text-muted); margin-bottom: 22px; }
.booking-ref-box { background: rgba(200,169,110,0.10); border: 1px dashed rgba(200,169,110,0.40); border-radius: var(--r-sm); padding: 16px; margin-bottom: 26px; }
.booking-ref-box small { display: block; font-size: 11px; color: var(--text-muted); text-transform: uppercase; }
.booking-ref-box strong { font-size: 20px; color: var(--gold); font-family: monospace; }

/* SCROLL ANIMATIONS */
html { scroll-behavior: smooth !important; }
.scroll-reveal { opacity: 0; transition: opacity 0.75s var(--ease), transform 0.75s var(--ease); will-change: opacity, transform; }
.reveal-fade-up    { transform: translateY(44px) scale(0.98); }
.reveal-fade-left  { transform: translateX(48px) scale(0.98); }
.reveal-fade-right { transform: translateX(-48px) scale(0.98); }
.scroll-reveal.revealed { opacity: 1 !important; transform: translate3d(0,0,0) scale(1) !important; }

/* RESPONSIVE */
@media (max-width: 1080px) {
    .booking-standalone-grid { grid-template-columns: 1fr; gap: 38px; }
    .hero-main-column { max-width: 100%; }
    .services-grid { grid-template-columns: repeat(2, 1fr); }
    .fleet-gallery-grid { grid-template-columns: repeat(3, 1fr); }
    .footer-container { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 820px) {
    .nav-menu { display: none; }
    .mobile-nav-toggle { display: block; }
    .header-lang-picker { display: none; }
    .strip-container { grid-template-columns: repeat(2, 1fr); gap: 18px; }
    .excursion-showcase-row, .excursion-form-card, .fleet-panel { grid-template-columns: 1fr; }
    .fleet-panel-visual { height: 290px; }
    .excursion-cards-grid, .steps-grid { grid-template-columns: 1fr; }
    .about-grid { grid-template-columns: 1fr; gap: 44px; }
    .about-visual-card { max-width: 490px; margin: 0 auto; }
    .about-stats-strip { grid-template-columns: repeat(2, 1fr); gap: 28px; margin-top: 44px; padding-top: 36px; }
}
@media (max-width: 640px) {
    html { scroll-padding-top: 78px; }
    .top-notice-bar { display: none; }
    .about-section { padding: 64px 5%; }
    .about-title { font-size: 28px; }
    .about-floating-badge { padding: 11px 14px; gap: 11px; top: 12px; left: 12px; right: 12px; }
    .badge-icon-box { width: 38px; height: 38px; }
    .badge-company { font-size: 13.5px; }
    .badge-address { font-size: 11px; }
    .btn-about-call { width: 100%; padding: 14px 20px; }
    .about-stats-strip { grid-template-columns: repeat(2, 1fr); gap: 22px; }
    .hero-fullscreen-section { padding: 52px 5% 60px; min-height: auto; background-position: center right 30%; }
    .hero-bg-overlay { background: linear-gradient(180deg, rgba(10,14,26,0.94) 0%, rgba(10,14,26,0.85) 50%, rgba(10,14,26,0.97) 100%); }
    .hero-status-pill { font-size: 11px; padding: 7px 14px; }
    .hero-giant-title { font-size: 36px; letter-spacing: -1.2px; }
    .hero-description-text { font-size: 14px; margin-bottom: 28px; }
    .hero-actions-row { flex-direction: column; align-items: stretch; gap: 10px; margin-bottom: 34px; }
    .hero-actions-row .btn-hero-primary,
    .hero-actions-row .btn-hero-whatsapp,
    .hero-actions-row .btn-hero-glass { justify-content: center; width: 100%; padding: 14px 20px; }
    .hero-trust-bar { flex-wrap: wrap; gap: 22px; }
    .trust-divider { display: none; }
    .booking-standalone-section { padding: 44px 5% 56px; }
    .hero-booking-card { padding: 26px 20px; }
    .form-row-2 { grid-template-columns: 1fr; }
    .strip-container { grid-template-columns: 1fr; }
    .services-grid { grid-template-columns: 1fr; }
    .fleet-gallery-grid { grid-template-columns: repeat(2, 1fr); }
    .fleet-tabs { grid-template-columns: 1fr; }
    .footer-container { grid-template-columns: 1fr; }
    .mobile-sticky-bar { display: grid; }
    body { padding-bottom: 72px; }
}
@media (prefers-reduced-motion: reduce) {
    .scroll-reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
}
"""

with open("assets/css/style.css", "w", encoding="utf-8") as f:
    f.write(css)

print("CSS written:", len(css), "bytes")
