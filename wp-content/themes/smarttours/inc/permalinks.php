<?php
/**
 * Pretty nested permalinks for Experience Categories and Single Experiences
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Filter single experience permalink to include full category hierarchy
 */
function smarttours_experience_post_link($post_link, $post) {
    if ($post->post_type !== 'experience') {
        return $post_link;
    }

    $terms = wp_get_object_terms($post->ID, 'experience_category', array('orderby' => 'term_id', 'order' => 'ASC'));
    if (!empty($terms) && !is_wp_error($terms)) {
        // Find deepest child term
        $deepest_term = $terms[0];
        $max_depth = 0;
        foreach ($terms as $term) {
            $ancestors = get_ancestors($term->term_id, 'experience_category');
            $depth = count($ancestors);
            if ($depth > $max_depth) {
                $max_depth = $depth;
                $deepest_term = $term;
            }
        }

        $term_slugs = array($deepest_term->slug);
        $ancestor_ids = get_ancestors($deepest_term->term_id, 'experience_category');
        foreach ($ancestor_ids as $ancestor_id) {
            $ancestor = get_term($ancestor_id, 'experience_category');
            if ($ancestor && !is_wp_error($ancestor)) {
                array_unshift($term_slugs, $ancestor->slug);
            }
        }
        $category_path = implode('/', $term_slugs);
        return home_url('/experiences/' . $category_path . '/' . $post->post_name . '/');
    }

    return home_url('/experiences/' . $post->post_name . '/');
}
add_filter('post_type_link', 'smarttours_experience_post_link', 10, 2);

/**
 * Custom rewrite rules for experiences hierarchy and /go/ click tracking
 */
function smarttours_custom_rewrite_rules() {
    // Click tracking endpoint: /go/{id}/
    add_rewrite_rule('^go/([0-9]+)/?$', 'index.php?st_go_id=$matches[1]', 'top');

    // Trip builder page: /my-trip/
    add_rewrite_rule('^my-trip/?$', 'index.php?pagename=my-trip', 'top');

    // Experience landing page: /experiences/
    add_rewrite_rule('^experiences/?$', 'index.php?post_type=experience', 'top');
}
add_action('init', 'smarttours_custom_rewrite_rules', 10);

/**
 * Guarantee page-my-trip.php is loaded for /my-trip/
 */
function smarttours_template_include($template) {
    if (is_page('my-trip') || get_query_var('pagename') === 'my-trip' || get_query_var('st_page') === 'my-trip') {
        $trip_template = locate_template('page-my-trip.php');
        if ($trip_template) {
            return $trip_template;
        }
    }
    return $template;
}
add_filter('template_include', 'smarttours_template_include');


/**
 * Register custom query variables
 */
function smarttours_query_vars($vars) {
    $vars[] = 'st_go_id';
    $vars[] = 'st_page';
    return $vars;
}
add_filter('query_vars', 'smarttours_query_vars');

/**
 * Resolve nested paths under /experiences/ to either single experience or experience_category
 */
function smarttours_parse_request($query) {
    if (!isset($query->query_vars['experience_category'])) {
        return;
    }

    $raw_path = trim($query->query_vars['experience_category'], '/');
    if (empty($raw_path)) {
        return;
    }

    $segments = explode('/', $raw_path);
    $last_segment = end($segments);

    // Check if the exact full path matches an existing term
    $term = get_term_by('slug', $last_segment, 'experience_category');
    if ($term && !is_wp_error($term)) {
        // It's a valid category term
        return;
    }

    // If not a term, check if the last segment is a single experience CPT slug
    $experience = get_page_by_path($last_segment, OBJECT, 'experience');
    if ($experience) {
        unset($query->query_vars['experience_category']);
        $query->query_vars['post_type'] = 'experience';
        $query->query_vars['name'] = $last_segment;
        $query->is_single = true;
        $query->is_singular = true;
        $query->is_tax = false;
        $query->is_archive = false;
    }
}
add_action('parse_request', 'smarttours_parse_request');
