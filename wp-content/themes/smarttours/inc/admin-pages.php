<?php
/**
 * Admin Settings & Demo Content Management
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

function smarttours_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=experience',
        __('Demo Content & Setup', 'smarttours'),
        __('Demo Content', 'smarttours'),
        'manage_options',
        'smarttours-setup',
        'smarttours_render_setup_page'
    );
}
add_action('admin_menu', 'smarttours_admin_menu');

function smarttours_handle_admin_actions() {
    if (!isset($_POST['st_action']) || !check_admin_referer('smarttours_admin_action', 'st_nonce')) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    $action = sanitize_text_field($_POST['st_action']);

    if ($action === 'seed_demo') {
        smarttours_seed_demo_content();
        flush_rewrite_rules();
        add_settings_error('smarttours_notices', 'seeded', __('Demo content and category tree successfully seeded!', 'smarttours'), 'updated');
    } elseif ($action === 'delete_demo') {
        smarttours_delete_demo_content();
        add_settings_error('smarttours_notices', 'deleted', __('Demo content successfully deleted.', 'smarttours'), 'updated');
    } elseif ($action === 'flush_permalinks') {
        flush_rewrite_rules();
        add_settings_error('smarttours_notices', 'flushed', __('Permalinks flushed and rewrite rules updated.', 'smarttours'), 'updated');
    }
}
add_action('admin_init', 'smarttours_handle_admin_actions');

function smarttours_render_setup_page() {
    global $wpdb;
    $exp_count    = wp_count_posts('experience')->publish;
    $demo_count   = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_st_demo' AND meta_value = '1'");
    $cat_count    = wp_count_terms(array('taxonomy' => 'experience_category', 'hide_empty' => false));
    $amenity_count= wp_count_terms(array('taxonomy' => 'amenity', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1><?php _e('Canada Smart Tours — System Setup & Demo Data', 'smarttours'); ?></h1>
        <?php settings_errors('smarttours_notices'); ?>

        <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 800px; margin-top: 20px;">
            <h2><?php _e('System Status Overview', 'smarttours'); ?></h2>
            <ul style="font-size: 14px; line-height: 1.8;">
                <li><strong><?php _e('Published Experiences:', 'smarttours'); ?></strong> <?php echo intval($exp_count); ?></li>
                <li><strong><?php _e('Demo Items Tagged (_st_demo):', 'smarttours'); ?></strong> <?php echo intval($demo_count); ?></li>
                <li><strong><?php _e('Experience Categories:', 'smarttours'); ?></strong> <?php echo intval($cat_count); ?></li>
                <li><strong><?php _e('Filter Amenities:', 'smarttours'); ?></strong> <?php echo intval($amenity_count); ?></li>
            </ul>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;" />

            <h2><?php _e('Demo Content Actions', 'smarttours'); ?></h2>
            <p><?php _e('Seed the entire 12-category Okanagan experiences taxonomy tree, guide pages, blog posts, and realistic demo experiences with Okanagan coordinates, high-resolution imagery, and booking configurations.', 'smarttours'); ?></p>

            <form method="post" style="display:inline-block; margin-right: 15px;">
                <?php wp_nonce_field('smarttours_admin_action', 'st_nonce'); ?>
                <input type="hidden" name="st_action" value="seed_demo" />
                <button type="submit" class="button button-primary button-hero">
                    <?php _e('Seed / Re-seed Demo Content', 'smarttours'); ?>
                </button>
            </form>

            <form method="post" style="display:inline-block; margin-right: 15px;" onsubmit="return confirm('Are you sure you want to delete all demo content?');">
                <?php wp_nonce_field('smarttours_admin_action', 'st_nonce'); ?>
                <input type="hidden" name="st_action" value="delete_demo" />
                <button type="submit" class="button button-secondary button-hero" style="color: #a00;">
                    <?php _e('Delete Demo Content', 'smarttours'); ?>
                </button>
            </form>

            <form method="post" style="display:inline-block;">
                <?php wp_nonce_field('smarttours_admin_action', 'st_nonce'); ?>
                <input type="hidden" name="st_action" value="flush_permalinks" />
                <button type="submit" class="button button-secondary button-hero">
                    <?php _e('Flush Permalinks', 'smarttours'); ?>
                </button>
            </form>
        </div>
    </div>
    <?php
}
