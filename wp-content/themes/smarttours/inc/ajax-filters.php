<?php
/**
 * AJAX Filters, Live Search, Faceted Search, Map Markers, and Trip Storage
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ensure custom database table for trips exists
 */
function smarttours_create_trips_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_trips';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        share_code varchar(32) NOT NULL UNIQUE,
        experience_ids longtext NOT NULL,
        notes longtext NULL,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY share_code (share_code),
        KEY created_at (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'smarttours_create_trips_table');
add_action('admin_init', function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_trips';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        smarttours_create_trips_table();
    }
});

/**
 * Render single experience card (Clean, modern Viator-inspired layout)
 */
function smarttours_render_experience_card($post_obj, $view = 'grid', $is_trip_view = false, $trip_date = '', $trip_note = '', $is_shared = false) {
    $post_id   = is_object($post_obj) ? $post_obj->ID : intval($post_obj);
    $post      = get_post($post_id);
    if (!$post || $post->post_type !== 'experience') return '';

    $title     = get_the_title($post);
    $permalink = get_permalink($post);
    $thumb_url = get_the_post_thumbnail_url($post, 'large') ?: get_post_meta($post->ID, '_st_cover_image', true);
    if (!$thumb_url) {
        $thumb_url = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
    }

    $address      = get_post_meta($post->ID, '_st_address', true);
    $city         = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
    $price_raw    = get_post_meta($post->ID, '_st_price_from', true);
    $duration     = get_post_meta($post->ID, '_st_duration', true) ?: '2 Hours';
    $lat          = get_post_meta($post->ID, '_st_latitude', true);
    $lng          = get_post_meta($post->ID, '_st_longitude', true);
    $rating_val   = get_post_meta($post->ID, '_st_rating', true) ?: '4.9';
    $review_cnt   = get_post_meta($post->ID, '_st_review_count', true) ?: '86';
    $badge        = get_post_meta($post->ID, '_st_badge', true);
    $cancel_pol   = get_post_meta($post->ID, '_st_cancellation_policy', true);

    // If no custom badge, auto-derive badge
    if (empty($badge)) {
        if (!empty($cancel_pol) || strpos(strtolower($title), 'tour') !== false) {
            $badge = 'Free cancellation';
        } elseif (intval($review_cnt) > 80) {
            $badge = 'Likely to sell out';
        }
    }

    $full_addr = trim(($address ? $address . ', ' : '') . $city);
    $numeric_price = floatval(preg_replace('/[^0-9.]/', '', $price_raw));

    ob_start();
    ?>
    <article class="st-card st-card--<?php echo esc_attr($view); ?><?php echo $is_trip_view ? ' st-card--trip' : ''; ?>" data-id="<?php echo esc_attr($post->ID); ?>" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" data-price="<?php echo esc_attr($numeric_price); ?>" data-duration="<?php echo esc_attr($duration); ?>" data-rating="<?php echo esc_attr($rating_val); ?>">
        <!-- Card Link for entire card clickability -->
        <a href="<?php echo esc_url($permalink); ?>" class="st-card__main-link" aria-label="<?php echo esc_attr($title); ?>"></a>

        <div class="st-card__media">
            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" class="st-card__img" />

            <?php if (!empty($badge)): ?>
                <span class="st-card__badge <?php echo (strpos(strtolower($badge), 'sell out') !== false) ? 'st-card__badge--alert' : 'st-card__badge--success'; ?>">
                    <?php echo esc_html($badge); ?>
                </span>
            <?php endif; ?>

            <!-- Heart Button (Save to Trip) -->
            <?php if (!$is_trip_view): ?>
                <button type="button" class="st-card__heart js-toggle-trip" data-id="<?php echo esc_attr($post->ID); ?>" aria-label="<?php esc_attr_e('Save to My Trip', 'smarttours'); ?>" title="Save to My Trip">
                    <svg viewBox="0 0 24 24" class="st-icon-heart" width="20" height="20" aria-hidden="true">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            <?php endif; ?>
        </div>

        <div class="st-card__body">
            <!-- Location Line -->
            <?php if ($full_addr): ?>
                <div class="st-card__location">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span><?php echo esc_html($city . ', BC'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Title (2 lines max) -->
            <h3 class="st-card__title">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
            </h3>

            <!-- Rating & Duration Row -->
            <div class="st-card__meta">
                <div class="st-card__rating">
                    <span class="st-star-icon">★</span>
                    <strong><?php echo esc_html($rating_val); ?></strong>
                    <span class="st-card__reviews">(<?php echo esc_html($review_cnt); ?>)</span>
                </div>
                <span class="st-card__meta-divider">&bull;</span>
                <span class="st-card__duration">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <?php echo esc_html($duration); ?>
                </span>
            </div>

            <?php if ($view === 'list' && !empty($post->post_excerpt)): ?>
                <p class="st-card__excerpt"><?php echo esc_html(wp_trim_words($post->post_excerpt, 20)); ?></p>
            <?php endif; ?>

            <!-- Price & Action Footer -->
            <div class="st-card__footer">
                <div class="st-card__price">
                    <span class="st-price-label"><?php _e('From', 'smarttours'); ?></span>
                    <span class="st-price-amount">
                        <?php echo $numeric_price > 0 ? '$' . number_format($numeric_price, 0) : esc_html($price_raw ?: '$45'); ?>
                    </span>
                    <span class="st-price-currency">CAD</span>
                </div>

                <?php if ($is_trip_view): ?>
                    <?php if (!$is_shared): ?>
                        <button type="button" class="st-card__btn-remove js-remove-trip" data-id="<?php echo esc_attr($post->ID); ?>" aria-label="<?php esc_attr_e('Remove experience from trip', 'smarttours'); ?>">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            <span>Remove</span>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo esc_url($permalink); ?>" class="st-card__btn-view">
                            <span>Book Now &rarr;</span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="st-card__cta-hint"><?php _e('View details', 'smarttours'); ?> &rarr;</span>
                <?php endif; ?>
            </div>

            <?php if (!$is_trip_view): ?>
                <!-- Action Buttons: QUICKVIEW and DETAILS (Tourism Kelowna style) -->
                <div class="st-card__actions">
                    <button type="button" class="st-card__action-btn st-card__action-btn--quickview js-quickview-btn" data-id="<?php echo esc_attr($post->ID); ?>" aria-label="Quickview <?php echo esc_attr($title); ?>">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>QUICKVIEW</span>
                    </button>
                    <a href="<?php echo esc_url($permalink); ?>" class="st-card__action-btn st-card__action-btn--details" aria-label="Details for <?php echo esc_attr($title); ?>">
                        <span>DETAILS &gt;</span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Notes & Date for My Trip Planner -->
            <?php if ($is_trip_view): ?>
                <div class="st-trip-meta-inputs">
                    <?php if ($is_shared): ?>
                        <?php if (!empty($trip_date)): ?>
                            <div class="st-trip-shared-note"><strong>Planned Date:</strong> <?php echo esc_html($trip_date); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($trip_note)): ?>
                            <div class="st-trip-shared-note"><strong>Note:</strong> <?php echo esc_html($trip_note); ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="st-trip-input-row">
                            <input type="text" class="st-trip-date-input js-trip-date" data-id="<?php echo esc_attr($post->ID); ?>" placeholder="Planned date (e.g. June 15, 10:00 AM)" value="<?php echo esc_attr($trip_date); ?>" />
                            <input type="text" class="st-trip-note-input js-trip-note" data-id="<?php echo esc_attr($post->ID); ?>" placeholder="Notes for this stop (e.g. Book patio table)" value="<?php echo esc_attr($trip_note); ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

/**
 * Handle AJAX experience filter request
 */
function smarttours_ajax_filter_experiences() {
    $term_id      = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    $keyword      = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
    $amenities    = isset($_POST['amenities']) && is_array($_POST['amenities']) ? array_map('intval', $_POST['amenities']) : array();
    $sort         = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'recommended';
    $paged        = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $view         = isset($_POST['view']) && in_array($_POST['view'], array('grid', 'list')) ? $_POST['view'] : 'grid';
    $price_max    = isset($_POST['price_max']) && is_numeric($_POST['price_max']) ? floatval($_POST['price_max']) : 0;
    $duration_flt = isset($_POST['duration_filter']) ? sanitize_text_field($_POST['duration_filter']) : '';
    $rating_min   = isset($_POST['rating_min']) && is_numeric($_POST['rating_min']) ? floatval($_POST['rating_min']) : 0;
    $free_cancel  = !empty($_POST['free_cancellation']) && $_POST['free_cancellation'] === '1';
    $user_lat     = isset($_POST['user_lat']) && is_numeric($_POST['user_lat']) ? floatval($_POST['user_lat']) : null;
    $user_lng     = isset($_POST['user_lng']) && is_numeric($_POST['user_lng']) ? floatval($_POST['user_lng']) : null;
    $per_page     = 12;

    $args = array(
        'post_type'      => 'experience',
        'post_status'    => 'publish',
        'posts_per_page' => ($sort === 'near_me' && $user_lat !== null) || ($price_max > 0) || !empty($duration_flt) || ($rating_min > 0) ? 150 : $per_page,
        'paged'          => (($sort === 'near_me' && $user_lat !== null) || ($price_max > 0) || !empty($duration_flt) || ($rating_min > 0)) ? 1 : $paged,
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

    // Standard sorting
    if ($sort === 'price_asc') {
        $args['meta_key'] = '_st_price_from';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'ASC';
    } elseif ($sort === 'price_desc') {
        $args['meta_key'] = '_st_price_from';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } elseif ($sort === 'rating_desc') {
        $args['meta_key'] = '_st_rating';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } elseif ($sort === 'duration_asc') {
        $args['meta_key'] = '_st_duration';
        $args['orderby']  = 'meta_value';
        $args['order']    = 'ASC';
    } elseif ($sort === 'title_asc') {
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
    } else {
        $args['orderby'] = 'menu_order date';
        $args['order']   = 'DESC';
    }

    $query = new WP_Query($args);
    $posts = $query->posts;

    // In-memory filters for dynamic fields
    if (!empty($posts)) {
        $posts = array_filter($posts, function($p) use ($price_max, $duration_flt, $rating_min, $free_cancel) {
            $p_price = floatval(preg_replace('/[^0-9.]/', '', get_post_meta($p->ID, '_st_price_from', true) ?: '0'));
            if ($price_max > 0 && $p_price > $price_max) {
                return false;
            }

            if ($rating_min > 0) {
                $p_rating = floatval(get_post_meta($p->ID, '_st_rating', true) ?: '4.9');
                if ($p_rating < $rating_min) return false;
            }

            if ($free_cancel) {
                $cancellation = get_post_meta($p->ID, '_st_cancellation_policy', true);
                $badge = get_post_meta($p->ID, '_st_badge', true);
                if (empty($cancellation) && strpos(strtolower($badge), 'cancellation') === false) {
                    return false;
                }
            }

            if (!empty($duration_flt) && $duration_flt !== 'all') {
                $dur_str = strtolower(get_post_meta($p->ID, '_st_duration', true) ?: '');
                preg_match('/([0-9]+)/', $dur_str, $matches);
                $hours = !empty($matches[1]) ? intval($matches[1]) : 2;
                if (strpos($dur_str, 'min') !== false) {
                    $hours = $hours / 60;
                } elseif (strpos($dur_str, 'half') !== false) {
                    $hours = 4;
                } elseif (strpos($dur_str, 'full') !== false) {
                    $hours = 8;
                }

                if ($duration_flt === 'short' && $hours > 2) return false;
                if ($duration_flt === 'medium' && ($hours <= 2 || $hours > 4)) return false;
                if ($duration_flt === 'long' && $hours <= 4) return false;
            }

            return true;
        });

        // Near Me distance sort if requested
        if ($sort === 'near_me' && $user_lat !== null && $user_lng !== null) {
            usort($posts, function($a, $b) use ($user_lat, $user_lng) {
                $lat_a = floatval(get_post_meta($a->ID, '_st_latitude', true) ?: 49.88);
                $lng_a = floatval(get_post_meta($a->ID, '_st_longitude', true) ?: -119.49);
                $lat_b = floatval(get_post_meta($b->ID, '_st_latitude', true) ?: 49.88);
                $lng_b = floatval(get_post_meta($b->ID, '_st_longitude', true) ?: -119.49);

                $dist_a = smarttours_haversine_distance($user_lat, $user_lng, $lat_a, $lng_a);
                $dist_b = smarttours_haversine_distance($user_lat, $user_lng, $lat_b, $lng_b);

                return ($dist_a < $dist_b) ? -1 : 1;
            });
        }

        // Re-slice pagination if in-memory filtering occurred
        $total_found = count($posts);
        $max_pages   = max(1, ceil($total_found / $per_page));
        $offset      = ($paged - 1) * $per_page;
        $posts       = array_slice($posts, $offset, $per_page);
    } else {
        $total_found = 0;
        $max_pages   = 0;
    }

    $html = '';
    $markers = array();

    if (!empty($posts)) {
        foreach ($posts as $post_item) {
            $html .= smarttours_render_experience_card($post_item, $view);

            $lat = get_post_meta($post_item->ID, '_st_latitude', true);
            $lng = get_post_meta($post_item->ID, '_st_longitude', true);
            $img = get_the_post_thumbnail_url($post_item, 'large') ?: get_post_meta($post_item->ID, '_st_cover_image', true);
            $addr = get_post_meta($post_item->ID, '_st_address', true);
            $city = get_post_meta($post_item->ID, '_st_city', true) ?: 'Kelowna';
            $price = get_post_meta($post_item->ID, '_st_price_from', true);

            if ($lat && $lng) {
                $markers[] = array(
                    'id'      => $post_item->ID,
                    'title'   => get_the_title($post_item),
                    'lat'     => floatval($lat),
                    'lng'     => floatval($lng),
                    'url'     => get_permalink($post_item),
                    'image'   => $img,
                    'address' => trim(($addr ? $addr . ', ' : '') . $city . ', BC'),
                    'price'   => $price ? ('$' . $price . ' CAD') : '',
                );
            }
        }
    } else {
        $html = '
        <div class="st-no-results">
            <div class="st-no-results__icon">🔍</div>
            <h3>No experiences match your filters</h3>
            <p>Try resetting filters, widening your price range, or searching for a different keyword.</p>
            <button type="button" class="st-btn st-btn--primary js-clear-filters" style="margin-top: 16px;">
                Reset All Filters
            </button>
        </div>';
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
 * AJAX Live Search Suggestions (Experiences + Categories)
 */
function smarttours_ajax_live_search() {
    $q = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';
    if (strlen($q) < 2) {
        wp_send_json_success(array('experiences' => array(), 'categories' => array()));
    }

    // 1. Search Categories
    $categories = array();
    $cat_terms = get_terms(array(
        'taxonomy'   => 'experience_category',
        'name__like' => $q,
        'hide_empty' => false,
        'number'     => 4,
    ));

    if (!empty($cat_terms) && !is_wp_error($cat_terms)) {
        foreach ($cat_terms as $ct) {
            if (is_numeric($ct->name)) continue;
            $categories[] = array(
                'id'    => $ct->term_id,
                'name'  => $ct->name,
                'url'   => get_term_link($ct),
                'count' => intval($ct->count),
            );
        }
    }

    // 2. Search Experiences
    $experiences = array();
    $exp_posts = get_posts(array(
        'post_type'      => 'experience',
        'post_status'    => 'publish',
        'posts_per_page' => 5,
        's'              => $q,
    ));

    if (!empty($exp_posts)) {
        foreach ($exp_posts as $ep) {
            $img = get_the_post_thumbnail_url($ep, 'thumbnail') ?: get_post_meta($ep->ID, '_st_cover_image', true);
            $price = get_post_meta($ep->ID, '_st_price_from', true);
            $city = get_post_meta($ep->ID, '_st_city', true) ?: 'Kelowna';
            $rating = get_post_meta($ep->ID, '_st_rating', true) ?: '4.9';

            $experiences[] = array(
                'id'       => $ep->ID,
                'title'    => get_the_title($ep),
                'url'      => get_permalink($ep),
                'image'    => $img,
                'city'     => $city,
                'price'    => $price ? ('From $' . $price . ' CAD') : '',
                'rating'   => $rating,
            );
        }
    }

    wp_send_json_success(array(
        'experiences' => $experiences,
        'categories'  => $categories,
    ));
}
add_action('wp_ajax_smarttours_live_search', 'smarttours_ajax_live_search');
add_action('wp_ajax_nopriv_smarttours_live_search', 'smarttours_ajax_live_search');

/**
 * AJAX Save / Share Trip in wp_st_trips table
 */
function smarttours_ajax_save_trip() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_trips';

    $ids_raw = isset($_POST['ids']) ? $_POST['ids'] : array();
    $notes_raw = isset($_POST['notes']) ? $_POST['notes'] : array();

    if (is_string($ids_raw)) {
        $ids = json_decode(stripslashes($ids_raw), true);
    } elseif (is_array($ids_raw)) {
        $ids = array_map('intval', $ids_raw);
    } else {
        $ids = array();
    }

    if (empty($ids)) {
        wp_send_json_error(array('message' => 'Your trip is empty. Please save some experiences first.'));
    }

    $ids = array_values(array_unique(array_filter($ids)));

    if (is_string($notes_raw)) {
        $notes = json_decode(stripslashes($notes_raw), true);
    } elseif (is_array($notes_raw)) {
        $notes = $notes_raw;
    } else {
        $notes = array();
    }

    // Generate unique 6-character alphanumeric code
    $share_code = '';
    $attempts = 0;
    while ($attempts < 10) {
        $code = strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6));
        $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE share_code = %s", $code));
        if (!$exists) {
            $share_code = $code;
            break;
        }
        $attempts++;
    }

    if (empty($share_code)) {
        $share_code = substr(md5(uniqid(wp_rand(), true)), 0, 6);
    }

    $inserted = $wpdb->insert(
        $table_name,
        array(
            'share_code'      => $share_code,
            'experience_ids'  => wp_json_encode($ids),
            'notes'           => wp_json_encode($notes),
            'created_at'      => current_time('mysql'),
        ),
        array('%s', '%s', '%s', '%s')
    );

    if ($inserted === false) {
        wp_send_json_error(array('message' => 'Could not save trip to server. Please try again.'));
    }

    $share_url = home_url('/trip/' . $share_code . '/');

    wp_send_json_success(array(
        'share_code' => $share_code,
        'share_url'  => $share_url,
        'message'    => 'Trip saved successfully!',
    ));
}
add_action('wp_ajax_smarttours_save_trip', 'smarttours_ajax_save_trip');
add_action('wp_ajax_nopriv_smarttours_save_trip', 'smarttours_ajax_save_trip');

/**
 * AJAX Handler for My Trip saved items
 */
function smarttours_ajax_get_trip_items() {
    $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? array_map('intval', $_POST['ids']) : array();
    $notes_in = isset($_POST['notes']) && is_array($_POST['notes']) ? $_POST['notes'] : array();

    if (empty($ids)) {
        wp_send_json_success(array('html' => '', 'markers' => array(), 'count' => 0, 'total_price' => 0));
    }

    $posts = get_posts(array(
        'post_type'      => 'experience',
        'post__in'       => $ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    ));

    $html = '';
    $markers = array();
    $total_price = 0;

    foreach ($posts as $idx => $post) {
        $p_id = $post->ID;
        $note_info = isset($notes_in[$p_id]) ? $notes_in[$p_id] : (isset($notes_in[strval($p_id)]) ? $notes_in[strval($p_id)] : array());
        $saved_date = is_array($note_info) && isset($note_info['date']) ? $note_info['date'] : '';
        $saved_note = is_array($note_info) && isset($note_info['note']) ? $note_info['note'] : '';

        $html .= smarttours_render_experience_card($post, 'grid', true, $saved_date, $saved_note, false);

        $lat = get_post_meta($post->ID, '_st_latitude', true);
        $lng = get_post_meta($post->ID, '_st_longitude', true);
        $img = get_the_post_thumbnail_url($post, 'large') ?: get_post_meta($post->ID, '_st_cover_image', true);
        $addr = get_post_meta($post->ID, '_st_address', true);
        $city = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
        $price_raw = get_post_meta($post->ID, '_st_price_from', true);
        $num_price = floatval(preg_replace('/[^0-9.]/', '', $price_raw));
        $total_price += $num_price;

        if ($lat && $lng) {
            $markers[] = array(
                'id'        => $post->ID,
                'stop_num'  => ($idx + 1),
                'title'     => get_the_title($post),
                'lat'       => floatval($lat),
                'lng'       => floatval($lng),
                'url'       => get_permalink($post),
                'image'     => $img,
                'address'   => trim(($addr ? $addr . ', ' : '') . $city . ', BC'),
                'price'     => $price_raw ? ('$' . $price_raw . ' CAD') : '',
            );
        }
    }

    wp_send_json_success(array(
        'html'        => $html,
        'markers'     => $markers,
        'count'       => count($posts),
        'total_price' => $total_price,
    ));
}
add_action('wp_ajax_smarttours_get_trip_items', 'smarttours_ajax_get_trip_items');
add_action('wp_ajax_nopriv_smarttours_get_trip_items', 'smarttours_ajax_get_trip_items');

/**
 * Haversine distance in KM
 */
function smarttours_haversine_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earth_radius * $c;
}

