<?php
/**
 * Custom Post Type: Experience
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

function smarttours_register_post_types() {
    $labels = array(
        'name'                  => _x('Experiences', 'Post type general name', 'smarttours'),
        'singular_name'         => _x('Experience', 'Post type singular name', 'smarttours'),
        'menu_name'             => _x('Experiences', 'Admin Menu text', 'smarttours'),
        'name_admin_bar'        => _x('Experience', 'Add New on Toolbar', 'smarttours'),
        'add_new'               => __('Add New', 'smarttours'),
        'add_new_item'          => __('Add New Experience', 'smarttours'),
        'new_item'              => __('New Experience', 'smarttours'),
        'edit_item'             => __('Edit Experience', 'smarttours'),
        'view_item'             => __('View Experience', 'smarttours'),
        'all_items'             => __('All Experiences', 'smarttours'),
        'search_items'          => __('Search Experiences', 'smarttours'),
        'parent_item_colon'     => __('Parent Experiences:', 'smarttours'),
        'not_found'             => __('No experiences found.', 'smarttours'),
        'not_found_in_trash'    => __('No experiences found in Trash.', 'smarttours'),
        'featured_image'        => _x('Experience Cover Image', 'Overrides the "Featured Image" phrase', 'smarttours'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'smarttours'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'smarttours'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'smarttours'),
        'archives'              => _x('Experience Archives', 'The post type archive label', 'smarttours'),
        'attributes'            => _x('Experience Attributes', 'The post type attributes label', 'smarttours'),
        'insert_into_item'      => _x('Insert into experience', 'Overrides the "Insert into post" phrase', 'smarttours'),
        'uploaded_to_this_item' => _x('Uploaded to this experience', 'Overrides the "Uploaded to this post" phrase', 'smarttours'),
        'filter_items_list'     => _x('Filter experiences list', 'Screen reader text for the filter links', 'smarttours'),
        'items_list_navigation' => _x('Experiences list navigation', 'Screen reader text for the pagination', 'smarttours'),
        'items_list'            => _x('Experiences list', 'Screen reader text for the items list', 'smarttours'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'experience', 'with_front' => false),
        'capability_type'    => 'post',
        'has_archive'        => 'experiences',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-tickets-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest'       => true,
    );

    register_post_type('experience', $args);
}
add_action('init', 'smarttours_register_post_types');

/**
 * Register responsive image sizes
 */
function smarttours_image_sizes() {
    add_image_size('st-hero', 1920, 750, true);
    add_image_size('st-tile', 700, 700, true);
    add_image_size('st-card', 800, 500, true); // 16:10 aspect ratio
    add_image_size('st-thumb', 400, 250, true);
}
add_action('after_setup_theme', 'smarttours_image_sizes');
