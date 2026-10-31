/**
 * Admin JavaScript Interactivity - MY TAXI
 */

document.addEventListener('DOMContentLoaded', () => {
    initTabs();
    initFilters();
    initSearch();
});

// Toast notification
function showToast(message, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.style.borderColor = isError ? '#ef4444' : '#eb8125';
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3500);
}

// Navigation Tabs
function initTabs() {
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    const panes = document.querySelectorAll('.tab-pane');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = item.getAttribute('data-tab');

            navItems.forEach(i => i.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));

            item.classList.add('active');
            const targetPane = document.getElementById(targetId);
            if (targetPane) targetPane.classList.add('active');
        });
    });
}

// Filter Bookings by Status
function initFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('#bookingsTable tbody tr');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const status = btn.getAttribute('data-status');
            applyFilters(status, document.getElementById('bookingSearch').value.toLowerCase());
        });
    });

    // KPI cards click to filter
    const kpiCards = document.querySelectorAll('.kpi-card');
    kpiCards.forEach(card => {
        card.addEventListener('click', () => {
            const filter = card.getAttribute('data-filter');
            const targetBtn = document.querySelector(`.filter-btn[data-status="${filter}"]`);
            if (targetBtn) {
                targetBtn.click();
            }
        });
    });
}

// Search Filter
function initSearch() {
    const searchInput = document.getElementById('bookingSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-status');
        applyFilters(activeFilter, query);
    });
}

function applyFilters(status, query) {
    const rows = document.querySelectorAll('#bookingsTable tbody tr');
    rows.forEach(row => {
        if (row.classList.contains('empty-state')) return;

        const rowStatus = row.getAttribute('data-status');
        const text = row.innerText.toLowerCase();

        const matchStatus = (status === 'all' || rowStatus === status);
        const matchQuery = (!query || text.includes(query));

        if (matchStatus && matchQuery) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Update Booking Status via AJAX
async function updateBookingStatus(id, newStatus) {
    try {
        const formData = new FormData();
        formData.append('action', 'update_status');
        formData.append('id', id);
        formData.append('status', newStatus);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            showToast('Statut mis à jour : ' + newStatus);
            const row = document.getElementById(`row-booking-${id}`);
            if (row) {
                row.setAttribute('data-status', newStatus);
                const select = row.querySelector('.status-select');
                if (select) {
                    select.className = `status-select status-${newStatus}`;
                }
            }
        } else {
            showToast(data.message || 'Erreur lors de la mise à jour', true);
        }
    } catch (err) {
        showToast('Erreur de connexion', true);
    }
}

// Assign Driver
async function assignDriver(id, driverName) {
    try {
        const formData = new FormData();
        formData.append('action', 'assign_driver');
        formData.append('id', id);
        formData.append('driver', driverName);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            showToast('Chauffeur assigné avec succès !');
        }
    } catch (err) {
        showToast('Erreur serveur', true);
    }
}

// Delete Booking
async function deleteBooking(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement cette réservation ?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'delete_booking');
        formData.append('id', id);

        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            const row = document.getElementById(`row-booking-${id}`);
            if (row) row.remove();
            showToast('Course supprimée');
        } else {
            showToast(data.message, true);
        }
    } catch (err) {
        showToast('Erreur de connexion', true);
    }
}

// View Details Modal
function viewBookingDetails(b) {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailContent');
    document.getElementById('detailTitle').textContent = `Fiche de Course · ${b.ref_code}`;

    const vehName = {
        'sedan': 'Berline Mercedes E-Class',
        'van': 'Van Mercedes V-Class (jusqu\'à 7 pers.)',
        'minibus': 'Minibus VIP (jusqu\'à 16 pers.)'
    }[b.vehicle_type] || b.vehicle_type;

    content.innerHTML = `
        <div class="detail-item">
            <div class="detail-label">Référence Course</div>
            <div class="detail-value"><strong style="color: #eb8125; font-size: 16px;">${b.ref_code}</strong> (Enregistrée le ${b.created_at})</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Client</div>
            <div class="detail-value"><strong>${b.customer_name}</strong></div>
            <div style="margin-top: 4px;">📞 <a href="tel:${b.customer_phone}" style="color: #60a5fa;">${b.customer_phone}</a> ${b.customer_email ? '· ✉️ ' + b.customer_email : ''}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Date & Heure de Prise en charge</div>
            <div class="detail-value">📅 <strong>${b.ride_date}</strong> à ⏰ <strong>${b.ride_time}</strong></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Itinéraire</div>
            <div class="detail-value">
                <div>📍 <strong>Départ :</strong> ${b.pickup_location}</div>
                <div style="margin-top: 4px;">🎯 <strong>Arrivée :</strong> ${b.destination}</div>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Véhicule & Capacité</div>
            <div class="detail-value">🚘 ${vehName} · 👥 ${b.passengers} passager(s) · 🧳 ${b.luggage} bagage(s)</div>
        </div>
        ${b.flight_number ? `
        <div class="detail-item">
            <div class="detail-label">Numéro de Vol ou Train</div>
            <div class="detail-value">✈️ ${b.flight_number}</div>
        </div>` : ''}
        <div class="detail-item">
            <div class="detail-label">Remarques / Besoins spécifiques</div>
            <div class="detail-value">${b.notes ? b.notes : '<em>Aucune remarque spécifique</em>'}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Chauffeur Assigné</div>
            <div class="detail-value">${b.driver_assigned ? b.driver_assigned : '<em>Non encore assigné</em>'}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Statut Actuel</div>
            <div class="detail-value"><strong>${b.status.toUpperCase()}</strong></div>
        </div>
    `;

    modal.classList.add('show');
}

function closeDetailsModal() {
    document.getElementById('detailsModal').classList.remove('show');
}

// Manual Booking Modal
function openManualBookingModal() {
    document.getElementById('manualModal').classList.add('show');
}

function closeManualBookingModal() {
    document.getElementById('manualModal').classList.remove('show');
}

async function submitManualBooking(e) {
    e.preventDefault();
    const form = document.getElementById('manualBookingForm');
    const formData = new FormData(form);
    formData.append('action', 'add_manual_booking');

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.success) {
            showToast(data.message);
            closeManualBookingModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(data.message, true);
        }
    } catch (err) {
        showToast('Erreur serveur', true);
    }
}

// Save Settings
async function saveSettings(e) {
    e.preventDefault();
    const form = document.getElementById('settingsForm');
    const formData = new FormData(form);
    formData.append('action', 'save_settings');

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        showToast(data.message, !data.success);
    } catch (err) {
        showToast('Erreur serveur', true);
    }
}

// Change Password
async function changePassword(e) {
    e.preventDefault();
    const form = document.getElementById('passwordForm');
    const formData = new FormData(form);
    formData.append('action', 'update_password');

    try {
        const res = await fetch('api.php', { method: 'POST', body: formData });
        const data = await res.json();
        showToast(data.message, !data.success);
        if (data.success) form.reset();
    } catch (err) {
        showToast('Erreur serveur', true);
    }
}
