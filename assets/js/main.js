/**
 * MY TAXI - Interactions JavaScript
 * Gestion des réservations, intégration WhatsApp, tabs flotte et galerie lightbox
 */
document.addEventListener('DOMContentLoaded', () => {
    initDatePicker();
    initDestinationPills();
    initBookingForm();
    initExcursions();
    initFleetTabs();
    initGalleryLightbox();
    initMobileNav();
    initLanguagePicker();
    initScrollAnimations();
});

// 1. Initialiser le sélecteur de date (date minimum = aujourd'hui)
function initDatePicker() {
    const dateInput = document.getElementById('rideDate');
    const excursionDate = document.getElementById('excursionDate');
    const today = new Date().toISOString().split('T')[0];

    if (dateInput) {
        dateInput.min = today;
        if (!dateInput.value) {
            dateInput.value = today;
        }
    }

    if (excursionDate) {
        excursionDate.min = today;
    }
}

// 2. Remplissage rapide par clic sur les badges de ville
function initDestinationPills() {
    const pills = document.querySelectorAll('[data-dest]');
    const destInput = document.getElementById('destinationInput');
    const pickupInput = document.getElementById('pickupInput');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            const destCity = pill.getAttribute('data-dest');
            if (destInput) {
                destInput.value = destCity;
                destInput.classList.add('highlight-glow');
                setTimeout(() => destInput.classList.remove('highlight-glow'), 1200);

                // Défilement doux vers le formulaire
                const bookingForm = document.getElementById('bookingForm');
                if (bookingForm) {
                    bookingForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });

    const pickupPills = document.querySelectorAll('[data-pickup]');
    pickupPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const pickupCity = pill.getAttribute('data-pickup');
            if (pickupInput) {
                pickupInput.value = pickupCity;
            }
        });
    });
}

