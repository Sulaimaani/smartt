/**
 * Canada Smart Tours (smarttours.ca) — Main Frontend JavaScript
 * Pure Vanilla JavaScript (No jQuery dependency)
 */

document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------
    // 1. Toast Notification Helper
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
        // Animate in
        requestAnimationFrame(() => toast.classList.add('is-visible'));

        const closeBtn = toast.querySelector('.st-toast__close');
        closeBtn.addEventListener('click', () => removeToast(toast));

        setTimeout(() => removeToast(toast), 4500);
    }

    function removeToast(toast) {
        toast.classList.remove('is-visible');
        setTimeout(() => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 300);
    }

    // -------------------------------------------------------------
    // 2. Mobile Drawer & Accordion Navigation
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

    // Accordions inside Mobile Drawer
    document.querySelectorAll('.js-accordion-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const panel = this.parentElement.nextElementSibling;
            const isOpen = panel && panel.classList.contains('is-open');
            document.querySelectorAll('.st-accordion-panel').forEach(p => p.classList.remove('is-open'));
            document.querySelectorAll('.st-accordion-toggle').forEach(btn => btn.setAttribute('aria-expanded', 'false'));
            if (!isOpen && panel) {
                panel.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // -------------------------------------------------------------
    // 3. Header Quick Search Bar Toggle
    // -------------------------------------------------------------
    const searchToggleBtn = document.querySelector('.js-toggle-header-search');
    const headerSearchBar = document.querySelector('.js-header-search-bar');
    if (searchToggleBtn && headerSearchBar) {
        searchToggleBtn.addEventListener('click', function () {
            const isOpen = headerSearchBar.classList.toggle('is-open');
            if (isOpen) {
                const input = headerSearchBar.querySelector('input');
                if (input) setTimeout(() => input.focus(), 150);
            }
        });
    }

    // -------------------------------------------------------------
    // 4. "My Trip" (Trip Builder) localStorage Integration
    // -------------------------------------------------------------
    const TRIP_STORAGE_KEY = 'smarttours_my_trip';

    function getTripIds() {
        try {
            const data = localStorage.getItem(TRIP_STORAGE_KEY);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error('LocalStorage error:', e);
            return [];
        }
    }

    function saveTripIds(ids) {
        try {
            localStorage.setItem(TRIP_STORAGE_KEY, JSON.stringify(ids));
            updateTripUI();
        } catch (e) {
            console.error('LocalStorage write error:', e);
        }
    }

    function updateTripUI() {
        const ids = getTripIds();
        const badges = document.querySelectorAll('.st-trip-count');
        badges.forEach(b => {
            b.textContent = ids.length;
        });

        // Update heart icon states in DOM
        document.querySelectorAll('.js-toggle-trip').forEach(btn => {
            const id = parseInt(btn.dataset.id, 10);
            if (ids.includes(id)) {
                btn.classList.add('is-saved');
                btn.setAttribute('aria-pressed', 'true');
                btn.setAttribute('title', 'Remove from My Trip');
            } else {
                btn.classList.remove('is-saved');
                btn.setAttribute('aria-pressed', 'false');
                btn.setAttribute('title', 'Save to My Trip');
            }
        });

        // Update stats bar if present
        const statCount = document.querySelector('.js-stat-count');
        if (statCount) statCount.textContent = ids.length;
    }

    // Toggle heart click event delegation
    document.addEventListener('click', function (e) {
        const heartBtn = e.target.closest('.js-toggle-trip');
        if (!heartBtn) return;
        e.preventDefault();

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
            const tripUrl = (window.smarttours_vars && window.smarttours_vars.my_trip_url) ? window.smarttours_vars.my_trip_url : '/my-trip/';
            showToast('❤️ Experience saved to My Trip!', tripUrl, 'View My Trip &rarr;');
        }

        // If on My Trip page, refresh
        if (document.querySelector('.js-my-trip-grid')) {
            loadMyTripPage();
        }
    });

    // Remove button inside My Trip page
    document.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.js-remove-trip');
        if (!removeBtn) return;
        e.preventDefault();

        const id = parseInt(removeBtn.dataset.id, 10);
        if (!id) return;

        let ids = getTripIds();
        ids = ids.filter(item => item !== id);
        saveTripIds(ids);
        showToast('Removed from your trip planner.');
        loadMyTripPage();
    });

    // Clear All Trip Button
    const clearAllTripBtn = document.querySelector('.js-clear-all-trip');
    if (clearAllTripBtn) {
        clearAllTripBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to clear all experiences from your trip?')) {
                saveTripIds([]);
                loadMyTripPage();
                showToast('All experiences cleared from trip planner.');
            }
        });
    }

    updateTripUI();

    // -------------------------------------------------------------
    // 5. My Trip Page Loader & Route Map
    // -------------------------------------------------------------
    let tripLeafletMap = null;
    let tripMarkersGroup = null;

    function loadMyTripPage() {
        const myTripContainer = document.querySelector('.js-my-trip-grid');
        const statsBar = document.querySelector('.js-trip-stats-bar');
        const mapWrapper = document.getElementById('st-my-trip-map-wrapper');
        if (!myTripContainer) return;

        const ids = getTripIds();
        if (ids.length === 0) {
            if (statsBar) statsBar.style.display = 'none';
            if (mapWrapper) mapWrapper.style.display = 'none';

            myTripContainer.innerHTML = `
                <div class="st-empty-trip-card" style="grid-column: 1/-1;">
                    <div class="st-empty-trip-icon">🗺️</div>
                    <h2>YOUR OKANAGAN TRIP IS EMPTY</h2>
                    <p>You haven't saved any wine tastings, lake cruises, or mountain tours yet.</p>
                    <p style="color:#666; font-size:0.95rem; margin-bottom:25px;">Browse our curated Okanagan experiences below and click the heart icon on any tour to add it to your itinerary.</p>
                    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                        <a href="${smarttours_vars.home_url}experiences/wine-touring/" class="st-btn st-btn--primary">EXPLORE WINE TOURS</a>
                        <a href="${smarttours_vars.home_url}experiences/active-outdoors/" class="st-btn st-btn--accent">EXPLORE ACTIVE &amp; OUTDOORS</a>
                    </div>
                </div>
            `;
            return;
        }

        if (statsBar) statsBar.style.display = 'flex';
        const statCount = document.querySelector('.js-stat-count');
        if (statCount) statCount.textContent = ids.length;

        myTripContainer.innerHTML = '<div style="grid-column:1/-1; padding:50px; text-align:center;"><div class="st-spinner"></div><p style="margin-top:15px;">Loading your saved Okanagan itinerary...</p></div>';

        const formData = new FormData();
        formData.append('action', 'smarttours_get_trip_items');
        ids.forEach(id => formData.append('ids[]', id));

        fetch(smarttours_vars.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                myTripContainer.innerHTML = res.data.html;
                updateTripUI();

                // Plot Map of Saved Trip Stops
                if (res.data.markers && res.data.markers.length > 0 && typeof L !== 'undefined') {
                    if (mapWrapper) mapWrapper.style.display = 'block';
                    const mapElement = document.getElementById('st-my-trip-map');
                    if (mapElement) {
                        if (!tripLeafletMap) {
                            tripLeafletMap = L.map('st-my-trip-map').setView([49.8880, -119.4960], 10);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(tripLeafletMap);
                            tripMarkersGroup = L.featureGroup().addTo(tripLeafletMap);
                        } else {
                            tripMarkersGroup.clearLayers();
                        }

                        res.data.markers.forEach((m, idx) => {
                            if (m.lat && m.lng) {
                                const popup = `
                                    <div style="width:200px; font-family:var(--font-sans);">
                                        <img src="${m.image}" style="width:100%; height:105px; object-fit:cover; border-radius:4px; margin-bottom:6px;" />
                                        <h4 style="font-size:13px; margin-bottom:2px; line-height:1.2;">Stop #${idx + 1}: ${m.title}</h4>
                                        <p style="font-size:11px; color:#666; margin-bottom:6px;">${m.address}</p>
                                        <a href="${m.url}" style="display:inline-block; font-size:11px; font-weight:bold; background:#000; color:#fff; padding:4px 10px; border-radius:4px; text-decoration:none;">View Experience &rarr;</a>
                                    </div>
                                `;
                                const marker = L.marker([m.lat, m.lng]).bindPopup(popup);
                                tripMarkersGroup.addLayer(marker);
                            }
                        });

                        setTimeout(() => {
                            try {
                                tripLeafletMap.invalidateSize();
                                tripLeafletMap.fitBounds(tripMarkersGroup.getBounds(), { padding: [40, 40] });
                            } catch (e) {}
                        }, 250);
                    }
                } else if (mapWrapper) {
                    mapWrapper.style.display = 'none';
                }
            }
        })
        .catch(err => {
            console.error('Trip fetch error:', err);
            myTripContainer.innerHTML = '<div style="grid-column:1/-1; padding:40px; color:#c00;">Failed to load saved experiences.</div>';
        });
    }

    if (document.querySelector('.js-my-trip-grid')) {
        loadMyTripPage();
    }

    // -------------------------------------------------------------
    // 6. Quickview Modal
    // -------------------------------------------------------------
    const modalBackdrop = document.querySelector('.js-quickview-modal');
    const modalBody = document.querySelector('.js-modal-body');

    function openQuickview(id) {
        if (!modalBackdrop || !modalBody) return;
        modalBody.innerHTML = '<div style="padding:60px; text-align:center;"><div class="st-spinner"></div><p style="margin-top:15px;">Loading experience details...</p></div>';
        modalBackdrop.classList.add('is-open');

        const formData = new FormData();
        formData.append('action', 'smarttours_quickview');
        formData.append('id', id);

        fetch(smarttours_vars.ajax_url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modalBody.innerHTML = data.data.html;
                updateTripUI();
            } else {
                modalBody.innerHTML = '<div style="padding:30px; color:#c00;">' + (data.data.message || 'Error loading experience.') + '</div>';
            }
        })
        .catch(err => {
            modalBody.innerHTML = '<div style="padding:30px; color:#c00;">Failed to load experience.</div>';
        });
    }

    document.addEventListener('click', function (e) {
        const qvBtn = e.target.closest('.js-quickview');
        if (qvBtn) {
            e.preventDefault();
            const id = qvBtn.dataset.id;
            openQuickview(id);
        }
        if (e.target.closest('.js-modal-close') || e.target === modalBackdrop) {
            if (modalBackdrop) modalBackdrop.classList.remove('is-open');
        }
    });

    // -------------------------------------------------------------
    // 7. AJAX Experiences Filter, Search & Leaflet Map
    // -------------------------------------------------------------
    const gridContainer = document.querySelector('.js-experience-grid:not(.js-my-trip-grid)');
    const loadMoreBtn = document.querySelector('.js-load-more');
    const mapWrapper = document.querySelector('.js-map-wrapper');
    const mapToggleBtn = document.querySelector('.js-toggle-map');
    const resultsCountElem = document.querySelector('.js-results-count');

    let archiveLeafletMap = null;
    let archiveMarkersGroup = null;
    let currentUserLat = null;
    let currentUserLng = null;
    let currentPage = 1;
    let currentView = 'grid';

    function initArchiveMap(markersData) {
        const mapElement = document.getElementById('st-experience-map');
        if (!mapElement || typeof L === 'undefined') return;

        if (!archiveLeafletMap) {
            archiveLeafletMap = L.map('st-experience-map').setView([49.8880, -119.4960], 11);
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
                        <div style="width:200px; font-family:var(--font-sans);">
                            <img src="${m.image}" style="width:100%; height:110px; object-fit:cover; margin-bottom:8px; border-radius:4px;" />
                            <h4 style="font-size:13px; margin-bottom:3px; line-height:1.2;">${m.title}</h4>
                            <p style="font-size:11px; color:#666; margin-bottom:5px;">${m.address}</p>
                            ${m.price ? `<p style="font-size:11px; font-weight:bold; color:#0073aa; margin-bottom:6px;">${m.price}</p>` : ''}
                            <a href="${m.url}" style="display:inline-block; font-size:11px; font-weight:bold; background:#000; color:#fff; padding:4px 8px; border-radius:3px; text-decoration:none;">VIEW TOUR &gt;</a>
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

    if (mapToggleBtn && mapWrapper) {
        mapToggleBtn.addEventListener('click', function () {
            const isOpen = mapWrapper.classList.toggle('is-open');
            this.classList.toggle('is-active', isOpen);
            this.querySelector('span').textContent = isOpen ? 'HIDE MAP' : 'SHOW MAP';
            if (isOpen && archiveLeafletMap) {
                setTimeout(() => {
                    archiveLeafletMap.invalidateSize();
                }, 200);
            }
        });
    }

    function runAjaxFilter(append = false) {
        if (!gridContainer) return;

        if (!append) {
            currentPage = 1;
            gridContainer.innerHTML = '<div style="grid-column:1/-1; padding:60px; text-align:center;"><div class="st-spinner"></div><p style="margin-top:15px;">Filtering Okanagan experiences...</p></div>';
        }

        const termIdInput = document.querySelector('input[name="filter_term_id"]');
        const termId = termIdInput ? termIdInput.value : 0;
        const keywordInput = document.querySelector('input[name="filter_keyword"]');
        const keyword = keywordInput ? keywordInput.value.trim() : '';
        const sortSelect = document.querySelector('select[name="filter_sort"]');
        const sort = sortSelect ? sortSelect.value : 'recommended';

        const checkedAmenities = [];
        document.querySelectorAll('input[name="amenity[]"]:checked').forEach(cb => {
            checkedAmenities.push(cb.value);
        });

        // Update URL query parameters
        const params = new URLSearchParams();
        if (keyword) params.set('keyword', keyword);
        if (checkedAmenities.length) params.set('amenities', checkedAmenities.join(','));
        if (sort && sort !== 'recommended') params.set('sort', sort);
        if (currentView && currentView !== 'grid') params.set('view', currentView);

        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);

        const formData = new FormData();
        formData.append('action', 'smarttours_filter_experiences');
        formData.append('term_id', termId);
        formData.append('keyword', keyword);
        formData.append('sort', sort);
        formData.append('paged', currentPage);
        formData.append('view', currentView);
        if (currentUserLat && currentUserLng) {
            formData.append('user_lat', currentUserLat);
            formData.append('user_lng', currentUserLng);
        }

        checkedAmenities.forEach(amId => {
            formData.append('amenities[]', amId);
        });

        fetch(smarttours_vars.ajax_url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                if (append) {
                    gridContainer.insertAdjacentHTML('beforeend', data.html);
                } else {
                    gridContainer.innerHTML = data.html;
                }

                if (resultsCountElem) {
                    resultsCountElem.textContent = data.found_posts;
                }

                if (loadMoreBtn) {
                    loadMoreBtn.style.display = (data.paged < data.max_pages) ? 'inline-block' : 'none';
                }

                initArchiveMap(data.markers);
                updateTripUI();
            }
        })
        .catch(err => {
            console.error('Filter error:', err);
        });
    }

    // Keyword Form Submit
    const searchForm = document.querySelector('.js-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            runAjaxFilter(false);
        });
    }

    // Amenities Checkbox Change
    document.querySelectorAll('input[name="amenity[]"]').forEach(cb => {
        cb.addEventListener('change', function () {
            runAjaxFilter(false);
        });
    });

    // Clear Filters Button
    document.querySelectorAll('.js-clear-filters').forEach(btn => {
        btn.addEventListener('click', function () {
            const kw = document.querySelector('input[name="filter_keyword"]');
            if (kw) kw.value = '';
            document.querySelectorAll('input[name="amenity[]"]').forEach(cb => cb.checked = false);
            const sort = document.querySelector('select[name="filter_sort"]');
            if (sort) sort.value = 'recommended';
            runAjaxFilter(false);
        });
    });

    // Sort Dropdown & "Near Me"
    const sortSelect = document.querySelector('select[name="filter_sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            if (this.value === 'near_me') {
                if (navigator.geolocation) {
                    const elem = this;
                    elem.disabled = true;
                    navigator.geolocation.getCurrentPosition(
                        pos => {
                            currentUserLat = pos.coords.latitude;
                            currentUserLng = pos.coords.longitude;
                            elem.disabled = false;
                            runAjaxFilter(false);
                        },
                        err => {
                            alert('Could not retrieve your location. Defaulting to Kelowna.');
                            currentUserLat = 49.8880;
                            currentUserLng = -119.4960;
                            elem.disabled = false;
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

    // Load More Pagination Click
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            currentPage++;
            runAjaxFilter(true);
        });
    }

    // Initial Map rendering if markers present
    if (document.getElementById('st-experience-map')) {
        const initialCards = document.querySelectorAll('.st-card');
        const initialMarkers = [];
        initialCards.forEach(card => {
            const lat = card.dataset.lat;
            const lng = card.dataset.lng;
            const titleElem = card.querySelector('.st-card__title a');
            const imgElem = card.querySelector('.st-card__img');
            const locElem = card.querySelector('.st-card__location span');
            if (lat && lng && titleElem) {
                initialMarkers.push({
                    title: titleElem.textContent,
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                    url: titleElem.href,
                    image: imgElem ? imgElem.src : '',
                    address: locElem ? locElem.textContent : '',
                });
            }
        });
        initArchiveMap(initialMarkers);
    }

    // -------------------------------------------------------------
    // 8. Single Experience Lightbox & Social Share
    // -------------------------------------------------------------
    const singleGalleryImg = document.querySelector('.js-gallery-main');
    const galleryThumbs = document.querySelectorAll('.js-thumb-item');
    const galleryCounter = document.querySelector('.js-gallery-counter');

    if (galleryThumbs.length > 0 && singleGalleryImg) {
        galleryThumbs.forEach((thumb, idx) => {
            thumb.addEventListener('click', function () {
                galleryThumbs.forEach(t => t.style.borderColor = '#ddd');
                this.style.borderColor = '#0073aa';
                singleGalleryImg.src = this.dataset.full;
                if (galleryCounter) {
                    galleryCounter.textContent = (idx + 1) + '/' + galleryThumbs.length;
                }
            });
        });
    }

    // Share Button
    document.querySelectorAll('.js-share-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: window.location.href,
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Link copied to clipboard!');
                });
            }
        });
    });
});
