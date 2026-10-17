<?php
/**
 * Template Name: My Trip (Trip Builder & Shared Trips)
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();

global $wpdb;
$share_code = get_query_var('st_share_code');
$is_shared = false;
$shared_trip = null;
$shared_posts = array();
$shared_notes = array();
$total_shared_price = 0;

if (!empty($share_code)) {
    $table_name = $wpdb->prefix . 'st_trips';
    $shared_trip = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE share_code = %s", $share_code));
    if ($shared_trip) {
        $is_shared = true;
        $shared_ids = json_decode($shared_trip->experience_ids, true);
        $shared_notes = json_decode($shared_trip->notes, true) ?: array();

        if (!empty($shared_ids) && is_array($shared_ids)) {
            $shared_posts = get_posts(array(
                'post_type'      => 'experience',
                'post__in'       => $shared_ids,
                'posts_per_page' => -1,
                'orderby'        => 'post__in',
            ));
            foreach ($shared_posts as $sp) {
                $p_raw = get_post_meta($sp->ID, '_st_price_from', true);
                $total_shared_price += floatval(preg_replace('/[^0-9.]/', '', $p_raw));
            }
        }
    }
}
?>

<main id="primary" class="site-main st-my-trip-page" role="main" <?php echo $is_shared ? 'data-is-shared="1" data-share-code="' . esc_attr($share_code) . '"' : ''; ?>>
    <div class="st-container">
        <!-- Breadcrumbs -->
        <div class="st-listing-breadcrumbs">
            <?php smarttours_render_breadcrumbs(); ?>
        </div>

        <!-- Header -->
        <header class="st-trip-header">
            <?php if ($is_shared && $shared_trip): ?>
                <div class="st-shared-trip-banner">
                    <div class="st-shared-trip-banner__info">
                        <span class="st-shared-pill">Shared Okanagan Itinerary</span>
                        <h1>Trip &bull; <?php echo esc_html(strtoupper($share_code)); ?></h1>
                        <p>A fellow traveller shared their custom Okanagan itinerary with you. You can review the route map and save it into your own planner to edit dates or notes.</p>
                    </div>
                    <div class="st-shared-trip-banner__action">
                        <button type="button" class="st-btn st-btn--primary js-import-shared-trip" data-ids="<?php echo esc_attr(wp_json_encode(wp_list_pluck($shared_posts, 'ID'))); ?>" data-notes="<?php echo esc_attr(wp_json_encode($shared_notes)); ?>">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Save to My Trip</span>
                        </button>
                    </div>
                </div>
            <?php elseif (!empty($share_code) && !$shared_trip): ?>
                <div class="st-shared-trip-banner st-shared-trip-banner--error">
                    <h1>Trip Not Found</h1>
                    <p>The shared trip link you opened (code: <code><?php echo esc_html($share_code); ?></code>) does not exist or may have been removed.</p>
                    <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-btn st-btn--primary" style="margin-top: 15px;">Go to My Trip Planner</a>
                </div>
            <?php else: ?>
                <div class="st-trip-title-row">
                    <div>
                        <h1 class="st-trip-title"><?php _e('My Okanagan Trip Planner', 'smarttours'); ?></h1>
                        <p class="st-trip-desc"><?php _e('Review your saved wine tastings, outdoor adventures, boat charters, and route map before booking.', 'smarttours'); ?></p>
                    </div>

                    <!-- Actions & Stats Strip -->
                    <div class="st-trip-stats-bar js-trip-stats-bar" style="display: none;">
                        <div class="st-trip-stat">
                            <span class="st-stat-num js-stat-count">0</span>
                            <span class="st-stat-label">Saved Tours</span>
                        </div>
                        <div class="st-trip-stat">
                            <span class="st-stat-num">$<span class="js-stat-total">0</span> CAD</span>
                            <span class="st-stat-label">Total Estimated</span>
                        </div>
                        <div class="st-trip-actions-group">
                            <button type="button" class="st-btn-pill js-open-share-modal">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                                <span>Share Trip</span>
                            </button>
                            <button type="button" class="st-btn-pill" onclick="window.print();">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                <span>Print</span>
                            </button>
                            <button type="button" class="st-btn-pill st-btn-pill--danger js-clear-all-trip">
                                <span>Clear All</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </header>

        <!-- Route Map of Saved Stops -->
        <div id="st-my-trip-map-wrapper" class="st-trip-map-box" style="<?php echo ($is_shared && !empty($shared_posts)) ? 'display:block;' : 'display:none;'; ?>">
            <div class="st-trip-map-head">
                <div>
                    <h3 class="st-trip-map-title">🗺️ Your Okanagan Route Map</h3>
                    <p class="st-trip-map-sub">Numbered route pins showing your itinerary stops across the Okanagan Valley.</p>
                </div>
                <div class="st-trip-map-badge">
                    <span>Stops: <strong class="js-map-stop-count"><?php echo $is_shared ? count($shared_posts) : 0; ?></strong></span>
                </div>
            </div>
            <div id="st-my-trip-map" style="height: 420px; width: 100%; border-radius: 12px;"></div>
        </div>

        <!-- Saved Experiences Cards Grid -->
        <div class="st-experience-grid js-my-trip-grid">
            <?php if ($is_shared && !empty($shared_posts)): ?>
                <?php foreach ($shared_posts as $s_post):
                    $p_id = $s_post->ID;
                    $s_date = isset($shared_notes[$p_id]['date']) ? $shared_notes[$p_id]['date'] : (isset($shared_notes[strval($p_id)]['date']) ? $shared_notes[strval($p_id)]['date'] : '');
                    $s_note = isset($shared_notes[$p_id]['note']) ? $shared_notes[$p_id]['note'] : (isset($shared_notes[strval($p_id)]['note']) ? $shared_notes[strval($p_id)]['note'] : '');
                    echo smarttours_render_experience_card($s_post, 'grid', true, $s_date, $s_note, true);
                endforeach; ?>
            <?php elseif (!$is_shared): ?>
                <div class="st-loading-placeholder" style="grid-column: 1/-1; padding: 60px; text-align: center;">
                    <div class="st-spinner"></div>
                    <p style="margin-top: 15px; font-weight: 600;"><?php _e('Loading your saved itinerary...', 'smarttours'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Share Trip Modal -->
<div class="st-share-modal-backdrop js-share-modal" role="dialog" aria-modal="true" aria-label="Share Itinerary">
    <div class="st-share-modal-window">
        <button type="button" class="st-share-modal-close js-close-share-modal" aria-label="Close share modal">&times;</button>
        <div class="st-share-modal-head">
            <span class="st-share-icon-large">🔗</span>
            <h3>Share Your Okanagan Trip</h3>
            <p>Generate a unique, permanent link that opens this exact trip on any device or computer.</p>
        </div>

        <div class="st-share-link-box">
            <input type="text" id="st-share-url-input" class="st-share-url-input js-share-url-input" readonly value="" placeholder="Generating your trip link..." />
            <button type="button" class="st-btn st-btn--primary js-copy-share-url">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                <span class="js-copy-text">Copy link</span>
            </button>
        </div>

        <div class="st-share-quick-channels">
            <span class="st-share-channels-title">Or share directly:</span>
            <div class="st-share-channels-buttons">
                <!-- WhatsApp -->
                <a href="#" target="_blank" rel="noopener" class="st-share-channel-btn st-share-channel-btn--whatsapp js-share-whatsapp">
                    <span>WhatsApp</span>
                </a>
                <!-- Email -->
                <a href="#" class="st-share-channel-btn st-share-channel-btn--email js-share-email">
                    <span>Email</span>
                </a>
                <!-- Native Mobile Share (if available) -->
                <button type="button" class="st-share-channel-btn st-share-channel-btn--native js-share-native">
                    <span>More Options</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if ($is_shared && !empty($shared_posts)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Plot Map for Shared Trip
    const mapEl = document.getElementById('st-my-trip-map');
    if (mapEl && typeof L !== 'undefined') {
        const sharedMap = L.map('st-my-trip-map').setView([49.8880, -119.4960], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(sharedMap);
        const group = L.featureGroup().addTo(sharedMap);

        const markers = <?php
            $m_arr = array();
            foreach ($shared_posts as $idx => $p) {
                $lat = get_post_meta($p->ID, '_st_latitude', true);
                $lng = get_post_meta($p->ID, '_st_longitude', true);
                if ($lat && $lng) {
                    $m_arr[] = array(
                        'idx'   => ($idx + 1),
                        'title' => get_the_title($p),
                        'lat'   => floatval($lat),
                        'lng'   => floatval($lng),
                        'url'   => get_permalink($p),
                        'img'   => get_the_post_thumbnail_url($p, 'large') ?: get_post_meta($p->ID, '_st_cover_image', true),
                    );
                }
            }
            echo wp_json_encode($m_arr);
        ?>;

        markers.forEach(m => {
            const popup = `
                <div style="width:200px; font-family:var(--st-font);">
                    <img src="${m.img}" style="width:100%; height:110px; object-fit:cover; border-radius:8px; margin-bottom:6px;" />
                    <h4 style="font-size:13px; margin:0 0 4px 0; line-height:1.3;">Stop #${m.idx}: ${m.title}</h4>
                    <a href="${m.url}" style="display:inline-block; font-size:11px; font-weight:bold; background:#0F7B5F; color:#fff; padding:4px 10px; border-radius:6px; text-decoration:none;">View Tour &rarr;</a>
                </div>
            `;
            const marker = L.marker([m.lat, m.lng]).bindPopup(popup);
            group.addLayer(marker);
        });

        if (markers.length > 0) {
            try {
                sharedMap.fitBounds(group.getBounds(), { padding: [40, 40] });
            } catch (e) {}
        }
    }
});
</script>
<?php endif; ?>

<?php
get_footer();
