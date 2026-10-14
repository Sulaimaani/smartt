<?php
/**
 * SEO & Structured Data (JSON-LD Breadcrumbs, TouristAttraction & LocalBusiness)
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output JSON-LD Schemas in Head
 */
function smarttours_output_schema_jsonld() {
    // 1. Breadcrumbs Schema
    $breadcrumbs = smarttours_get_breadcrumb_items();
    if (!empty($breadcrumbs)) {
        $schema_breadcrumbs = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(),
        );

        foreach ($breadcrumbs as $i => $item) {
            $schema_breadcrumbs['itemListElement'][] = array(
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $item['name'],
                'item'     => $item['url'],
            );
        }

        echo "\n<!-- Smart Tours Breadcrumb Schema -->\n";
        echo '<script type="application/ld+json">' . wp_json_encode($schema_breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "</script>\n";
    }

    // 2. Single Experience Schema: TouristAttraction & LocalBusiness
    if (is_singular('experience')) {
        global $post;
        $title       = get_the_title($post);
        $description = get_the_excerpt($post) ?: wp_trim_words(strip_tags($post->post_content), 30);
        $image       = get_the_post_thumbnail_url($post, 'large') ?: get_post_meta($post->ID, '_st_cover_image', true);
        $url         = get_permalink($post);
        $address     = get_post_meta($post->ID, '_st_address', true);
        $city        = get_post_meta($post->ID, '_st_city', true) ?: 'Kelowna';
        $postal_code = get_post_meta($post->ID, '_st_postal_code', true);
        $lat         = get_post_meta($post->ID, '_st_latitude', true);
        $lng         = get_post_meta($post->ID, '_st_longitude', true);
        $phone       = get_post_meta($post->ID, '_st_phone', true);
        $price       = get_post_meta($post->ID, '_st_price_from', true);

        $experience_schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => array('TouristAttraction', 'LocalBusiness'),
            'name'        => $title,
            'description' => $description,
            'url'         => $url,
            'image'       => $image,
        );

        if ($phone) {
            $experience_schema['telephone'] = $phone;
        }

        if ($price) {
            $experience_schema['priceRange'] = '$' . $price . ' CAD';
        }

        if ($address || $city) {
            $experience_schema['address'] = array(
                '@type'           => 'PostalAddress',
                'streetAddress'   => $address,
                'addressLocality' => $city,
                'addressRegion'   => 'BC',
                'postalCode'      => $postal_code,
                'addressCountry'  => 'CA',
            );
        }

        if ($lat && $lng) {
            $experience_schema['geo'] = array(
                '@type'     => 'GeoCoordinates',
                'latitude'  => floatval($lat),
                'longitude' => floatval($lng),
            );
        }

        echo "\n<!-- Smart Tours Experience Schema -->\n";
        echo '<script type="application/ld+json">' . wp_json_encode($experience_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "</script>\n";
    }
}
add_action('wp_head', 'smarttours_output_schema_jsonld');

/**
 * Build breadcrumb items list
 */
function smarttours_get_breadcrumb_items() {
    $items = array();
    $items[] = array('name' => __('Home', 'smarttours'), 'url' => home_url('/'));

    if (is_post_type_archive('experience') || is_page('experiences')) {
        $items[] = array('name' => __('Experiences', 'smarttours'), 'url' => home_url('/experiences/'));
    } elseif (is_tax('experience_category')) {
        $items[] = array('name' => __('Experiences', 'smarttours'), 'url' => home_url('/experiences/'));
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            $ancestor_ids = array_reverse(get_ancestors($term->term_id, 'experience_category'));
            foreach ($ancestor_ids as $ancestor_id) {
                $ancestor = get_term($ancestor_id, 'experience_category');
                if ($ancestor && !is_wp_error($ancestor)) {
                    $items[] = array('name' => $ancestor->name, 'url' => get_term_link($ancestor));
                }
            }
            $items[] = array('name' => $term->name, 'url' => get_term_link($term));
        }
    } elseif (is_singular('experience')) {
        $items[] = array('name' => __('Experiences', 'smarttours'), 'url' => home_url('/experiences/'));
        $terms = wp_get_object_terms(get_the_ID(), 'experience_category');
        if (!empty($terms) && !is_wp_error($terms)) {
            $term = $terms[0];
            $ancestor_ids = array_reverse(get_ancestors($term->term_id, 'experience_category'));
            foreach ($ancestor_ids as $ancestor_id) {
                $ancestor = get_term($ancestor_id, 'experience_category');
                if ($ancestor && !is_wp_error($ancestor)) {
                    $items[] = array('name' => $ancestor->name, 'url' => get_term_link($ancestor));
                }
            }
            $items[] = array('name' => $term->name, 'url' => get_term_link($term));
        }
        $items[] = array('name' => get_the_title(), 'url' => get_permalink());
    }

    return $items;
}

/**
 * Helper to render HTML breadcrumbs navigation
 */
function smarttours_render_breadcrumbs() {
    $items = smarttours_get_breadcrumb_items();
    if (empty($items)) return;

    echo '<nav class="st-breadcrumbs" aria-label="Breadcrumb"><ol>';
    $total = count($items);
    foreach ($items as $index => $item) {
        $is_last = ($index === $total - 1);
        echo '<li>';
        if ($is_last) {
            echo '<span aria-current="page">' . esc_html($item['name']) . '</span>';
        } else {
            echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a>';
            echo '<span class="st-breadcrumbs__separator">&gt;</span>';
        }
        echo '</li>';
    }
    echo '</ol></nav>';
}
