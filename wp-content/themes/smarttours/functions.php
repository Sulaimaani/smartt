<?php
/**
 * Smart Tours Theme Functions
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SMARTTOURS_VERSION', '2.0.0');
define('SMARTTOURS_DIR', get_template_directory());
define('SMARTTOURS_URI', get_template_directory_uri());

// Include modular components
require_once SMARTTOURS_DIR . '/inc/post-types.php';
require_once SMARTTOURS_DIR . '/inc/taxonomies.php';
require_once SMARTTOURS_DIR . '/inc/permalinks.php';
require_once SMARTTOURS_DIR . '/inc/meta-boxes.php';
require_once SMARTTOURS_DIR . '/inc/click-tracking.php';
require_once SMARTTOURS_DIR . '/inc/demo-seeder.php';
require_once SMARTTOURS_DIR . '/inc/ajax-filters.php';
require_once SMARTTOURS_DIR . '/inc/schema-seo.php';
require_once SMARTTOURS_DIR . '/inc/admin-pages.php';

/**
 * Theme Setup
 */
function smarttours_setup() {
    load_theme_textdomain('smarttours', SMARTTOURS_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Navigation (Experiences, Explore, etc.)', 'smarttours'),
        'footer'  => __('Footer Navigation', 'smarttours'),
    ));
}
add_action('after_setup_theme', 'smarttours_setup');

/**
 * Auto-seed demo content on theme activation
 */
function smarttours_activation_setup() {
    smarttours_create_clicks_table();
    smarttours_create_trips_table();
    smarttours_register_post_types();
    smarttours_register_taxonomies();
    smarttours_custom_rewrite_rules();

    if (!get_option('smarttours_demo_seeded')) {
        smarttours_seed_demo_content();
    }
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'smarttours_activation_setup');

/**
 * Enqueue Styles and Scripts
 */
function smarttours_enqueue_scripts() {
    // 1. Google Font: Plus Jakarta Sans (Clean, modern, geometric sans-serif)
    wp_enqueue_style(
        'smarttours-fonts',
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // 2. Leaflet Map CSS & JS
    wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4');
    wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);

    // 3. Main Theme CSS
    wp_enqueue_style('smarttours-style', SMARTTOURS_URI . '/assets/css/main.css', array('leaflet-css', 'smarttours-fonts'), SMARTTOURS_VERSION);

    // 4. Main Theme JavaScript
    wp_enqueue_script('smarttours-main', SMARTTOURS_URI . '/assets/js/main.js', array('leaflet-js'), SMARTTOURS_VERSION, true);

    // Localize Script for AJAX & Endpoints
    wp_localize_script('smarttours-main', 'smarttours_vars', array(
        'ajax_url'     => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('smarttours_ajax_nonce'),
        'home_url'     => home_url('/'),
        'my_trip_url'  => home_url('/my-trip/'),
        'currency'     => 'CAD',
        'currency_sym' => '$',
        'i18n'         => array(
            'added_to_trip'   => __('Added to My Trip', 'smarttours'),
            'removed_to_trip' => __('Removed from My Trip', 'smarttours'),
            'loading'         => __('Loading...', 'smarttours'),
            'locating'        => __('Getting your location...', 'smarttours'),
            'location_error'  => __('Could not detect location.', 'smarttours'),
        )
    ));
}
add_action('wp_enqueue_scripts', 'smarttours_enqueue_scripts');