/**
 * AJAX Quickview Handler
 */
function smarttours_ajax_get_quickview() {
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'experience') {
        wp_send_json_error(array('message' => 'Tour not found.'));
    }

    $title = get_the_title($post);
    $permalink = get_permalink($post);
    $thumb = get_the_post_thumbnail_url($post, 'large') ?: get_post_meta($post->ID, '_st_cover_image', true);
    if (!$thumb) {
        $thumb = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
    }

    $city = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
    $address = get_post_meta($post->ID, '_st_address', true);
    $price = get_post_meta($post->ID, '_st_price_from', true) ?: '$45';
    $duration = get_post_meta($post->ID, '_st_duration', true) ?: '2 Hours';
    $rating = get_post_meta($post->ID, '_st_rating', true) ?: '4.9';
    $reviews = get_post_meta($post->ID, '_st_review_count', true) ?: '86';
    $badge = get_post_meta($post->ID, '_st_badge', true) ?: 'Top Rated';
    $booking_type = get_post_meta($post->ID, '_st_booking_type', true) ?: 'none';
    $booking_url = get_post_meta($post->ID, '_st_booking_url', true);
    $cancellation = get_post_meta($post->ID, '_st_cancellation_policy', true) ?: 'Free cancellation up to 24 hours before experience starts.';
    $excerpt = !empty($post->post_excerpt) ? $post->post_excerpt : wp_trim_words($post->post_content, 35);

    $cats = get_the_terms($post->ID, 'experience_category');
    $cat_name = (!empty($cats) && !is_wp_error($cats)) ? $cats[0]->name : 'Experience';

    wp_send_json_success(array(
        'id'           => $post->ID,
        'title'        => $title,
        'url'          => $permalink,
        'image'        => $thumb,
        'city'         => $city,
        'address'      => trim(($address ? $address . ', ' : '') . $city . ', BC'),
        'price'        => $price,
        'duration'     => $duration,
        'rating'       => $rating,
        'reviews'      => $reviews,
        'badge'        => $badge,
        'category'     => $cat_name,
        'cancellation' => $cancellation,
        'excerpt'      => $excerpt,
        'booking_type' => $booking_type,
        'booking_url'  => $booking_url,
    ));
}
add_action('wp_ajax_smarttours_get_quickview', 'smarttours_ajax_get_quickview');
add_action('wp_ajax_nopriv_smarttours_get_quickview', 'smarttours_ajax_get_quickview');