// 3. Soumission et gestion du Formulaire de Réservation
function initBookingForm() {
    const form = document.getElementById('bookingForm');
    const btnWhatsApp = document.getElementById('btnSubmitWhatsApp');

    if (!form) return;

    // A) Envoi normal avec enregistrement en base SQL
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Enregistrement de votre course...';

        const formData = new FormData(form);
        formData.append('action', 'book');

        try {
            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showSuccessModal(result);
                form.reset();
                initDatePicker();
            } else {
                alert(result.message || 'Une erreur est survenue lors de la réservation.');
            }
        } catch (error) {
            alert('Erreur de connexion au serveur. Vous pouvez réserver directement par téléphone ou WhatsApp.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    // B) Réservation directe via WhatsApp
    if (btnWhatsApp) {
        btnWhatsApp.addEventListener('click', () => {
            const pickup = document.getElementById('pickupInput').value.trim() || 'Aigle';
            const dest = document.getElementById('destinationInput').value.trim() || 'À convenir';
            const date = document.getElementById('rideDate').value;
            const time = document.getElementById('rideTime').value || 'Dès que possible';
            const vehicle = document.getElementById('vehicleSelect').value;
            const name = document.getElementById('customerName').value.trim();
            const phone = document.getElementById('customerPhone').value.trim();
            const notes = document.getElementById('rideNotes') ? document.getElementById('rideNotes').value.trim() : '';

            const dateFormatted = date ? date.split('-').reverse().join('.') : 'Aujourd\'hui';

            let msg = `Bonjour MY TAXI,\n\nJe souhaite réserver une course :\n\n`
                + `🚖 Prise en charge : ${pickup}\n`
                + `🎯 Destination : ${dest}\n`
                + `📅 Date : ${dateFormatted}\n`
                + `⏰ Heure : ${time}\n`
                + `🚘 Véhicule : ${vehicle}\n`;

            if (name) msg += `👤 Nom : ${name}\n`;
            if (phone) msg += `📞 Téléphone : ${phone}\n`;
            if (notes) msg += `📝 Remarques : ${notes}\n`;

            msg += `\nPouvez-vous me confirmer le tarif et la disponibilité ? Merci !`;

            const waNumber = '41798456270';
            window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`, '_blank');
        });
    }
}

// Affichage Modal Succès
function showSuccessModal(data) {
    const modal = document.getElementById('bookingSuccessModal');
    if (!modal) return;

    document.getElementById('modalRefCode').textContent = data.ref_code;
    
    const waBtn = document.getElementById('modalWhatsAppBtn');
    if (waBtn && data.whatsapp_url) {
        waBtn.href = data.whatsapp_url;
    }

    modal.classList.add('active');
}

function closeSuccessModal() {
    const modal = document.getElementById('bookingSuccessModal');
    if (modal) modal.classList.remove('active');
}

// 4. Section Excursions
function initExcursions() {
    const ideaButtons = document.querySelectorAll('[data-excursion-idea]');
    const textarea = document.getElementById('excursionIdea');
    const excursionForm = document.getElementById('excursionForm');

    ideaButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const idea = btn.getAttribute('data-excursion-idea');
            if (textarea) {
                textarea.value = idea;
                excursionForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                textarea.focus();
            }
        });
    });

    if (excursionForm) {
        excursionForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(excursionForm);
            formData.append('action', 'excursion');

            try {
                const res = await fetch('api.php', { method: 'POST', body: formData });
                const result = await res.json();
                if (result.success) {
                    alert(result.message);
                    if (result.whatsapp_url) {
                        window.open(result.whatsapp_url, '_blank');
                    }
                    excursionForm.reset();
                } else {
                    alert(result.message || 'Erreur lors de la demande.');
                }
            } catch (err) {
                alert('Erreur réseau. Veuillez nous contacter au 079 845 62 70.');
            }
        });
    }
}

// 5. Onglets de la Flotte de véhicules
function initFleetTabs() {
    const tabButtons = document.querySelectorAll('.fleet-tab-btn');
    const panels = document.querySelectorAll('.fleet-panel');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const targetPanelId = btn.getAttribute('data-panel');
            const targetPanel = document.getElementById(targetPanelId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });
}

// 6. Galerie Lightbox pour les photos de véhicules
function initGalleryLightbox() {
    const lightbox = document.getElementById('photoLightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const closeBtn = document.getElementById('lightboxClose');

    const triggers = document.querySelectorAll('[data-lightbox-photo]');

    triggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const src = trigger.getAttribute('data-lightbox-photo');
            const caption = trigger.getAttribute('data-lightbox-caption') || 'Véhicule MY TAXI';

            lightboxImg.src = src;
            lightboxCaption.textContent = caption;
            lightbox.classList.add('active');
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            lightbox.classList.remove('active');
        });
    }

    if (lightbox) {
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
            }
        });
    }
}

// 7. Menu Mobile
function initMobileNav() {
    const toggle = document.querySelector('.mobile-nav-toggle');
    const menu = document.querySelector('.nav-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            menu.classList.toggle('mobile-open');
            toggle.textContent = menu.classList.contains('mobile-open') ? '✕' : '☰';
        });

        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.remove('mobile-open');
                toggle.textContent = '☰';
            });
        });
    }
}

// 8. Sélecteur de Langue Dynamique (FR / EN / RU)
function initLanguagePicker() {
    const langButtons = document.querySelectorAll('.lang-choice');
    const currentLang = localStorage.getItem('mytaxi_lang') || 'FR';

    // Appliquer la langue au chargement
    setLanguage(currentLang);

    langButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const chosenLang = btn.getAttribute('data-lang') || 'FR';
            setLanguage(chosenLang);
        });
    });
}

function setLanguage(lang) {
    if (!window.siteTranslations || !window.siteTranslations[lang]) return;

    localStorage.setItem('mytaxi_lang', lang);
    document.documentElement.lang = lang.toLowerCase();

    // Mettre à jour les boutons actifs
    document.querySelectorAll('.lang-choice').forEach(b => {
        if (b.getAttribute('data-lang') === lang) {
            b.classList.add('active');
        } else {
            b.classList.remove('active');
        }
    });

    const dict = window.siteTranslations[lang];

    // Traduction des textes normaux et HTML
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[key] !== undefined) {
            if (el.getAttribute('data-i18n-html') === 'true') {
                el.innerHTML = dict[key];
            } else {
                el.textContent = dict[key];
            }
        }
    });

    // Traduction des placeholders
    document.querySelectorAll('[data-i18n-ph]').forEach(el => {
        const key = el.getAttribute('data-i18n-ph');
        if (dict[key] !== undefined) {
            el.setAttribute('placeholder', dict[key]);
        }
    });
}
// 9. Animations au défilement (Scroll Reveal avec Intersection Observer)
function initScrollAnimations() {
    // Ajouter les attributs data-animate sur les éléments à animer
    const animTargets = [
        { selector: '.section-heading-row, .section-overline, .section-title, .section-desc', animation: 'fade-up', delay: 0 },
        { selector: '.about-kicker, .about-title, .about-text-body', animation: 'fade-up', delay: 0 },
        { selector: '.about-features-list .about-feature-item', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.about-cta-row', animation: 'fade-up', delay: 200 },
        { selector: '.about-visual-col', animation: 'fade-left', delay: 0 },
        { selector: '.about-stat-box', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.strip-item', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.city-card', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.service-card', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.fleet-panel', animation: 'fade-up', delay: 0 },
        { selector: '.fleet-tab-btn', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.excursion-card', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.excursion-showcase-row', animation: 'fade-up', delay: 0 },
        { selector: '.step-item', animation: 'fade-up', delay: 0, stagger: true },
        { selector: '.contact-cta-section h2, .contact-cta-section p', animation: 'fade-up', delay: 0 },
        { selector: '.contact-actions', animation: 'fade-up', delay: 150 },
        { selector: '.booking-perk-card', animation: 'fade-right', delay: 0 },
        { selector: '.hero-booking-card', animation: 'fade-up', delay: 0 },
        { selector: '.perk-list li', animation: 'fade-up', delay: 0, stagger: true },
    ];

    // Appliquer les classes initiales
    animTargets.forEach(({ selector, animation, delay, stagger }) => {
        const elements = document.querySelectorAll(selector);
        elements.forEach((el, i) => {
            el.classList.add('scroll-reveal', `reveal-${animation}`);
            if (stagger) {
                el.style.transitionDelay = `${i * 80}ms`;
            } else if (delay) {
                el.style.transitionDelay = `${delay}ms`;
            }
        });
    });

    // Observer bidirectionnel : anime quand on descend ET quand on remonte
    let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;

    const observer = new IntersectionObserver((entries) => {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        const scrollingDown = currentScroll >= lastScrollY;
        lastScrollY = currentScroll;

        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Éléments entrent dans l'écran : jouer l'animation
                entry.target.classList.add('revealed');
            } else {
                // Éléments sortent de l'écran : réinitialiser pour rejouer au prochain passage
                const rect = entry.target.getBoundingClientRect();
                // Si l'élément sort par le bas (en remontant) ou par le haut (en descendant loin)
                if (rect.top > window.innerHeight * 0.95 || rect.bottom < -50) {
                    entry.target.classList.remove('revealed');
                }
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

    // Effet sur la barre de navigation au scroll + mise en avant du lien actif
    const header = document.querySelector('header.site-header');
    const navLinks = document.querySelectorAll('.nav-menu a[href^="#"]');
    const sections = Array.from(navLinks).map(link => {
        const id = link.getAttribute('href').substring(1);
        return document.getElementById(id);
    }).filter(Boolean);

    window.addEventListener('scroll', () => {
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;

        // Ombre et fond de la barre de navigation
        if (header) {
            if (scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        // Détection de la section active pour le lien du menu
        let currentSectionId = '';
        sections.forEach(sec => {
            const top = sec.offsetTop - 120;
            const height = sec.offsetHeight;
            if (scrollY >= top && scrollY < top + height) {
                currentSectionId = sec.id;
            }
        });

        navLinks.forEach(link => {
            if (link.getAttribute('href') === `#${currentSectionId}`) {
                link.classList.add('nav-active');
            } else {
                link.classList.remove('nav-active');
            }
        });
    }, { passive: true });
}
