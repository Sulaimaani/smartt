<?php
/**
 * AJAX Filters, Faceted Search, Map Markers, and Quickview
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render single experience card (Grid or List view)
 */
function smarttours_render_experience_card($post_obj, $view = 'grid', $is_trip_view = false) {
    $post_id     = is_object($post_obj) ? $post_obj->ID : $post_obj;
    $post        = get_post($post_id);
    if (!$post) return '';

    $title       = get_the_title($post);
    $permalink   = get_permalink($post);
    $thumb_url   = get_the_post_thumbnail_url($post, 'st-card');
    if (!$thumb_url) {
        $thumb_url = get_post_meta($post->ID, '_st_cover_image', true) ?: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
    }

    $address     = get_post_meta($post->ID, '_st_address', true);
    $city        = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
    $price_from  = get_post_meta($post->ID, '_st_price_from', true);
    $duration    = get_post_meta($post->ID, '_st_duration', true);
    $lat         = get_post_meta($post->ID, '_st_latitude', true);
    $lng         = get_post_meta($post->ID, '_st_longitude', true);
    $full_addr   = trim(($address ? $address . ', ' : '') . $city);

    // Primary category tag
    $categories  = wp_get_object_terms($post->ID, 'experience_category');
    $primary_cat = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : 'Okanagan Tour';

    ob_start();
    ?>
    <article class="st-card st-card--<?php echo esc_attr($view); ?><?php echo $is_trip_view ? ' st-card--trip' : ''; ?>" data-id="<?php echo esc_attr($post->ID); ?>" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" data-city="<?php echo esc_attr($city); ?>">
        <div class="st-card__media">
            <a href="<?php echo esc_url($permalink); ?>" class="st-card__img-link" aria-label="<?php echo esc_attr($title); ?>">
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" class="st-card__img" />
            </a>

            <!-- Category tag -->
            <span class="st-card__cat-pill"><?php echo esc_html($primary_cat); ?></span>

            <!-- Heart Save to Trip Button -->
            <button type="button" class="st-card__heart js-toggle-trip" data-id="<?php echo esc_attr($post->ID); ?>" aria-label="<?php esc_attr_e('Toggle save to My Trip', 'smarttours'); ?>" title="Save to My Trip">
                <svg viewBox="0 0 24 24" class="st-icon-heart" width="20" height="20">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </button>

            <?php if (!empty($price_from)): ?>
                <span class="st-card__price-badge">
                    <?php echo is_numeric($price_from) ? 'From $' . esc_html($price_from) . ' CAD' : esc_html($price_from); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="st-card__body">
            <div class="st-card__meta-top">
                <span class="st-card__rating">★ 4.9</span>
                <?php if ($duration): ?>
                    <span class="st-card__duration">⏱ <?php echo esc_html($duration); ?></span>
                <?php endif; ?>
            </div>

            <h3 class="st-card__title">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
            </h3>

            <?php if ($full_addr): ?>
                <p class="st-card__location">
                    <svg viewBox="0 0 24 24" width="14" height="14" class="st-pin-icon" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span><?php echo esc_html($full_addr); ?></span>
                </p>
            <?php endif; ?>

            <?php if ($view === 'list' && !empty($post->post_excerpt)): ?>
                <p class="st-card__excerpt"><?php echo esc_html(wp_trim_words($post->post_excerpt, 22)); ?></p>
            <?php endif; ?>

            <div class="st-card__footer">
                <?php if ($is_trip_view): ?>
                    <button type="button" class="st-card__remove-trip js-remove-trip" data-id="<?php echo esc_attr($post->ID); ?>">
                        &times; Remove
                    </button>
                    <a href="<?php echo esc_url($permalink); ?>" class="st-card__book-now">
                        View &amp; Book &rarr;
                    </a>
                <?php else: ?>
                    <button type="button" class="st-card__quickview js-quickview" data-id="<?php echo esc_attr($post->ID); ?>">
                        QUICKVIEW
                    </button>
                    <a href="<?php echo esc_url($permalink); ?>" class="st-card__details">
                        DETAILS &gt;
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

/**
 * Handle AJAX experience filter request
 */
function smarttours_ajax_filter_experiences() {
    $term_id   = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    $keyword   = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $amenities = isset($_POST['amenities']) && is_array($_POST['amenities']) ? array_map('intval', $_POST['amenities']) : array();
    $sort      = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'recommended';
    $paged     = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $view      = isset($_POST['view']) && in_array($_POST['view'], array('grid', 'list')) ? $_POST['view'] : 'grid';
    $user_lat  = isset($_POST['user_lat']) && is_numeric($_POST['user_lat']) ? floatval($_POST['user_lat']) : null;
    $user_lng  = isset($_POST['user_lng']) && is_numeric($_POST['user_lng']) ? floatval($_POST['user_lng']) : null;
    $per_page  = 15;

    $args = array(
        'post_type'      => 'experience',
        'post_status'    => 'publish',
        'posts_per_page' => ($sort === 'near_me' && $user_lat !== null) ? 100 : $per_page,
        'paged'          => ($sort === 'near_me' && $user_lat !== null) ? 1 : $paged,
    );

    // Tax query
    $tax_query = array('relation' => 'AND');
    if ($term_id > 0) {
        $tax_query[] = array(
            'taxonomy'         => 'experience_category',
            'field'            => 'term_id',
            'terms'            => $term_id,
            'include_children' => true,
        );
    }
    if (!empty($amenities)) {
        $tax_query[] = array(
            'taxonomy'         => 'amenity',
            'field'            => 'term_id',
            'terms'            => $amenities,
            'operator'         => 'IN',
        );
    }
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    // Keyword search
    if (!empty($keyword)) {
        $args['s'] = $keyword;
    }

    // Sorting
    if ($sort === 'title_asc') {
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
    } else {
        $args['orderby'] = 'date';
        $args['order']   = 'DESC';
    }

    $query = new WP_Query($args);
    $posts = $query->posts;

    // Handle "Near Me" distance sorting if user coordinates provided
    if ($sort === 'near_me' && $user_lat !== null && $user_lng !== null && !empty($posts)) {
        usort($posts, function($a, $b) use ($user_lat, $user_lng) {
            $lat_a = floatval(get_post_meta($a->ID, '_st_latitude', true) ?: 49.88);
            $lng_a = floatval(get_post_meta($a->ID, '_st_longitude', true) ?: -119.49);
            $lat_b = floatval(get_post_meta($b->ID, '_st_latitude', true) ?: 49.88);
            $lng_b = floatval(get_post_meta($b->ID, '_st_longitude', true) ?: -119.49);

            $dist_a = smarttours_haversine_distance($user_lat, $user_lng, $lat_a, $lng_a);
            $dist_b = smarttours_haversine_distance($user_lat, $user_lng, $lat_b, $lng_b);

            return ($dist_a < $dist_b) ? -1 : 1;
        });

        // Slice for current page
        $total_found = count($posts);
        $max_pages   = ceil($total_found / $per_page);
        $offset      = ($paged - 1) * $per_page;
        $posts       = array_slice($posts, $offset, $per_page);
    } else {
        $total_found = $query->found_posts;
        $max_pages   = $query->max_num_pages;
    }

    $html = '';
    $markers = array();

    if (!empty($posts)) {
        foreach ($posts as $post_item) {
            $html .= smarttours_render_experience_card($post_item, $view);

            $lat = get_post_meta($post_item->ID, '_st_latitude', true);
            $lng = get_post_meta($post_item->ID, '_st_longitude', true);
            $img = get_the_post_thumbnail_url($post_item, 'st-thumb') ?: get_post_meta($post_item->ID, '_st_cover_image', true);
            $addr = get_post_meta($post_item->ID, '_st_address', true);
            $city = get_post_meta($post_item->ID, '_st_city', true) ?: 'Kelowna';
            $price = get_post_meta($post_item->ID, '_st_price_from', true);

            if ($lat && $lng) {
                $markers[] = array(
                    'id'        => $post_item->ID,
                    'title'     => get_the_title($post_item),
                    'lat'       => floatval($lat),
                    'lng'       => floatval($lng),
                    'url'       => get_permalink($post_item),
                    'image'     => $img,
                    'address'   => trim(($addr ? $addr . ', ' : '') . $city),
                    'price'     => $price ? ('$' . $price . ' CAD') : '',
                );
            }
        }
    } else {
        $html = '<div class="st-no-results"><p>' . __('No experiences match your current filters. Try expanding your search.', 'smarttours') . '</p></div>';
    }

    wp_send_json_success(array(
        'html'        => $html,
        'found_posts' => $total_found,
        'max_pages'   => $max_pages,
        'paged'       => $paged,
        'markers'     => $markers,
    ));
}
add_action('wp_ajax_smarttours_filter_experiences', 'smarttours_ajax_filter_experiences');
add_action('wp_ajax_nopriv_smarttours_filter_experiences', 'smarttours_ajax_filter_experiences');

/**
 * Calculate Haversine distance in KM
 */
function smarttours_haversine_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earth_radius * $c;
}

