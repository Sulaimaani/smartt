<?php
/**
 * Template Name: My Trip (Trip Builder)
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();
?>

<main id="primary" class="site-main st-my-trip-page" role="main">
    <!-- Hero Banner -->
    <section class="st-trip-hero">
        <div class="st-container">
            <?php smarttours_render_breadcrumbs(); ?>
            <div class="st-hero-pill-badge" style="margin-bottom: 12px;">
                <span>🇨🇦</span>
                <span>CANADA SMART TOURS &bull; ITINERARY BUILDER</span>
            </div>
            <h1 class="st-trip-hero__title">MY OKANAGAN TRIP PLANNER</h1>
            <p class="st-trip-hero__subtitle">
                Your custom valley itinerary. Review your saved wine tours, outdoor adventures, boat charters, and route map before booking.
            </p>

            <!-- Stats & Quick Actions Strip -->
            <div class="st-trip-stats-bar js-trip-stats-bar" style="display: none;">
                <div class="st-trip-stat">
                    <span class="st-stat-num js-stat-count">0</span>
                    <span class="st-stat-label">Saved Experiences</span>
                </div>
                <div class="st-trip-stat-divider"></div>
                <div class="st-trip-actions">
                    <button type="button" class="st-btn-trip-action" onclick="window.print();">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>Print Itinerary</span>
                    </button>
                    <button type="button" class="st-btn-trip-action js-share-btn">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        <span>Share Trip</span>
                    </button>
                    <button type="button" class="st-btn-trip-action st-btn-trip-action--danger js-clear-all-trip">
                        <span>Clear All</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="st-container st-my-trip-container" style="padding: 40px 20px 90px 20px;">
        <!-- Leaflet Map of Saved Trip Stops -->
        <div id="st-my-trip-map-wrapper" style="display: none; margin-bottom: 40px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <h3 style="font-size: 1.4rem;">🗺️ YOUR OKANAGAN ROUTE MAP</h3>
                <span style="font-size: 0.875rem; color: #666;">Interactive valley map showing your saved experience pins</span>
            </div>
            <div id="st-my-trip-map" style="height: 420px; width: 100%; border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--card-shadow);"></div>
        </div>

        <!-- Saved Experiences Cards Grid -->
        <div class="st-experience-grid js-my-trip-grid">
            <div style="grid-column: 1/-1; padding: 60px; text-align: center;">
                <div class="st-spinner"></div>
                <p style="margin-top: 15px; font-weight: 600;"><?php _e('Loading your saved experiences...', 'smarttours'); ?></p>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
