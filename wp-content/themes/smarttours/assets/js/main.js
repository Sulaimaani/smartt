/**
 * Canada Smart Tours (smarttours.ca) — Main Frontend JavaScript
 * Modern Vanilla JS (No jQuery dependency, zero console errors)
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // -------------------------------------------------------------
    // 1. Toast Notification System
    // -------------------------------------------------------------
    const toastContainer = document.getElementById('st-toast-container');

    function showToast(message, linkUrl = null, linkText = 'View My Trip &rarr;') {
        if (!toastContainer) return;
        const toast = document.createElement('div');
        toast.className = 'st-toast';
        toast.innerHTML = `
            <div class="st-toast__content">
                <span>${message}</span>
                ${linkUrl ? `<a href="${linkUrl}" class="st-toast__link">${linkText}</a>` : ''}
            </div>
            <button type="button" class="st-toast__close" aria-label="Close notification">&times;</button>
        `;

        toastContainer.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('is-visible'));

        const closeBtn = toast.querySelector('.st-toast__close');
        closeBtn.addEventListener('click', () => removeToast(toast));

        setTimeout(() => removeToast(toast), 4000);
    }

    function removeToast(toast) {
        toast.classList.remove('is-visible');
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }

    // -------------------------------------------------------------
    // 2. Dark Mode Management
    // -------------------------------------------------------------
    const THEME_KEY = 'smarttours_theme';

    function initTheme() {
        try {
            const savedTheme = localStorage.getItem(THEME_KEY);
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        } catch (e) {}
    }

    function toggleTheme() {
        try {
            const current = document.documentElement.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem(THEME_KEY, newTheme);
        } catch (e) {}
    }

    initTheme();
    document.querySelectorAll('.js-toggle-theme').forEach(btn => {
        btn.addEventListener('click', toggleTheme);
    });

    // -------------------------------------------------------------
    // 3. Header Live Search Suggestions (Desktop & Mobile)
    // -------------------------------------------------------------
    function setupLiveSearch(inputSelector, resultsSelector) {
        const input = document.querySelector(inputSelector);
        const resultsBox = document.querySelector(resultsSelector);
        if (!input || !resultsBox) return;

        let debounceTimer = null;

        input.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < 2) {
                resultsBox.style.display = 'none';
                resultsBox.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`${smarttours_vars.ajax_url}?action=smarttours_live_search&q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data) {
                            renderLiveSuggestions(res.data, resultsBox, query);
                        }
                    })
                    .catch(() => {});
            }, 220);
        });

        // Close on escape or outside click
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.style.display = 'none';
            }
        });
    }

    function renderLiveSuggestions(data, container, query) {
        const hasCats = data.categories && data.categories.length > 0;
        const hasExps = data.experiences && data.experiences.length > 0;

        if (!hasCats && !hasExps) {
            container.innerHTML = `<p style="padding:10px; font-size:0.875rem; color:var(--st-muted);">No suggestions found for "${query}"</p>`;
            container.style.display = 'block';
            return;
        }

        let html = '';

        if (hasCats) {
            html += `<div class="st-live-search-section-title">Categories</div><div class="st-live-cat-list">`;
            data.categories.forEach(cat => {
                html += `<a href="${cat.url}" class="st-live-cat-chip">${cat.name} <small>(${cat.count})</small></a>`;
            });
            html += `</div>`;
        }

        if (hasExps) {
            html += `<div class="st-live-search-section-title">Experiences</div><div class="st-live-exp-list">`;
            data.experiences.forEach(exp => {
                html += `
                    <a href="${exp.url}" class="st-live-exp-item">
                        <img src="${exp.image}" alt="${exp.title}" class="st-live-exp-thumb" />
                        <div class="st-live-exp-info">
                            <div class="st-live-exp-title">${exp.title}</div>
                            <div class="st-live-exp-meta">📍 ${exp.city} &bull; ★ ${exp.rating} &bull; <strong>${exp.price}</strong></div>
                        </div>
                    </a>
                `;
            });
            html += `</div>`;
        }

        container.innerHTML = html;
        container.style.display = 'block';
    }

    setupLiveSearch('.js-live-search-input', '.js-live-search-results');
    setupLiveSearch('.js-mobile-search-input', '.js-mobile-live-results');

    // Mobile Search Bar Toggle
    const mobileSearchBtn = document.querySelector('.js-toggle-mobile-search');
    const mobileSearchBar = document.querySelector('.js-mobile-search-bar');
    if (mobileSearchBtn && mobileSearchBar) {
        mobileSearchBtn.addEventListener('click', function () {
            mobileSearchBar.classList.toggle('is-open');
            if (mobileSearchBar.classList.contains('is-open')) {
                const input = mobileSearchBar.querySelector('input');
                if (input) setTimeout(() => input.focus(), 150);
            }
        });
    }

    // -------------------------------------------------------------
    // 4. Mobile Drawer & Accordion Navigation
    // -------------------------------------------------------------
    const hamburger = document.querySelector('.js-hamburger');
    const drawer = document.querySelector('.js-mobile-drawer');
    const overlay = document.querySelector('.js-drawer-overlay');
    const closeDrawerBtn = document.querySelector('.js-close-drawer');

    function openDrawer() {
        if (drawer && overlay) {
            drawer.classList.add('is-open');
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDrawer() {
        if (drawer && overlay) {
            drawer.classList.remove('is-open');
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    }

    if (hamburger) hamburger.addEventListener('click', openDrawer);
    if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);

    // Accordions
    document.querySelectorAll('.js-accordion-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const item = this.closest('.st-accordion-item');
            const panel = item ? item.querySelector('.st-accordion-panel') : null;
            if (!panel) return;

            const isOpen = panel.classList.contains('is-open');
            document.querySelectorAll('.st-accordion-panel').forEach(p => p.classList.remove('is-open'));
            document.querySelectorAll('.js-accordion-toggle').forEach(b => b.setAttribute('aria-expanded', 'false'));

            if (!isOpen) {
                panel.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // -------------------------------------------------------------
    // 5. "My Trip" (Trip Builder) localStorage Integration
    // -------------------------------------------------------------
    const TRIP_STORAGE_KEY = 'smarttours_my_trip';
    const TRIP_NOTES_KEY = 'smarttours_my_trip_notes';

    function getTripIds() {
        try {
            const data = localStorage.getItem(TRIP_STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            return [];
        }
    }

    function saveTripIds(ids) {
        try {
            localStorage.setItem(TRIP_STORAGE_KEY, JSON.stringify(ids));
            updateTripUI();
        } catch (e) {}
    }

    function getTripNotes() {
        try {
            const data = localStorage.getItem(TRIP_NOTES_KEY);
            return data ? JSON.parse(data) : {};
        } catch (e) {
            return {};
        }
    }

    function saveTripNotes(notes) {
        try {
            localStorage.setItem(TRIP_NOTES_KEY, JSON.stringify(notes));
        } catch (e) {}
    }

    function updateTripUI() {
        const ids = getTripIds();
        document.querySelectorAll('.st-trip-count').forEach(badge => {
            badge.textContent = ids.length;
        });

        // Toggle heart buttons states across the DOM
        document.querySelectorAll('.js-toggle-trip').forEach(btn => {
            const id = parseInt(btn.dataset.id, 10);
            if (ids.includes(id)) {
                btn.classList.add('is-saved');
                btn.setAttribute('aria-pressed', 'true');
                btn.setAttribute('title', 'Remove from My Trip');
                const label = btn.querySelector('span');
                if (label && label.textContent === 'Save to Trip') {
                    label.textContent = 'Saved in Trip';
                }
            } else {
                btn.classList.remove('is-saved');
                btn.setAttribute('aria-pressed', 'false');
                btn.setAttribute('title', 'Save to My Trip');
                const label = btn.querySelector('span');
                if (label && label.textContent === 'Saved in Trip') {
                    label.textContent = 'Save to Trip';
                }
            }
        });

        const statCount = document.querySelector('.js-stat-count');
        if (statCount) statCount.textContent = ids.length;
    }

    // Toggle heart click event delegation
    document.addEventListener('click', function (e) {
        const heartBtn = e.target.closest('.js-toggle-trip');
        if (!heartBtn) return;
        e.preventDefault();
        e.stopPropagation();

        const id = parseInt(heartBtn.dataset.id, 10);
        if (!id) return;

        let ids = getTripIds();
        if (ids.includes(id)) {
            ids = ids.filter(item => item !== id);
            heartBtn.classList.remove('is-saved');
            saveTripIds(ids);
            showToast('Removed from your trip planner.');
        } else {
            ids.push(id);
            heartBtn.classList.add('is-saved');
            saveTripIds(ids);
            const tripUrl = smarttours_vars.my_trip_url || '/my-trip/';
            showToast('Added to My Trip &bull; <a href="' + tripUrl + '" style="color:#4ade80;font-weight:700;">View</a>');
        }

        // Refresh My Trip page if active
        if (document.querySelector('.st-my-trip-page') && !document.querySelector('.st-my-trip-page').dataset.isShared) {
            loadMyTripPage();
        }
    });

    // Remove button inside My Trip page
    document.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.js-remove-trip');
        if (!removeBtn) return;
        e.preventDefault();
        e.stopPropagation();

        const id = parseInt(removeBtn.dataset.id, 10);
        if (!id) return;

        let ids = getTripIds();
        ids = ids.filter(item => item !== id);
        saveTripIds(ids);

        // Also clean up notes for this ID
        const notes = getTripNotes();
        delete notes[id];
        delete notes[String(id)];
        saveTripNotes(notes);

        showToast('Experience removed from trip.');
        loadMyTripPage();
    });

    // Clear All Trip Button
    const clearAllTripBtn = document.querySelector('.js-clear-all-trip');
    if (clearAllTripBtn) {
        clearAllTripBtn.addEventListener('click', function () {
            if (confirm('Clear all experiences from your trip planner?')) {
                saveTripIds([]);
                saveTripNotes({});
                loadMyTripPage();
                showToast('All experiences cleared.');
            }
        });
    }

    // Date & Notes change listeners inside My Trip page
    document.addEventListener('change', function (e) {
        if (e.target.matches('.js-trip-date') || e.target.matches('.js-trip-note')) {
            const card = e.target.closest('.st-card--trip');
            if (!card) return;
            const id = card.dataset.id;
            const dateInput = card.querySelector('.js-trip-date');
            const noteInput = card.querySelector('.js-trip-note');

            const notes = getTripNotes();
            notes[id] = {
                date: dateInput ? dateInput.value.trim() : '',
                note: noteInput ? noteInput.value.trim() : '',
            };
            saveTripNotes(notes);
        }
    });

    updateTripUI();

    // -------------------------------------------------------------
    // 6. My Trip Page Loader & Map
    // -------------------------------------------------------------
    let tripLeafletMap = null;
    let tripMarkersGroup = null;

    function loadMyTripPage() {
        const myTripContainer = document.querySelector('.js-my-trip-grid');
        const statsBar = document.querySelector('.js-trip-stats-bar');
        const mapWrapper = document.getElementById('st-my-trip-map-wrapper');
        const isSharedPage = document.querySelector('.st-my-trip-page[data-is-shared="1"]');

        if (!myTripContainer || isSharedPage) return;

        const ids = getTripIds();
        if (ids.length === 0) {
            if (statsBar) statsBar.style.display = 'none';
            if (mapWrapper) mapWrapper.style.display = 'none';

            myTripContainer.innerHTML = `
                <div class="st-no-results" style="grid-column: 1/-1; padding: 70px 20px;">
                    <div class="st-no-results__icon">🗺️</div>
                    <h2 style="font-size: 1.6rem; margin-bottom: 8px;">Your Okanagan Trip is Empty</h2>
                    <p style="color:var(--st-muted); max-width: 500px; margin: 0 auto 24px auto;">You haven't saved any wine tours, lake charters, or mountain hikes yet. Heart any experience to add it to your itinerary.</p>
                    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <a href="${smarttours_vars.home_url}experiences/wine-touring/" class="st-btn st-btn--primary">Browse Wine Tours</a>
                        <a href="${smarttours_vars.home_url}experiences/active-outdoors/" class="st-btn st-btn-pill">Active &amp; Outdoors</a>
                    </div>
                </div>
            `;
            return;
        }

        if (statsBar) statsBar.style.display = 'flex';
        const statCount = document.querySelector('.js-stat-count');
        if (statCount) statCount.textContent = ids.length;

        // Render skeleton cards while loading
        myTripContainer.innerHTML = renderSkeletonCards(ids.length);

        const formData = new FormData();
        formData.append('action', 'smarttours_get_trip_items');
        ids.forEach(id => formData.append('ids[]', id));

        const notes = getTripNotes();
        Object.keys(notes).forEach(k => {
            if (notes[k].date) formData.append(`notes[${k}][date]`, notes[k].date);
            if (notes[k].note) formData.append(`notes[${k}][note]`, notes[k].note);
        });

        fetch(smarttours_vars.ajax_url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                myTripContainer.innerHTML = res.data.html;
                updateTripUI();

                const totalElem = document.querySelector('.js-stat-total');
                if (totalElem) totalElem.textContent = Number(res.data.total_price || 0).toLocaleString();

                // Plot Map of Saved Stops
                if (res.data.markers && res.data.markers.length > 0 && typeof L !== 'undefined') {
                    if (mapWrapper) mapWrapper.style.display = 'block';
                    const mapElement = document.getElementById('st-my-trip-map');
                    if (mapElement) {
                        if (!tripLeafletMap) {
                            tripLeafletMap = L.map('st-my-trip-map', { scrollWheelZoom: false }).setView([49.8880, -119.4960], 10);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(tripLeafletMap);
                            tripMarkersGroup = L.featureGroup().addTo(tripLeafletMap);
                        } else {
                            tripMarkersGroup.clearLayers();
                        }

                        res.data.markers.forEach(m => {
                            const popup = `
                                <div style="width:200px; font-family:var(--st-font);">
                                    <img src="${m.image}" style="width:100%; height:105px; object-fit:cover; border-radius:6px; margin-bottom:6px;" />
                                    <h4 style="font-size:13px; font-weight:700; margin-bottom:3px; line-height:1.2;">Stop #${m.stop_num}: ${m.title}</h4>
                                    <p style="font-size:11px; color:#666; margin-bottom:6px;">${m.address}</p>
                                    <a href="${m.url}" style="display:inline-block; font-size:11px; font-weight:bold; background:#0F7B5F; color:#fff; padding:4px 10px; border-radius:4px; text-decoration:none;">View Tour &rarr;</a>
                                </div>
                            `;
                            const marker = L.marker([m.lat, m.lng]).bindPopup(popup);
                            tripMarkersGroup.addLayer(marker);
                        });

                        setTimeout(() => {
                            try {
                                tripLeafletMap.invalidateSize();
                                tripLeafletMap.fitBounds(tripMarkersGroup.getBounds(), { padding: [40, 40] });
                            } catch (e) {}
                        }, 200);
                    }
                } else if (mapWrapper) {
                    mapWrapper.style.display = 'none';
                }
            }
        })
        .catch(() => {
            myTripContainer.innerHTML = '<div style="grid-column:1/-1; padding:40px; color:#c00;">Failed to load your trip.</div>';
        });
    }

    if (document.querySelector('.st-my-trip-page') && !document.querySelector('.st-my-trip-page').dataset.isShared) {
        loadMyTripPage();
    }

    // -------------------------------------------------------------
    // 7. Share Trip Modal & Server-Side Generator
    // -------------------------------------------------------------
    const shareModal = document.querySelector('.js-share-modal');
    const shareUrlInput = document.querySelector('.js-share-url-input');

    document.querySelectorAll('.js-open-share-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const ids = getTripIds();
            if (ids.length === 0) {
                alert('Add at least one experience to your trip before sharing.');
                return;
            }

            if (shareModal) shareModal.classList.add('is-open');
            if (shareUrlInput) shareUrlInput.value = 'Generating unique trip link...';

            const formData = new FormData();
            formData.append('action', 'smarttours_save_trip');
            formData.append('ids', JSON.stringify(ids));
            formData.append('notes', JSON.stringify(getTripNotes()));

            fetch(smarttours_vars.ajax_url, {
                method: 'POST',
                body: formData,
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const shareUrl = res.data.share_url;
                    if (shareUrlInput) shareUrlInput.value = shareUrl;

                    // Update direct channel links
                    const waBtn = document.querySelector('.js-share-whatsapp');
                    if (waBtn) {
                        waBtn.href = `https://api.whatsapp.com/send?text=${encodeURIComponent('Check out my custom Okanagan trip on Canada Smart Tours: ' + shareUrl)}`;
                    }

                    const emailBtn = document.querySelector('.js-share-email');
                    if (emailBtn) {
                        emailBtn.href = `mailto:?subject=${encodeURIComponent('My Okanagan Trip Itinerary')}&body=${encodeURIComponent('Here is the Okanagan trip itinerary I planned on Canada Smart Tours:\n\n' + shareUrl)}`;
                    }

                    const nativeBtn = document.querySelector('.js-share-native');
                    if (nativeBtn) {
                        nativeBtn.onclick = () => {
                            if (navigator.share) {
                                navigator.share({
                                    title: 'My Okanagan Trip - Canada Smart Tours',
                                    url: shareUrl,
                                }).catch(() => {});
                            } else {
                                copyToClipboard(shareUrl);
                            }
                        };
                    }
                } else {
                    if (shareUrlInput) shareUrlInput.value = 'Error generating link.';
                }
            })
            .catch(() => {
                if (shareUrlInput) shareUrlInput.value = 'Connection error.';
            });
        });
    });

    // Close Share Modal
    document.querySelectorAll('.js-close-share-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            if (shareModal) shareModal.classList.remove('is-open');
        });
    });

    if (shareModal) {
        shareModal.addEventListener('click', function (e) {
            if (e.target === shareModal) shareModal.classList.remove('is-open');
        });
    }

    // Copy Link button in share modal
    document.querySelectorAll('.js-copy-share-url').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!shareUrlInput || !shareUrlInput.value) return;
            copyToClipboard(shareUrlInput.value);
            const copyText = btn.querySelector('.js-copy-text');
            if (copyText) {
                const orig = copyText.textContent;
                copyText.textContent = 'Link Copied!';
                setTimeout(() => copyText.textContent = orig, 2500);
            }
        });
    });

    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Link copied to clipboard!');
            });
        } else {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToast('Link copied to clipboard!');
        }
    }

    // Import shared trip into visitor's own trip
    document.querySelectorAll('.js-import-shared-trip').forEach(btn => {
        btn.addEventListener('click', function () {
            try {
                const ids = JSON.parse(this.dataset.ids || '[]');
                const notes = JSON.parse(this.dataset.notes || '{}');
                let existingIds = getTripIds();
                const mergedIds = Array.from(new Set([...existingIds, ...ids]));
                saveTripIds(mergedIds);

                const existingNotes = getTripNotes();
                const mergedNotes = Object.assign({}, existingNotes, notes);
                saveTripNotes(mergedNotes);

                showToast('Trip saved to your planner! Redirecting...', smarttours_vars.my_trip_url);
                setTimeout(() => {
                    window.location.href = smarttours_vars.my_trip_url;
                }, 1000);
            } catch (e) {
                alert('Could not import trip.');
            }
        });
    });

    // -------------------------------------------------------------
    // 8. AJAX Filter, Search & Sorting on Listing Pages
    // -------------------------------------------------------------
    const gridContainer = document.querySelector('.js-experience-grid:not(.js-my-trip-grid)');
    const loadMoreBtn = document.querySelector('.js-load-more');
    const mapWrapper = document.querySelector('.js-map-wrapper');
    const mapToggleBtn = document.querySelector('.js-toggle-map');
    const resultsCountElem = document.querySelector('.js-results-count');
    const priceSlider = document.querySelector('.js-price-slider');
    const priceValElem = document.querySelector('.js-price-val');

    let archiveLeafletMap = null;
    let archiveMarkersGroup = null;
    let currentUserLat = null;
    let currentUserLng = null;
    let currentPage = 1;
    let currentView = 'grid';

    function renderSkeletonCards(count = 6) {
        let html = '';
        for (let i = 0; i < count; i++) {
            html += `
                <div class="st-card-skeleton">
                    <div class="st-skeleton-media"></div>
                    <div class="st-skeleton-body">
                        <div class="st-skeleton-line st-skeleton-line--short"></div>
                        <div class="st-skeleton-line st-skeleton-line--title"></div>
                        <div class="st-skeleton-line"></div>
                        <div class="st-skeleton-line st-skeleton-line--price"></div>
                    </div>
                </div>
            `;
        }
        return html;
    }

    function initArchiveMap(markersData) {
        const mapElement = document.getElementById('st-experience-map');
        if (!mapElement || typeof L === 'undefined') return;

        if (!archiveLeafletMap) {
            archiveLeafletMap = L.map('st-experience-map', { scrollWheelZoom: false }).setView([49.8880, -119.4960], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(archiveLeafletMap);
            archiveMarkersGroup = L.featureGroup().addTo(archiveLeafletMap);
        } else {
            archiveMarkersGroup.clearLayers();
        }

        if (markersData && markersData.length > 0) {
            markersData.forEach(m => {
                if (m.lat && m.lng) {
                    const popupHtml = `
                        <div style="width:200px; font-family:var(--st-font);">
                            <img src="${m.image}" style="width:100%; height:105px; object-fit:cover; margin-bottom:6px; border-radius:6px;" />
                            <h4 style="font-size:13px; font-weight:700; margin-bottom:3px; line-height:1.2;">${m.title}</h4>
                            <p style="font-size:11px; color:#666; margin-bottom:5px;">${m.address}</p>
                            ${m.price ? `<p style="font-size:12px; font-weight:bold; color:#0F7B5F; margin-bottom:6px;">${m.price}</p>` : ''}
                            <a href="${m.url}" style="display:inline-block; font-size:11px; font-weight:bold; background:#0F7B5F; color:#fff; padding:4px 10px; border-radius:4px; text-decoration:none;">View Tour &rarr;</a>
                        </div>
                    `;
                    const marker = L.marker([m.lat, m.lng]).bindPopup(popupHtml);
                    archiveMarkersGroup.addLayer(marker);
                }
            });

            try {
                archiveLeafletMap.fitBounds(archiveMarkersGroup.getBounds(), { padding: [35, 35] });
            } catch (e) {}
        }
    }

    // Toggle Map
    if (mapToggleBtn && mapWrapper) {
        mapToggleBtn.addEventListener('click', function () {
            const isOpen = mapWrapper.classList.toggle('is-open');
            this.classList.toggle('is-active', isOpen);
            this.querySelector('span').textContent = isOpen ? 'Hide Map' : 'Show Map';
            if (isOpen && archiveLeafletMap) {
                setTimeout(() => archiveLeafletMap.invalidateSize(), 200);
            }
        });
    }

    // Price Slider display update
    if (priceSlider && priceValElem) {
        priceSlider.addEventListener('input', function () {
            if (parseInt(this.value, 10) >= 400) {
                priceValElem.textContent = 'All Prices';
            } else {
                priceValElem.textContent = `Up to $${this.value} CAD`;
            }
        });
        priceSlider.addEventListener('change', () => runAjaxFilter(false));
    }

    function runAjaxFilter(append = false) {
        if (!gridContainer) return;

        if (!append) {
            currentPage = 1;
            gridContainer.innerHTML = renderSkeletonCards(6);
        }

        const termIdInput = document.querySelector('input[name="filter_term_id"]');
        const termId = termIdInput ? termIdInput.value : 0;
        const kwInput = document.querySelector('#filter_keyword');
        const keyword = kwInput ? kwInput.value.trim() : '';
        const sortSelect = document.querySelector('#filter_sort');
        const sort = sortSelect ? sortSelect.value : 'recommended';
        const freeCancelCb = document.querySelector('#filter_free_cancel');
        const freeCancel = freeCancelCb && freeCancelCb.checked ? '1' : '0';
        const durationRadio = document.querySelector('input[name="duration_filter"]:checked');
        const durationVal = durationRadio ? durationRadio.value : 'all';
        const ratingRadio = document.querySelector('input[name="rating_min"]:checked');
        const ratingVal = ratingRadio ? ratingRadio.value : '0';
        const maxPrice = priceSlider && parseInt(priceSlider.value, 10) < 400 ? priceSlider.value : 0;

        const checkedAmenities = [];
        document.querySelectorAll('input[name="amenity[]"]:checked').forEach(cb => {
            checkedAmenities.push(cb.value);
        });

        const formData = new FormData();
        formData.append('action', 'smarttours_filter_experiences');
        formData.append('term_id', termId);
        formData.append('keyword', keyword);
        formData.append('sort', sort);
        formData.append('paged', currentPage);
        formData.append('view', currentView);
        formData.append('free_cancellation', freeCancel);
        formData.append('duration_filter', durationVal);
        formData.append('rating_min', ratingVal);
        if (maxPrice > 0) formData.append('price_max', maxPrice);

        if (currentUserLat && currentUserLng) {
            formData.append('user_lat', currentUserLat);
            formData.append('user_lng', currentUserLng);
        }

        checkedAmenities.forEach(amId => {
            formData.append('amenities[]', amId);
        });

        let totalPages = 1;

        fetch(smarttours_vars.ajax_url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                totalPages = data.max_pages || 1;

                if (append) {
                    gridContainer.insertAdjacentHTML('beforeend', data.html);
                } else {
                    gridContainer.innerHTML = data.html;
                }

                document.querySelectorAll('.js-results-count').forEach(el => {
                    el.textContent = data.found_posts;
                });

                // Update pagination controls
                const pageRangeElem = document.querySelector('.js-page-range');
                if (pageRangeElem) {
                    if (data.found_posts === 0) {
                        pageRangeElem.textContent = '0';
                    } else {
                        const start = (currentPage - 1) * 12 + 1;
                        const end = Math.min(currentPage * 12, data.found_posts);
                        pageRangeElem.textContent = `${start} – ${end}`;
                    }
                }

                const prevPageBtn = document.querySelector('.js-page-prev');
                const nextPageBtn = document.querySelector('.js-page-next');
                if (prevPageBtn) prevPageBtn.disabled = (currentPage <= 1);
                if (nextPageBtn) nextPageBtn.disabled = (currentPage >= totalPages);

                if (loadMoreBtn) loadMoreBtn.style.display = (data.paged < data.max_pages) ? 'inline-flex' : 'none';

                initArchiveMap(data.markers);
                updateTripUI();
                initQuickviewButtons();
            }
        })
        .catch(() => {});
    }

    // Filter Listeners
    document.querySelectorAll('.js-filter-input').forEach(input => {
        input.addEventListener('change', () => runAjaxFilter(false));
    });

    const searchForm = document.querySelector('.js-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            runAjaxFilter(false);
        });
    }

    // Clear filters
    document.querySelectorAll('.js-clear-filters').forEach(btn => {
        btn.addEventListener('click', function () {
            const kw = document.querySelector('#filter_keyword');
            if (kw) kw.value = '';
            document.querySelectorAll('input[name="amenity[]"]').forEach(cb => cb.checked = false);
            const freeCb = document.querySelector('#filter_free_cancel');
            if (freeCb) freeCb.checked = false;
            const durAll = document.querySelector('input[name="duration_filter"][value="all"]');
            if (durAll) durAll.checked = true;
            const ratAll = document.querySelector('input[name="rating_min"][value="0"]');
            if (ratAll) ratAll.checked = true;
            if (priceSlider) {
                priceSlider.value = 400;
                if (priceValElem) priceValElem.textContent = 'All Prices';
            }
            const sortSel = document.querySelector('#filter_sort');
            if (sortSel) sortSel.value = 'recommended';
            document.querySelectorAll('.js-sort-tab').forEach(t => {
                t.classList.toggle('is-active', t.dataset.sort === 'recommended');
            });
            runAjaxFilter(false);
        });
    });

    // Sort Tabs (Tourism Kelowna style: RECOMMENDED, NEAR ME, PRICE, RATING)
    document.querySelectorAll('.js-sort-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.js-sort-tab').forEach(t => t.classList.remove('is-active'));
            this.classList.add('is-active');
            const sortVal = this.dataset.sort;
            const sortSelect = document.querySelector('#filter_sort');
            if (sortSelect) sortSelect.value = sortVal;

            if (sortVal === 'near_me') {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        pos => {
                            currentUserLat = pos.coords.latitude;
                            currentUserLng = pos.coords.longitude;
                            runAjaxFilter(false);
                        },
                        () => {
                            currentUserLat = 49.8880;
                            currentUserLng = -119.4960;
                            runAjaxFilter(false);
                        }
                    );
                } else {
                    runAjaxFilter(false);
                }
            } else {
                runAjaxFilter(false);
            }
        });
    });

    // Prev / Next Pagination Arrow Buttons
    const prevPageBtn = document.querySelector('.js-page-prev');
    const nextPageBtn = document.querySelector('.js-page-next');

    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                runAjaxFilter(false);
                const listingTop = document.querySelector('.st-listing-layout');
                if (listingTop) listingTop.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', function () {
            currentPage++;
            runAjaxFilter(false);
            const listingTop = document.querySelector('.st-listing-layout');
            if (listingTop) listingTop.scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Sort Dropdown (fallback / sync)
    const sortSelect = document.querySelector('#filter_sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            document.querySelectorAll('.js-sort-tab').forEach(t => {
                t.classList.toggle('is-active', t.dataset.sort === sortSelect.value);
            });
            runAjaxFilter(false);
        });
    }

    // View Switcher (Grid | List)
    document.querySelectorAll('.js-view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.js-view-btn').forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
            currentView = this.dataset.view;
            runAjaxFilter(false);
        });
    });

    // Quickview Modal System
    const qvModal = document.getElementById('st-quickview-modal');
    const qvBody = document.querySelector('.js-quickview-body');

    function initQuickviewButtons() {
        document.querySelectorAll('.js-quickview-btn').forEach(btn => {
            btn.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                openQuickview(this.dataset.id);
            };
        });
    }
    initQuickviewButtons();

    function openQuickview(postId) {
        if (!qvModal || !qvBody) return;
        qvModal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        qvBody.innerHTML = `
            <div class="st-quickview-loading">
                <div class="st-spinner"></div>
                <span>Loading preview...</span>
            </div>
        `;

        fetch(`${smarttours_vars.ajax_url}?action=smarttours_get_quickview&post_id=${postId}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const d = res.data;
                    qvBody.innerHTML = `
                        <div class="st-quickview-grid">
                            <div class="st-quickview-media">
                                <img src="${d.image}" alt="${d.title}" class="st-quickview-img" />
                                <button type="button" class="st-card__heart js-toggle-trip" data-id="${postId}" aria-label="Save to My Trip" title="Save to My Trip">
                                    <svg viewBox="0 0 24 24" class="st-icon-heart" width="20" height="20" aria-hidden="true">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="st-quickview-info">
                                <span class="st-quickview-cat">${d.category}</span>
                                <h3 class="st-quickview-title">${d.title}</h3>
                                <div class="st-quickview-location">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <span>${d.address}</span>
                                </div>
                                <div class="st-quickview-meta-row">
                                    <span>★ <strong>${d.rating}</strong> (${d.reviews})</span>
                                    <span>&bull;</span>
                                    <span>⏱ ${d.duration}</span>
                                    <span>&bull;</span>
                                    <span>🛡 ${d.cancellation}</span>
                                </div>
                                <p class="st-quickview-desc">${d.excerpt}</p>
                                <div class="st-quickview-footer">
                                    <div class="st-quickview-price-box">
                                        <small>From</small>
                                        <strong>${d.price} CAD</strong>
                                    </div>
                                    <div class="st-quickview-actions">
                                        <a href="${d.url}" class="st-btn st-btn--primary">View Tour Page &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    updateTripUI();
                } else {
                    qvBody.innerHTML = '<p style="padding:40px; text-align:center;">Could not load experience preview.</p>';
                }
            })
            .catch(() => {
                qvBody.innerHTML = '<p style="padding:40px; text-align:center;">Connection error.</p>';
            });
    }

    function closeQuickview() {
        if (!qvModal) return;
        qvModal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-close-quickview').forEach(btn => {
        btn.addEventListener('click', closeQuickview);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && qvModal && qvModal.classList.contains('is-open')) {
            closeQuickview();
        }
    });

    // Show more amenities toggle
    const showMoreAmenitiesBtn = document.querySelector('.js-show-more-amenities');
    if (showMoreAmenitiesBtn) {
        showMoreAmenitiesBtn.addEventListener('click', function () {
            const hiddenItems = document.querySelectorAll('.st-amenity-extra');
            let isExpanded = false;
            hiddenItems.forEach(el => {
                const isHidden = (el.style.display === 'none' || !el.style.display);
                el.style.display = isHidden ? 'flex' : 'none';
                if (isHidden) isExpanded = true;
            });
            this.textContent = isExpanded ? '- Show fewer amenities' : '+ Show more amenities';
        });
    }

    // Load More Pagination
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            currentPage++;
            runAjaxFilter(true);
        });
    }

    // Mobile Filters Bottom Sheet Drawer
    const filterSidebar = document.querySelector('.js-filter-sidebar');
    const filterBackdrop = document.querySelector('.st-filter-backdrop');

    document.querySelectorAll('.js-open-filters-sheet').forEach(btn => {
        btn.addEventListener('click', () => {
            if (filterSidebar) filterSidebar.classList.add('is-open');
            if (filterBackdrop) filterBackdrop.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeFilterSheet() {
        if (filterSidebar) filterSidebar.classList.remove('is-open');
        if (filterBackdrop) filterBackdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-close-filters-sheet, .js-apply-mobile-filters').forEach(btn => {
        btn.addEventListener('click', closeFilterSheet);
    });

    // -------------------------------------------------------------
    // 9. Single Experience Viator Layout Functionality
    // -------------------------------------------------------------
    // 9a. Gallery Thumbnails & Counter
    const galleryThumbs = document.querySelectorAll('.js-thumb-item');
    const galleryMainImg = document.querySelector('.js-gallery-main-img');
    const galleryCounter = document.querySelector('.js-gallery-counter span');
    let currentGalleryIdx = 0;

    if (galleryThumbs.length > 0 && galleryMainImg) {
        galleryThumbs.forEach((thumb, idx) => {
            thumb.addEventListener('click', function () {
                currentGalleryIdx = idx;
                updateGalleryImage(idx);
            });
        });

        // Prev / Next arrows
        const prevBtn = document.querySelector('.js-gallery-prev');
        const nextBtn = document.querySelector('.js-gallery-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                currentGalleryIdx = (currentGalleryIdx - 1 + galleryThumbs.length) % galleryThumbs.length;
                updateGalleryImage(currentGalleryIdx);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                currentGalleryIdx = (currentGalleryIdx + 1) % galleryThumbs.length;
                updateGalleryImage(currentGalleryIdx);
            });
        }
    }

    function updateGalleryImage(index) {
        if (!galleryThumbs[index] || !galleryMainImg) return;
        galleryThumbs.forEach(t => t.classList.remove('is-active'));
        galleryThumbs[index].classList.add('is-active');
        galleryMainImg.src = galleryThumbs[index].dataset.src;
        if (galleryCounter) {
            galleryCounter.textContent = `${index + 1} / ${galleryThumbs.length} Photos`;
        }
    }

    // 9b. Fullscreen Lightbox
    const lightboxModal = document.querySelector('.js-lightbox-modal');
    const lightboxImg = document.querySelector('.js-lightbox-img');
    const lightboxCounter = document.querySelector('.js-lightbox-counter');
    const lightboxClose = document.querySelector('.js-lightbox-close');
    const lightboxPrev = document.querySelector('.js-lightbox-prev');
    const lightboxNext = document.querySelector('.js-lightbox-next');

    function openLightbox(index) {
        if (!lightboxModal || !galleryThumbs[index]) return;
        currentGalleryIdx = index;
        lightboxImg.src = galleryThumbs[index].dataset.src;
        if (lightboxCounter) lightboxCounter.textContent = `${index + 1} / ${galleryThumbs.length}`;
        lightboxModal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightboxModal) return;
        lightboxModal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.js-gallery-lightbox-trigger, .js-gallery-main-img').forEach(el => {
        el.addEventListener('click', () => openLightbox(currentGalleryIdx));
    });

    document.querySelectorAll('.js-lightbox-slide').forEach(card => {
        card.addEventListener('click', function () {
            const src = this.dataset.src;
            let targetIdx = 0;
            galleryThumbs.forEach((t, i) => {
                if (t.dataset.src === src) targetIdx = i;
            });
            openLightbox(targetIdx);
        });
    });

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', () => {
            currentGalleryIdx = (currentGalleryIdx - 1 + galleryThumbs.length) % galleryThumbs.length;
            openLightbox(currentGalleryIdx);
        });
    }
    if (lightboxNext) {
        lightboxNext.addEventListener('click', () => {
            currentGalleryIdx = (currentGalleryIdx + 1) % galleryThumbs.length;
            openLightbox(currentGalleryIdx);
        });
    }

    // 9c. Sticky In-Page Navigation & Smooth Scroll
    document.querySelectorAll('.js-inpage-nav a, .js-scroll-to').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (!href || !href.startsWith('#')) return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // In-page nav scrollspy
    const navLinks = document.querySelectorAll('.js-inpage-nav a');
    if (navLinks.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    navLinks.forEach(link => {
                        link.classList.toggle('is-active', link.getAttribute('href') === `#${id}`);
                    });
                }
            });
        }, { rootMargin: '-20% 0px -70% 0px' });

        document.querySelectorAll('.st-exp-section[id]').forEach(sec => observer.observe(sec));
    }

    // 9d. Travelers Selector Counter & Live Price Calculator
    let guestCount = 2;
    const guestCountElem = document.querySelector('.js-guest-count');
    const guestIncBtn = document.querySelector('.js-guest-inc');
    const guestDecBtn = document.querySelector('.js-guest-dec');
    const basePriceElem = document.querySelector('.js-base-price');
    const totalPriceElem = document.querySelector('.js-total-price');

    if (guestIncBtn && guestDecBtn && basePriceElem && totalPriceElem) {
        const basePrice = parseFloat(basePriceElem.dataset.base || '45');

        guestIncBtn.addEventListener('click', function () {
            if (guestCount < 20) {
                guestCount++;
                updateGuestPrice(basePrice);
            }
        });

        guestDecBtn.addEventListener('click', function () {
            if (guestCount > 1) {
                guestCount--;
                updateGuestPrice(basePrice);
            }
        });
    }

    function updateGuestPrice(base) {
        if (guestCountElem) guestCountElem.textContent = guestCount;
        if (totalPriceElem) {
            totalPriceElem.textContent = Number(base * guestCount).toLocaleString();
        }
    }

    // 9e. Additional Info "Show More Details" Toggle
    const extraBulletsBtn = document.querySelector('.js-toggle-extra-bullets');
    if (extraBulletsBtn) {
        extraBulletsBtn.addEventListener('click', function () {
            const extraItems = document.querySelectorAll('.st-bullets-two-col li.is-extra');
            let isExpanded = false;
            extraItems.forEach(item => {
                const isHidden = (item.style.display === 'none' || !item.style.display);
                item.style.display = isHidden ? 'flex' : 'none';
                if (isHidden) isExpanded = true;
            });
            this.textContent = isExpanded ? '- Show fewer details' : '+ Show more details';
        });
    }

    // 9f. Social Share button
    document.querySelectorAll('.js-share-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href,
                }).catch(() => {});
            } else {
                copyToClipboard(window.location.href);
            }
        });
    });

    // -------------------------------------------------------------
    // 10. Recently Viewed Experiences (SMART TOUCHES)
    // -------------------------------------------------------------
    const RECENT_KEY = 'smarttours_recently_viewed';

    function trackCurrentExperience() {
        const page = document.querySelector('.st-single-experience-page');
        if (!page) return;

        const id = parseInt(page.dataset.id, 10);
        if (!id) return;

        const currentExp = {
            id: id,
            title: page.dataset.title || '',
            price: page.dataset.price || '45',
            image: page.dataset.image || '',
            url: page.dataset.url || window.location.href,
            city: page.dataset.city || 'Kelowna',
            rating: page.dataset.rating || '4.9',
        };

        try {
            let list = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]');
            list = list.filter(item => item.id !== id);
            list.unshift(currentExp);
            if (list.length > 6) list = list.slice(0, 6);
            localStorage.setItem(RECENT_KEY, JSON.stringify(list));
        } catch (e) {}
    }

    function renderRecentlyViewed() {
        const section = document.querySelector('.js-recently-viewed-section');
        const grid = document.querySelector('.js-recently-viewed-grid');
        if (!section || !grid) return;

        try {
            const list = JSON.parse(localStorage.getItem(RECENT_KEY) || '[]');
            const currentPage = document.querySelector('.st-single-experience-page');
            const currentId = currentPage ? parseInt(currentPage.dataset.id, 10) : 0;

            const filtered = list.filter(item => item.id !== currentId);
            if (filtered.length === 0) {
                section.style.display = 'none';
                return;
            }

            let cardsHtml = '';
            filtered.forEach(exp => {
                cardsHtml += `
                    <article class="st-card" data-id="${exp.id}">
                        <a href="${exp.url}" class="st-card__main-link" aria-label="${exp.title}"></a>
                        <div class="st-card__media">
                            <img src="${exp.image}" alt="${exp.title}" loading="lazy" class="st-card__img" />
                            <button type="button" class="st-card__heart js-toggle-trip" data-id="${exp.id}" title="Save to My Trip">
                                <svg viewBox="0 0 24 24" class="st-icon-heart" width="20" height="20">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="st-card__body">
                            <div class="st-card__location">📍 ${exp.city}, BC</div>
                            <h3 class="st-card__title"><a href="${exp.url}">${exp.title}</a></h3>
                            <div class="st-card__meta">
                                <span class="st-card__rating"><span class="st-star-icon">★</span> <strong>${exp.rating}</strong></span>
                            </div>
                            <div class="st-card__footer">
                                <div class="st-card__price">
                                    <span class="st-price-label">From</span>
                                    <span class="st-price-amount">$${exp.price}</span>
                                    <span class="st-price-currency">CAD</span>
                                </div>
                                <span class="st-card__cta-hint">View &rarr;</span>
                            </div>
                        </div>
                    </article>
                `;
            });

            grid.innerHTML = cardsHtml;
            grid.className = 'st-experience-grid';
            section.style.display = 'block';
            updateTripUI();
        } catch (e) {
            section.style.display = 'none';
        }
    }

    trackCurrentExperience();
    renderRecentlyViewed();

    // -------------------------------------------------------------
    // 11. Carousel Prev / Next Arrow Navigation (Homepage)
    // -------------------------------------------------------------
    const carouselTrack = document.querySelector('.js-carousel-track');
    const carouselPrev = document.querySelector('.js-carousel-prev');
    const carouselNext = document.querySelector('.js-carousel-next');

    if (carouselTrack && carouselPrev && carouselNext) {
        carouselPrev.addEventListener('click', function () {
            carouselTrack.scrollBy({ left: -320, behavior: 'smooth' });
        });

        carouselNext.addEventListener('click', function () {
            carouselTrack.scrollBy({ left: 320, behavior: 'smooth' });
        });
    }
});