/**
 * AJAX Quickview Modal Handler
 */
function smarttours_ajax_quickview() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $post = get_post($id);
    if (!$post || $post->post_type !== 'experience') {
        wp_send_json_error(array('message' => 'Experience not found.'));
    }

    $title        = get_the_title($post);
    $permalink    = get_permalink($post);
    $img          = get_the_post_thumbnail_url($post, 'st-card') ?: get_post_meta($post->ID, '_st_cover_image', true);
    $address      = get_post_meta($post->ID, '_st_address', true);
    $city         = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
    $price_from   = get_post_meta($post->ID, '_st_price_from', true);
    $duration     = get_post_meta($post->ID, '_st_duration', true);
    $phone        = get_post_meta($post->ID, '_st_phone', true);
    $booking_type = get_post_meta($post->ID, '_st_booking_type', true);
    $amenities    = wp_get_object_terms($post->ID, 'amenity');

    ob_start();
    ?>
    <div class="st-quickview-content">
        <div class="st-quickview-media">
            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($title); ?>" />
        </div>
        <div class="st-quickview-info">
            <h2><?php echo esc_html($title); ?></h2>
            <p class="st-quickview-address">
                <strong>Location:</strong> <?php echo esc_html(trim(($address ? $address . ', ' : '') . $city)); ?>
            </p>
            <?php if ($phone): ?>
                <p><strong>Phone:</strong> <?php echo esc_html($phone); ?></p>
            <?php endif; ?>
            <div class="st-quickview-meta-row">
                <?php if ($price_from): ?>
                    <span class="st-meta-pill"><strong>Price:</strong> From $<?php echo esc_html($price_from); ?> CAD</span>
                <?php endif; ?>
                <?php if ($duration): ?>
                    <span class="st-meta-pill"><strong>Duration:</strong> <?php echo esc_html($duration); ?></span>
                <?php endif; ?>
            </div>

            <div class="st-quickview-excerpt">
                <?php echo wpautop($post->post_excerpt ?: wp_trim_words($post->post_content, 40)); ?>
            </div>

            <?php if (!empty($amenities) && !is_wp_error($amenities)): ?>
                <div class="st-quickview-amenities">
                    <strong>Amenities:</strong>
                    <div class="st-amenity-tags">
                        <?php foreach ($amenities as $am): ?>
                            <span class="st-amenity-tag"><?php echo esc_html($am->name); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="st-quickview-actions">
                <a href="<?php echo esc_url($permalink); ?>" class="st-btn st-btn--primary"><?php _e('VIEW FULL EXPERIENCE', 'smarttours'); ?></a>
                <?php if ($booking_type === 'affiliate_link' || $booking_type === 'external_link'): ?>
                    <a href="<?php echo esc_url(home_url('/go/' . $post->ID . '/')); ?>" target="_blank" rel="nofollow sponsored" class="st-btn st-btn--accent"><?php _e('BOOK NOW', 'smarttours'); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    $modal_html = ob_get_clean();
    wp_send_json_success(array('html' => $modal_html));
}
add_action('wp_ajax_smarttours_quickview', 'smarttours_ajax_quickview');
add_action('wp_ajax_nopriv_smarttours_quickview', 'smarttours_ajax_quickview');

