<?php
/**
 * Taxonomies: experience_category (hierarchical) & amenity (non-hierarchical)
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

function smarttours_register_taxonomies() {
    // 1. Hierarchical Taxonomy: experience_category
    $cat_labels = array(
        'name'              => _x('Experience Categories', 'taxonomy general name', 'smarttours'),
        'singular_name'     => _x('Experience Category', 'taxonomy singular name', 'smarttours'),
        'search_items'      => __('Search Experience Categories', 'smarttours'),
        'all_items'         => __('All Experience Categories', 'smarttours'),
        'parent_item'       => __('Parent Category', 'smarttours'),
        'parent_item_colon' => __('Parent Category:', 'smarttours'),
        'edit_item'         => __('Edit Category', 'smarttours'),
        'update_item'       => __('Update Category', 'smarttours'),
        'add_new_item'      => __('Add New Experience Category', 'smarttours'),
        'new_item_name'     => __('New Category Name', 'smarttours'),
        'menu_name'         => __('Categories', 'smarttours'),
    );

    $cat_args = array(
        'hierarchical'      => true,
        'labels'            => $cat_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug'         => 'experiences',
            'with_front'   => false,
            'hierarchical' => true,
        ),
        'show_in_rest'      => true,
    );

    register_taxonomy('experience_category', array('experience'), $cat_args);

    // 2. Non-hierarchical Taxonomy: amenity
    $amenity_labels = array(
        'name'                       => _x('Amenities', 'taxonomy general name', 'smarttours'),
        'singular_name'              => _x('Amenity', 'taxonomy singular name', 'smarttours'),
        'search_items'               => __('Search Amenities', 'smarttours'),
        'popular_items'              => __('Popular Amenities', 'smarttours'),
        'all_items'                  => __('All Amenities', 'smarttours'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edit Amenity', 'smarttours'),
        'update_item'                => __('Update Amenity', 'smarttours'),
        'add_new_item'               => __('Add New Amenity', 'smarttours'),
        'new_item_name'              => __('New Amenity Name', 'smarttours'),
        'separate_items_with_commas' => __('Separate amenities with commas', 'smarttours'),
        'add_or_remove_items'        => __('Add or remove amenities', 'smarttours'),
        'choose_from_most_used'      => __('Choose from the most used amenities', 'smarttours'),
        'not_found'                  => __('No amenities found.', 'smarttours'),
        'menu_name'                  => __('Amenities', 'smarttours'),
    );

    $amenity_args = array(
        'hierarchical'          => false,
        'labels'                => $amenity_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'amenity', 'with_front' => false),
        'show_in_rest'          => true,
    );

    register_taxonomy('amenity', array('experience'), $amenity_args);
}
add_action('init', 'smarttours_register_taxonomies', 0);

/**
 * Add custom fields to experience_category taxonomy
 */
function smarttours_add_term_fields() {
    ?>
    <div class="form-field term-group">
        <label for="st_hero_image"><?php _e('Hero Image URL', 'smarttours'); ?></label>
        <input type="text" name="st_hero_image" id="st_hero_image" value="" placeholder="https://..." />
        <p class="description"><?php _e('Full-width header image for category page.', 'smarttours'); ?></p>
    </div>
    <div class="form-field term-group">
        <label for="st_tile_image"><?php _e('Tile Image URL', 'smarttours'); ?></label>
        <input type="text" name="st_tile_image" id="st_tile_image" value="" placeholder="https://..." />
        <p class="description"><?php _e('Square / card tile image displayed on parent category grids.', 'smarttours'); ?></p>
    </div>
    <div class="form-field term-group">
        <label for="st_display_order"><?php _e('Display Order', 'smarttours'); ?></label>
        <input type="number" name="st_display_order" id="st_display_order" value="0" />
        <p class="description"><?php _e('Lower numbers appear first.', 'smarttours'); ?></p>
    </div>
    <?php
}
add_action('experience_category_add_form_fields', 'smarttours_add_term_fields', 10, 2);

function smarttours_edit_term_fields($term) {
    $hero_image    = get_term_meta($term->term_id, 'st_hero_image', true);
    $tile_image    = get_term_meta($term->term_id, 'st_tile_image', true);
    $display_order = get_term_meta($term->term_id, 'st_display_order', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="st_hero_image"><?php _e('Hero Image URL', 'smarttours'); ?></label></th>
        <td>
            <input type="text" name="st_hero_image" id="st_hero_image" value="<?php echo esc_attr($hero_image); ?>" style="width: 80%;" />
            <?php if ($hero_image): ?>
                <div style="margin-top: 8px;"><img src="<?php echo esc_url($hero_image); ?>" style="max-height: 80px; border-radius: 4px;" /></div>
            <?php endif; ?>
            <p class="description"><?php _e('Full-width header image for this category.', 'smarttours'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="st_tile_image"><?php _e('Tile Image URL', 'smarttours'); ?></label></th>
        <td>
            <input type="text" name="st_tile_image" id="st_tile_image" value="<?php echo esc_attr($tile_image); ?>" style="width: 80%;" />
            <?php if ($tile_image): ?>
                <div style="margin-top: 8px;"><img src="<?php echo esc_url($tile_image); ?>" style="max-height: 80px; border-radius: 4px;" /></div>
            <?php endif; ?>
            <p class="description"><?php _e('Grid tile image displayed on parent archives.', 'smarttours'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="st_display_order"><?php _e('Display Order', 'smarttours'); ?></label></th>
        <td>
            <input type="number" name="st_display_order" id="st_display_order" value="<?php echo esc_attr($display_order ?: 0); ?>" />
            <p class="description"><?php _e('Ordering weight for mega menu and tiles.', 'smarttours'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('experience_category_edit_form_fields', 'smarttours_edit_term_fields', 10, 2);

function smarttours_save_term_fields($term_id) {
    if (isset($_POST['st_hero_image'])) {
        update_term_meta($term_id, 'st_hero_image', esc_url_raw($_POST['st_hero_image']));
    }
    if (isset($_POST['st_tile_image'])) {
        update_term_meta($term_id, 'st_tile_image', esc_url_raw($_POST['st_tile_image']));
    }
    if (isset($_POST['st_display_order'])) {
        update_term_meta($term_id, 'st_display_order', intval($_POST['st_display_order']));
    }
}
add_action('created_experience_category', 'smarttours_save_term_fields', 10, 2);
add_action('edited_experience_category', 'smarttours_save_term_fields', 10, 2);
