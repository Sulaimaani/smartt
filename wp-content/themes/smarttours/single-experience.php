<?php
/**
 * Single Experience Template
 *
 * @package SmartTours
 */

get_header();

$experience_id       = get_the_ID();
$address             = get_post_meta($experience_id, '_st_address', true);
$city                = get_post_meta($experience_id, '_st_city', true) ?: 'Kelowna';
$postal_code         = get_post_meta($experience_id, '_st_postal_code', true);
$latitude            = get_post_meta($experience_id, '_st_latitude', true) ?: '49.8880';
$longitude           = get_post_meta($experience_id, '_st_longitude', true) ?: '-119.4960';
$phone               = get_post_meta($experience_id, '_st_phone', true);
$website_url         = get_post_meta($experience_id, '_st_website_url', true);
$price_from          = get_post_meta($experience_id, '_st_price_from', true);
$duration            = get_post_meta($experience_id, '_st_duration', true);
$facebook            = get_post_meta($experience_id, '_st_facebook', true);
$instagram           = get_post_meta($experience_id, '_st_instagram', true);
$twitter             = get_post_meta($experience_id, '_st_twitter', true);
$booking_type        = get_post_meta($experience_id, '_st_booking_type', true) ?: 'none';
$booking_url         = get_post_meta($experience_id, '_st_booking_url', true);
$booking_widget_code = get_post_meta($experience_id, '_st_booking_widget_code', true);
$gallery_raw         = get_post_meta($experience_id, '_st_gallery_ids', true);

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
$gallery_images = array_unique($gallery_images);
$gallery_count  = count($gallery_images);

// Categories & Amenities
$categories = wp_get_object_terms($experience_id, 'experience_category');
$amenities  = wp_get_object_terms($experience_id, 'amenity');
$full_address = trim(($address ? $address . ', ' : '') . $city . ($postal_code ? ' ' . $postal_code : ''));
?>