/**
 * AJAX Handler for My Trip saved items
 */
function smarttours_ajax_get_trip_items() {
    $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? array_map('intval', $_POST['ids']) : array();

    if (empty($ids)) {
        wp_send_json_success(array('html' => '', 'markers' => array(), 'count' => 0));
    }

    $posts = get_posts(array(
        'post_type'      => 'experience',
        'post__in'       => $ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    ));

    $html = '';
    $markers = array();

    foreach ($posts as $post) {
        $html .= smarttours_render_experience_card($post, 'grid', true);

        $lat = get_post_meta($post->ID, '_st_latitude', true);
        $lng = get_post_meta($post->ID, '_st_longitude', true);
        $img = get_the_post_thumbnail_url($post, 'st-thumb') ?: get_post_meta($post->ID, '_st_cover_image', true);
        $addr = get_post_meta($post->ID, '_st_address', true);
        $city = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
        $price = get_post_meta($post->ID, '_st_price_from', true);

        if ($lat && $lng) {
            $markers[] = array(
                'id'        => $post->ID,
                'title'     => get_the_title($post),
                'lat'       => floatval($lat),
                'lng'       => floatval($lng),
                'url'       => get_permalink($post),
                'image'     => $img,
                'address'   => trim(($addr ? $addr . ', ' : '') . $city),
                'price'     => $price ? ('$' . $price . ' CAD') : '',
            );
        }
    }

    wp_send_json_success(array(
        'html'    => $html,
        'markers' => $markers,
        'count'   => count($posts)
    ));
}
add_action('wp_ajax_smarttours_get_trip_items', 'smarttours_ajax_get_trip_items');
add_action('wp_ajax_nopriv_smarttours_get_trip_items', 'smarttours_ajax_get_trip_items');
