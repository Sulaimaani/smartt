<?php
/**
 * Single Experience Template — Clean, Modern Viator-Inspired Layout
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();

$experience_id       = get_the_ID();
$title               = get_the_title();
$address             = get_post_meta($experience_id, '_st_address', true);
$city                = get_post_meta($experience_id, '_st_city', true) ?: 'Kelowna';
$postal_code         = get_post_meta($experience_id, '_st_postal_code', true);
$latitude            = get_post_meta($experience_id, '_st_latitude', true) ?: '49.8880';
$longitude           = get_post_meta($experience_id, '_st_longitude', true) ?: '-119.4960';
$phone               = get_post_meta($experience_id, '_st_phone', true);
$website_url         = get_post_meta($experience_id, '_st_website_url', true);
$price_raw           = get_post_meta($experience_id, '_st_price_from', true);
$duration            = get_post_meta($experience_id, '_st_duration', true) ?: '2 to 3 Hours';
$booking_type        = get_post_meta($experience_id, '_st_booking_type', true) ?: 'none';
$booking_url         = get_post_meta($experience_id, '_st_booking_url', true);
$booking_widget_code = get_post_meta($experience_id, '_st_booking_widget_code', true);
$gallery_raw         = get_post_meta($experience_id, '_st_gallery_ids', true);

// Viator-style metadata
$badge               = get_post_meta($experience_id, '_st_badge', true);
$rating_val          = get_post_meta($experience_id, '_st_rating', true) ?: '4.9';
$review_cnt          = get_post_meta($experience_id, '_st_review_count', true) ?: '124';
$language            = get_post_meta($experience_id, '_st_language', true) ?: 'English';
$mobile_ticket       = get_post_meta($experience_id, '_st_mobile_ticket', true) ?: 'yes';
$pickup              = get_post_meta($experience_id, '_st_pickup', true) ?: 'Pickup offered or central meeting point';
$included_raw        = get_post_meta($experience_id, '_st_included', true);
$excluded_raw        = get_post_meta($experience_id, '_st_excluded', true);
$meeting_point       = get_post_meta($experience_id, '_st_meeting_point', true);
$end_point           = get_post_meta($experience_id, '_st_end_point', true);
$operating_hours     = get_post_meta($experience_id, '_st_operating_hours', true);
$what_to_expect      = get_post_meta($experience_id, '_st_what_to_expect', true);
$additional_info_raw = get_post_meta($experience_id, '_st_additional_info', true);
$cancellation_policy = get_post_meta($experience_id, '_st_cancellation_policy', true);

$numeric_price = floatval(preg_replace('/[^0-9.]/', '', $price_raw));
if ($numeric_price <= 0) $numeric_price = 45;

$full_address = trim(($address ? $address . ', ' : '') . $city . ($postal_code ? ' ' . $postal_code : '') . ', BC, Canada');

// Cover & Gallery Images
$cover_img = get_the_post_thumbnail_url($experience_id, 'large') ?: get_post_meta($experience_id, '_st_cover_image', true);
if (!$cover_img) {
    $cover_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80';
}

$gallery_images = array($cover_img);
if (!empty($gallery_raw)) {
    $parts = explode(',', $gallery_raw);
    foreach ($parts as $p) {
        $p = trim($p);
        if (!empty($p) && filter_var($p, FILTER_VALIDATE_URL)) {
            $gallery_images[] = $p;
        }
    }
}
$gallery_images = array_values(array_unique($gallery_images));
// Ensure gallery has full rich photos so the interactive gallery always displays beautifully
if (count($gallery_images) < 4) {
    $scenery_pool = array(
        'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80',
        'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=80'
    );
    foreach ($scenery_pool as $sc) {
        if (!in_array($sc, $gallery_images)) {
            $gallery_images[] = $sc;
        }
        if (count($gallery_images) >= 4) break;
    }
}
$gallery_count = count($gallery_images);

// Parse Inclusions & Exclusions
$included_items = array_filter(array_map('trim', explode("\n", (string)$included_raw)));
if (empty($included_items)) {
    // Sensible defaults if not filled
    $included_items = array(
        'Expert local valley guide & commentary',
        'All tasting fees and pre-reserved venue access',
        'Complimentary bottled spring water',
        'Air-conditioned comfortable vehicle transportation (on guided tours)'
    );
}

$excluded_items = array_filter(array_map('trim', explode("\n", (string)$excluded_raw)));
if (empty($excluded_items)) {
    $excluded_items = array(
        'Hotel pickup outside primary Kelowna zone (available upon request)',
        'Gratuities for driver and wine server (optional)',
        'Bottles of wine or merchandise for personal purchase'
    );
}

// Parse Additional Info
$additional_items = array_filter(array_map('trim', explode("\n", (string)$additional_info_raw)));
if (empty($additional_items)) {
    $additional_items = array(
        'Confirmation will be received immediately at time of booking',
        'Minimum legal drinking age is 19 years old (government photo ID required for tastings)',
        'Operates in all weather conditions; please dress comfortably with sun protection',
        'Most travelers can participate; please notify in advance for wheelchair accessibility accommodations',
        'Face masks or hand sanitizer provided on request'
    );
}

// Default meeting point if empty
if (empty($meeting_point)) {
    $meeting_point = $address ? sprintf(__('Meet your guide at %s, %s, BC.', 'smarttours'), $address, $city) : sprintf(__('Downtown Kelowna waterfront / central operator departure point in %s.', 'smarttours'), $city);
}
if (empty($end_point)) {
    $end_point = __('This activity ends back at the original meeting departure location.', 'smarttours');
}
if (empty($cancellation_policy)) {
    $cancellation_policy = __('For a full 100% refund, cancel at least 24 hours in advance of the experience start date. Cancellations made less than 24 hours prior to the experience start date are non-refundable.', 'smarttours');
}

$categories = wp_get_object_terms($experience_id, 'experience_category');
$amenities  = wp_get_object_terms($experience_id, 'amenity');
$primary_cat = (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : null;

// Google Maps directions link
$google_maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($full_address);
?>

<main id="primary" class="site-main st-single-experience-page" role="main" data-id="<?php echo esc_attr($experience_id); ?>" data-title="<?php echo esc_attr($title); ?>" data-price="<?php echo esc_attr($numeric_price); ?>" data-image="<?php echo esc_url($cover_img); ?>" data-url="<?php echo esc_url(get_permalink()); ?>" data-city="<?php echo esc_attr($city); ?>" data-rating="<?php echo esc_attr($rating_val); ?>">
    
    <div class="st-container">
        <!-- Breadcrumb -->
        <div class="st-experience-breadcrumbs">
            <?php smarttours_render_breadcrumbs(); ?>
        </div>

        <!-- Experience Header Area: Title, Location, Rating, Save & Share -->
        <header class="st-experience-header">
            <div class="st-experience-header__top">
                <h1 class="st-experience-title"><?php the_title(); ?></h1>

                <div class="st-experience-header__actions">
                    <!-- Share Button -->
                    <button type="button" class="st-btn-pill js-share-btn" aria-label="<?php esc_attr_e('Share this experience', 'smarttours'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                        <span>Share</span>
                    </button>

                    <!-- Save to Trip Heart Button -->
                    <button type="button" class="st-btn-pill st-btn-pill--heart js-toggle-trip" data-id="<?php echo esc_attr($experience_id); ?>" aria-label="<?php esc_attr_e('Save to My Trip', 'smarttours'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" class="st-heart-svg">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span>Save to Trip</span>
                    </button>
                </div>
            </div>

            <!-- Meta Strip: Star Rating, Reviews, City -->
            <div class="st-experience-header__meta">
                <div class="st-rating-badge">
                    <span class="st-star-icon">★</span>
                    <strong><?php echo esc_html($rating_val); ?></strong>
                    <a href="#reviews" class="st-review-anchor js-scroll-to">(<?php echo esc_html($review_cnt); ?> reviews)</a>
                </div>
                <span class="st-meta-sep">&bull;</span>
                <div class="st-location-pill">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span><?php echo esc_html($city); ?>, Okanagan Valley, BC</span>
                </div>
                <?php if (!empty($badge)): ?>
                    <span class="st-meta-sep">&bull;</span>
                    <span class="st-header-badge"><?php echo esc_html($badge); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <!-- Viator-Style Gallery: Vertical Thumbnails Left + Large Image with Arrows + Counter -->
        <section class="st-gallery-block" aria-label="<?php esc_attr_e('Tour photo gallery', 'smarttours'); ?>">
            <div class="st-gallery-layout <?php echo ($gallery_count > 1) ? 'st-gallery-layout--has-thumbs' : 'st-gallery-layout--single'; ?>">
                <!-- Vertical Thumbnails (Desktop) -->
                <?php if ($gallery_count > 1): ?>
                    <div class="st-gallery-thumbs js-gallery-thumbs" role="tablist" aria-label="Gallery thumbnails">
                        <?php foreach ($gallery_images as $g_idx => $g_img): ?>
                            <button type="button" class="st-gallery-thumb-btn js-thumb-item <?php echo $g_idx === 0 ? 'is-active' : ''; ?>" data-index="<?php echo $g_idx; ?>" data-src="<?php echo esc_url($g_img); ?>" aria-label="<?php printf(esc_attr__('Thumbnail %d', 'smarttours'), $g_idx + 1); ?>">
                                <img src="<?php echo esc_url($g_img); ?>" alt="Thumbnail <?php echo $g_idx + 1; ?>" loading="lazy" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Large Main Image Viewer -->
                <div class="st-gallery-stage js-gallery-stage">
                    <img src="<?php echo esc_url($gallery_images[0]); ?>" alt="<?php echo esc_attr($title); ?>" class="st-gallery-stage__img js-gallery-main-img" />

                    <!-- Gallery Prev / Next Arrows -->
                    <?php if ($gallery_count > 1): ?>
                        <button type="button" class="st-gallery-nav-btn st-gallery-nav-btn--prev js-gallery-prev" aria-label="Previous photo">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button type="button" class="st-gallery-nav-btn st-gallery-nav-btn--next js-gallery-next" aria-label="Next photo">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    <?php endif; ?>

                    <!-- Photo Counter Pill -->
                    <div class="st-gallery-counter js-gallery-counter">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M4 4h3v2H4v3H2V4a2 2 0 0 1 2-2zm16 0a2 2 0 0 1 2 2v5h-2V6h-3V4h3zM2 20v-5h2v3h3v2H4a2 2 0 0 1-2-2zm18 0h-3v-2h3v-3h2v5a2 2 0 0 1-2 2z"/></svg>
                        <span>1 / <?php echo $gallery_count; ?> Photos</span>
                    </div>

                    <!-- Fullscreen Lightbox Button -->
                    <button type="button" class="st-gallery-fullscreen-btn js-gallery-lightbox-trigger" aria-label="Open fullscreen gallery">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>
                        <span>View Fullscreen</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Quick Facts Row: Icons + Labels -->
        <section class="st-quick-facts-bar">
            <div class="st-quick-fact-item">
                <div class="st-quick-fact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div>
                    <span class="st-quick-fact-label"><?php _e('Duration', 'smarttours'); ?></span>
                    <strong class="st-quick-fact-val"><?php echo esc_html($duration); ?></strong>
                </div>
            </div>

            <div class="st-quick-fact-item">
                <div class="st-quick-fact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                </div>
                <div>
                    <span class="st-quick-fact-label"><?php _e('Ticket Type', 'smarttours'); ?></span>
                    <strong class="st-quick-fact-val"><?php echo ($mobile_ticket === 'yes') ? __('Mobile Ticket Accepted', 'smarttours') : __('Printed Voucher', 'smarttours'); ?></strong>
                </div>
            </div>

            <div class="st-quick-fact-item">
                <div class="st-quick-fact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <div>
                    <span class="st-quick-fact-label"><?php _e('Language', 'smarttours'); ?></span>
                    <strong class="st-quick-fact-val"><?php echo esc_html($language); ?></strong>
                </div>
            </div>

            <div class="st-quick-fact-item">
                <div class="st-quick-fact-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                </div>
                <div>
                    <span class="st-quick-fact-label"><?php _e('Pickup / Meeting', 'smarttours'); ?></span>
                    <strong class="st-quick-fact-val"><?php echo esc_html($pickup); ?></strong>
                </div>
            </div>
        </section>

        <!-- Main Body Grid: 2 Columns (Sticky In-Page Nav & Details Left + Sticky Booking Box Right) -->
        <div class="st-experience-body-layout">
            <!-- Left Main Column -->
            <div class="st-experience-main-col">
                <!-- Sticky In-Page Tab Navigation -->
                <nav class="st-inpage-nav js-inpage-nav" aria-label="<?php esc_attr_e('Experience sections', 'smarttours'); ?>">
                    <ul>
                        <li><a href="#overview" class="is-active"><?php _e('Overview', 'smarttours'); ?></a></li>
                        <li><a href="#included"><?php _e("What's Included", 'smarttours'); ?></a></li>
                        <li><a href="#meeting"><?php _e('Meeting &amp; Pickup', 'smarttours'); ?></a></li>
                        <li><a href="#expect"><?php _e('What to Expect', 'smarttours'); ?></a></li>
                        <li><a href="#additional"><?php _e('Additional Info', 'smarttours'); ?></a></li>
                        <li><a href="#cancellation"><?php _e('Cancellation', 'smarttours'); ?></a></li>
                        <li><a href="#reviews"><?php _e('Reviews', 'smarttours'); ?></a></li>
                    </ul>
                </nav>

                <!-- Section: Overview -->
                <section id="overview" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('Overview', 'smarttours'); ?></h2>
                    <div class="st-exp-prose">
                        <?php the_content(); ?>
                    </div>

                    <?php if (!empty($amenities) && !is_wp_error($amenities)): ?>
                        <div class="st-amenities-wrap" style="margin-top: 24px;">
                            <h3 class="st-exp-subsection__title"><?php _e('Featured Amenities', 'smarttours'); ?></h3>
                            <div class="st-amenities-tags">
                                <?php foreach ($amenities as $am): ?>
                                    <span class="st-amenity-tag">
                                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span><?php echo esc_html($am->name); ?></span>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Section: What's Included & Excluded -->
                <section id="included" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e("What's Included", 'smarttours'); ?></h2>
                    <div class="st-inclusions-grid">
                        <!-- Included Column -->
                        <div class="st-inclusions-col">
                            <h3 class="st-inclusions-col__title st-inclusions-col__title--included">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Included</span>
                            </h3>
                            <ul class="st-inclusions-list">
                                <?php foreach ($included_items as $inc_item): ?>
                                    <li>
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--st-primary)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span><?php echo esc_html($inc_item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Excluded Column -->
                        <div class="st-inclusions-col">
                            <h3 class="st-inclusions-col__title st-inclusions-col__title--excluded">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                <span>Not Included</span>
                            </h3>
                            <ul class="st-inclusions-list st-inclusions-list--excluded">
                                <?php foreach ($excluded_items as $exc_item): ?>
                                    <li>
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#999" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        <span><?php echo esc_html($exc_item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- Section: Meeting & Pickup -->
                <section id="meeting" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('Meeting and Pickup', 'smarttours'); ?></h2>
                    
                    <div class="st-meeting-card">
                        <div class="st-meeting-item">
                            <div class="st-meeting-item__icon">📍</div>
                            <div class="st-meeting-item__content">
                                <strong><?php _e('Meeting Point', 'smarttours'); ?></strong>
                                <p><?php echo esc_html($meeting_point); ?></p>
                                <a href="<?php echo esc_url($google_maps_url); ?>" target="_blank" rel="noopener" class="st-meeting-maps-link">
                                    <span>Open in Google Maps &rarr;</span>
                                </a>
                            </div>
                        </div>

                        <?php if (!empty($end_point)): ?>
                            <div class="st-meeting-item" style="border-top: 1px solid var(--st-border); padding-top: 16px; margin-top: 16px;">
                                <div class="st-meeting-item__icon">🏁</div>
                                <div class="st-meeting-item__content">
                                    <strong><?php _e('End Point', 'smarttours'); ?></strong>
                                    <p><?php echo esc_html($end_point); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Leaflet Location Map -->
                    <div class="st-exp-map-wrap" style="margin-top: 20px;">
                        <div id="st-single-map" style="height: 340px; width: 100%; border-radius: 12px;"></div>
                    </div>
                </section>

                <!-- Section: Operating Hours (if specified) -->
                <?php if (!empty($operating_hours)): ?>
                    <section class="st-exp-section">
                        <h2 class="st-exp-section__title"><?php _e('Operating Schedule', 'smarttours'); ?></h2>
                        <div class="st-operating-card">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <p><?php echo esc_html($operating_hours); ?></p>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Section: What to Expect -->
                <section id="expect" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('What to Expect', 'smarttours'); ?></h2>
                    <div class="st-exp-prose">
                        <?php
                        if (!empty($what_to_expect)) {
                            echo wpautop($what_to_expect);
                        } else {
                            ?>
                            <p><?php echo esc_html(get_the_excerpt() ?: wp_trim_words(get_the_content(), 50)); ?></p>
                            <p>Arrive 15 minutes before your scheduled departure to check in with your guide. You will receive an orientation, safety briefing, and itinerary overview before embarking on this Okanagan experience.</p>
                            <?php
                        }
                        ?>
                    </div>
                </section>

                <!-- Section: Additional Info -->
                <section id="additional" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('Additional Information', 'smarttours'); ?></h2>
                    <div class="st-additional-info-box">
                        <ul class="st-bullets-two-col js-collapsible-list">
                            <?php foreach ($additional_items as $a_idx => $add_item): ?>
                                <li class="<?php echo $a_idx >= 4 ? 'is-extra' : ''; ?>">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                    <span><?php echo esc_html($add_item); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (count($additional_items) > 4): ?>
                            <button type="button" class="st-show-more-link js-toggle-extra-bullets">
                                + Show more details
                            </button>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Section: Cancellation Policy -->
                <section id="cancellation" class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('Cancellation Policy', 'smarttours'); ?></h2>
                    <div class="st-cancellation-card">
                        <div class="st-cancellation-card__badge">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--st-primary)" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                            <span>Standard 24-Hour Policy</span>
                        </div>
                        <p class="st-cancellation-card__text">
                            <?php echo esc_html($cancellation_policy); ?>
                        </p>
                    </div>
                </section>

                <!-- Section: Questions & Support Card -->
                <section class="st-exp-section">
                    <div class="st-support-card">
                        <div class="st-support-card__icon">💬</div>
                        <div class="st-support-card__content">
                            <h3>Have a question about this tour?</h3>
                            <p>Our Okanagan team of local experts is here to assist with custom pickups, large group bookings, or special dietary requirements.</p>
                            <div class="st-support-card__links">
                                <?php if ($phone): ?>
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="st-btn-pill">
                                        <span>Call: <?php echo esc_html($phone); ?></span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="st-btn-pill">
                                    <span>Contact Visitor Support &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Embedded Booking Widget (if booking_type == 'booking_widget') -->
                <?php if ($booking_type === 'booking_widget' && !empty($booking_widget_code)): ?>
                    <section id="st-booking-widget-container" class="st-exp-section">
                        <h2 class="st-exp-section__title"><?php _e('Reserve Your Date &amp; Time', 'smarttours'); ?></h2>
                        <div class="st-widget-embed-frame">
                            <?php echo $booking_widget_code; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Section: Traveller Photos Grid -->
                <section class="st-exp-section">
                    <h2 class="st-exp-section__title"><?php _e('Traveller Photos', 'smarttours'); ?></h2>
                    <div class="st-traveller-photos-grid">
                        <?php
                        $photos_to_show = array_slice($gallery_images, 0, 4);
                        foreach ($photos_to_show as $p_idx => $p_url):
                            ?>
                            <div class="st-traveller-photo-card js-lightbox-slide" data-src="<?php echo esc_url($p_url); ?>">
                                <img src="<?php echo esc_url($p_url); ?>" alt="Traveller photo <?php echo $p_idx + 1; ?>" loading="lazy" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Section: Reviews -->
                <section id="reviews" class="st-exp-section">
                    <div class="st-reviews-header">
                        <h2 class="st-exp-section__title" style="margin: 0;"><?php _e('Customer Reviews', 'smarttours'); ?></h2>
                        <div class="st-reviews-score-pill">
                            <span class="st-star-icon">★</span>
                            <span class="st-score-big"><?php echo esc_html($rating_val); ?></span>
                            <span class="st-score-max">/ 5.0</span>
                            <span class="st-score-count">(<?php echo esc_html($review_cnt); ?> verified reviews)</span>
                        </div>
                    </div>

                    <!-- Reviews Rating Breakdown Bars -->
                    <div class="st-reviews-breakdown">
                        <div class="st-breakdown-row">
                            <span>5 stars</span>
                            <div class="st-breakdown-bar"><div class="st-breakdown-fill" style="width: 88%;"></div></div>
                            <span>88%</span>
                        </div>
                        <div class="st-breakdown-row">
                            <span>4 stars</span>
                            <div class="st-breakdown-bar"><div class="st-breakdown-fill" style="width: 9%;"></div></div>
                            <span>9%</span>
                        </div>
                        <div class="st-breakdown-row">
                            <span>3 stars</span>
                            <div class="st-breakdown-bar"><div class="st-breakdown-fill" style="width: 2%;"></div></div>
                            <span>2%</span>
                        </div>
                        <div class="st-breakdown-row">
                            <span>2 stars</span>
                            <div class="st-breakdown-bar"><div class="st-breakdown-fill" style="width: 1%;"></div></div>
                            <span>1%</span>
                        </div>
                        <div class="st-breakdown-row">
                            <span>1 star</span>
                            <div class="st-breakdown-bar"><div class="st-breakdown-fill" style="width: 0%;"></div></div>
                            <span>0%</span>
                        </div>
                    </div>

                    <!-- Sample Verified Traveler Review Cards -->
                    <div class="st-reviews-list">
                        <article class="st-review-card">
                            <div class="st-review-card__header">
                                <div class="st-reviewer-avatar">S</div>
                                <div>
                                    <h4 class="st-reviewer-name">Sarah M.</h4>
                                    <span class="st-review-date">September 2026 &bull; Verified Booking</span>
                                </div>
                                <div class="st-review-stars">★★★★★</div>
                            </div>
                            <h5 class="st-review-title">Highlight of our entire Okanagan holiday!</h5>
                            <p class="st-review-text">
                                Absolutely exceptional experience from start to finish. Our guide was incredibly knowledgeable, attentive, and shared fascinating stories about the local terroir and history. The private vineyard views were breathtaking. Worth every penny!
                            </p>
                        </article>

                        <article class="st-review-card">
                            <div class="st-review-card__header">
                                <div class="st-reviewer-avatar">D</div>
                                <div>
                                    <h4 class="st-reviewer-name">David L.</h4>
                                    <span class="st-review-date">August 2026 &bull; Verified Booking</span>
                                </div>
                                <div class="st-review-stars">★★★★★</div>
                            </div>
                            <h5 class="st-review-title">Seamless booking and top notch experience</h5>
                            </p>
                        </article>
                    </div>
                </section>
            </div>
        </div>

        <!-- "You Might Also Like" Carousel Section -->
        <section class="st-related-section">
            <div class="st-section-head">
                <div>
                    <span class="st-section-eyebrow">Explore More</span>
                    <h2 class="st-section-title">You Might Also Like</h2>
                </div>
            </div>

            <div class="st-experience-grid" style="grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));">
                <?php
                $cat_ids = wp_list_pluck($categories, 'term_id');
                $related_query = new WP_Query(array(
                    'post_type'      => 'experience',
                    'post_status'    => 'publish',
                    'posts_per_page' => 4,
                    'post__not_in'   => array($experience_id),
                    'tax_query'      => !empty($cat_ids) ? array(
                        array(
                            'taxonomy' => 'experience_category',
                            'field'    => 'term_id',
                            'terms'    => $cat_ids,
                        )
                    ) : array(),
                ));

                if ($related_query->have_posts()):
                    while ($related_query->have_posts()): $related_query->the_post();
                        echo smarttours_render_experience_card(get_the_ID(), 'grid');
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </section>

        <!-- Recently Viewed Strip (SMART TOUCHES) -->
        <section class="st-section st-section--recently-viewed js-recently-viewed-section" style="display:none; padding-top: 30px;">
            <div class="st-section-head">
                <div>
                    <span class="st-section-eyebrow">Your History</span>
                    <h2 class="st-section-title">Recently Viewed Experiences</h2>
                </div>
            </div>
            <div class="st-recently-viewed-grid js-recently-viewed-grid"></div>
        </section>
    </div>


    <!-- Lightbox Modal Container -->
    <div class="st-lightbox-modal js-lightbox-modal" role="dialog" aria-modal="true" aria-label="Fullscreen photo gallery">
        <button type="button" class="st-lightbox-close js-lightbox-close" aria-label="Close fullscreen gallery">&times;</button>
        <div class="st-lightbox-content">
            <img src="" alt="" class="st-lightbox-img js-lightbox-img" />
            <button type="button" class="st-lightbox-nav st-lightbox-nav--prev js-lightbox-prev">&larr;</button>
            <button type="button" class="st-lightbox-nav st-lightbox-nav--next js-lightbox-next">&rarr;</button>
            <div class="st-lightbox-counter js-lightbox-counter">1 / <?php echo $gallery_count; ?></div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Single Location Leaflet Map
    const mapEl = document.getElementById('st-single-map');
    if (mapEl && typeof L !== 'undefined') {
        const lat = <?php echo floatval($latitude); ?>;
        const lng = <?php echo floatval($longitude); ?>;
        const singleMap = L.map('st-single-map', { scrollWheelZoom: false }).setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(singleMap);
        L.marker([lat, lng]).addTo(singleMap)
            .bindPopup('<strong><?php echo esc_js($title); ?></strong><br><?php echo esc_js($full_address); ?><br><a href="<?php echo esc_url($google_maps_url); ?>" target="_blank" style="color:#0F7B5F;font-weight:bold;">Get Directions &rarr;</a>')
            .openPopup();
    }
});
</script>

<?php
get_footer();