<main id="primary" class="site-main st-single-experience" role="main">
    <!-- Top Section: Beige Background with Large Decorative Circle Shape -->
    <section class="st-single-hero">
        <div class="st-container">
            <div style="margin-bottom: 25px;">
                <?php smarttours_render_breadcrumbs(); ?>
            </div>

            <div class="st-single-hero__grid">
                <!-- Gallery Left -->
                <div class="st-single-gallery">
                    <img src="<?php echo esc_url($gallery_images[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="st-single-gallery__main-img js-gallery-main" />
                    <div class="st-gallery-controls">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M4 4h3v2H4v3H2V4a2 2 0 0 1 2-2zm16 0a2 2 0 0 1 2 2v5h-2V6h-3V4h3zM2 20v-5h2v3h3v2H4a2 2 0 0 1-2-2zm18 0h-3v-2h3v-3h2v5a2 2 0 0 1-2 2z"/></svg>
                        <span class="js-gallery-counter">1/<?php echo $gallery_count; ?></span>
                    </div>

                    <?php if ($gallery_count > 1): ?>
                        <div style="display:flex; gap:8px; padding:10px; background:#fff; overflow-x:auto;">
                            <?php foreach ($gallery_images as $idx => $g_img): ?>
                                <img src="<?php echo esc_url($g_img); ?>" alt="Thumbnail <?php echo $idx + 1; ?>" class="js-thumb-item" data-full="<?php echo esc_url($g_img); ?>" style="width:60px; height:45px; object-fit:cover; cursor:pointer; border-radius:2px;" />
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Side: Add to Trip, Share, Title, Primary Button -->
                <div class="st-single-hero__info">
                    <div class="st-single-actions-bar">
                        <button type="button" class="st-action-btn js-toggle-trip" data-id="<?php echo esc_attr($experience_id); ?>" aria-label="<?php esc_attr_e('Add to Trip', 'smarttours'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="st-icon-heart"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Add to Trip</span>
                        </button>
                        <button type="button" class="st-action-btn js-share-btn" aria-label="<?php esc_attr_e('Share this experience', 'smarttours'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                            <span>Share</span>
                        </button>
                    </div>

                    <h1 class="st-single-title"><?php the_title(); ?></h1>

                    <!-- Primary Button Logic -->
                    <div style="margin-top: 10px;">
                        <?php if ($booking_type === 'booking_widget'): ?>
                            <a href="#st-booking-widget-section" class="st-primary-btn" onclick="document.getElementById('st-booking-widget-section').scrollIntoView({behavior:'smooth'}); return false;">
                                <?php _e('BOOK NOW', 'smarttours'); ?> &darr;
                            </a>
                        <?php elseif ($booking_type === 'affiliate_link' || $booking_type === 'external_link'): ?>
                            <a href="<?php echo esc_url(home_url('/go/' . $experience_id . '/')); ?>" target="_blank" rel="nofollow sponsored" class="st-primary-btn">
                                <?php _e('BOOK NOW', 'smarttours'); ?> &rarr;
                            </a>
                        <?php elseif ($booking_type === 'none' && !empty($website_url)): ?>
                            <a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener" class="st-primary-btn">
                                <?php _e('VISIT WEBSITE', 'smarttours'); ?> &rarr;
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="st-primary-btn">
                                <?php _e('INQUIRE NOW', 'smarttours'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Details, Description & Amenities -->
    <div class="st-container st-single-body">
        <!-- Location, Phone, Price & Social Meta Strip -->
        <div class="st-single-meta-strip">
            <?php if (!empty($full_address)): ?>
                <div class="st-meta-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span><?php echo esc_html($full_address); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($phone)): ?>
                <div class="st-meta-item">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57a1 1 0 01-.25 1.02l-2.2 2.2z"/></svg>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                </div>
            <?php endif; ?>

            <?php if (!empty($price_from)): ?>
                <div class="st-meta-item">
                    <strong>Price:</strong>
                    <span><?php echo is_numeric($price_from) ? 'From $' . esc_html($price_from) . ' CAD' : esc_html($price_from); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($duration)): ?>
                <div class="st-meta-item">
                    <strong>Duration:</strong>
                    <span><?php echo esc_html($duration); ?></span>
                </div>
            <?php endif; ?>

            <!-- Social Links -->
            <?php if ($facebook || $instagram || $twitter): ?>
                <div class="st-social-links" style="margin-left: auto;">
                    <?php if ($facebook): ?>
                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" class="st-social-circle" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="#000"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if ($instagram): ?>
                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" class="st-social-circle" aria-label="Instagram">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#000" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
                        </a>
                    <?php endif; ?>
                    <?php if ($twitter): ?>
                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener" class="st-social-circle" aria-label="X">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="#000"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Description & Amenities -->
        <div class="st-single-content-grid">
            <div class="st-experience-description">
                <h2><?php _e('About this Experience', 'smarttours'); ?></h2>
                <?php the_content(); ?>

                <!-- Amenities Pills -->
                <?php if (!empty($amenities) && !is_wp_error($amenities)): ?>
                    <div class="st-amenities-section">
                        <h3><?php _e('Amenities & Features', 'smarttours'); ?></h3>
                        <div class="st-amenities-pills">
                            <?php foreach ($amenities as $am): ?>
                                <span class="st-amenity-pill"><?php echo esc_html($am->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Booking Widget Embed Section -->
                <?php if ($booking_type === 'booking_widget' && !empty($booking_widget_code)): ?>
                    <div id="st-booking-widget-section" class="st-booking-widget-section">
                        <h3 style="font-size: 1.8rem; margin-bottom: 15px;"><?php _e('Book Your Experience', 'smarttours'); ?></h3>
                        <div class="st-widget-embed-frame">
                            <?php echo $booking_widget_code; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Interactive Leaflet Map for this location -->
                <div style="margin: 40px 0;">
                    <h3><?php _e('Location & Map', 'smarttours'); ?></h3>
                    <div id="st-single-map" style="height: 380px; width: 100%; border: 1px solid var(--border); border-radius: 4px; margin-top: 15px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- "You May Also Like" (4 experiences from the same category) -->
    <section class="st-related-section">
        <div class="st-container">
            <h2 style="font-size: 2.2rem; margin-bottom: 30px;"><?php _e('YOU MAY ALSO LIKE', 'smarttours'); ?></h2>
            <div class="st-experience-grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
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
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Single Location Map
    const mapEl = document.getElementById('st-single-map');
    if (mapEl && typeof L !== 'undefined') {
        const lat = <?php echo floatval($latitude); ?>;
        const lng = <?php echo floatval($longitude); ?>;
        const singleMap = L.map('st-single-map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(singleMap);
        L.marker([lat, lng]).addTo(singleMap)
            .bindPopup('<strong><?php echo esc_js(get_the_title()); ?></strong><br><?php echo esc_js($full_address); ?>')
            .openPopup();
    }
});
</script>

<?php
get_footer();
