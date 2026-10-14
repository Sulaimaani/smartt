<?php
/**
 * Native Meta Boxes for Experience Custom Post Type
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

function smarttours_register_meta_boxes() {
    add_meta_box(
        'smarttours_experience_details',
        __('Experience Details & Booking', 'smarttours'),
        'smarttours_render_experience_meta_box',
        'experience',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'smarttours_register_meta_boxes');

function smarttours_render_experience_meta_box($post) {
    wp_nonce_field('smarttours_save_meta_box', 'smarttours_meta_box_nonce');

    $address             = get_post_meta($post->ID, '_st_address', true);
    $city                = get_post_meta($post->ID, '_st_city', true);
    $postal_code         = get_post_meta($post->ID, '_st_postal_code', true);
    $latitude            = get_post_meta($post->ID, '_st_latitude', true);
    $longitude           = get_post_meta($post->ID, '_st_longitude', true);
    $phone               = get_post_meta($post->ID, '_st_phone', true);
    $website_url         = get_post_meta($post->ID, '_st_website_url', true);
    $price_from          = get_post_meta($post->ID, '_st_price_from', true);
    $duration            = get_post_meta($post->ID, '_st_duration', true);
    $facebook            = get_post_meta($post->ID, '_st_facebook', true);
    $instagram           = get_post_meta($post->ID, '_st_instagram', true);
    $twitter             = get_post_meta($post->ID, '_st_twitter', true);
    $booking_type        = get_post_meta($post->ID, '_st_booking_type', true) ?: 'none';
    $booking_url         = get_post_meta($post->ID, '_st_booking_url', true);
    $booking_widget_code = get_post_meta($post->ID, '_st_booking_widget_code', true);
    $gallery_ids         = get_post_meta($post->ID, '_st_gallery_ids', true);
    ?>
    <style>
        .st-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .st-meta-field { display: flex; flex-direction: column; }
        .st-meta-field.full { grid-column: span 2; }
        .st-meta-field label { font-weight: 600; margin-bottom: 5px; color: #1e1e1e; font-size: 13px; }
        .st-meta-field input, .st-meta-field select, .st-meta-field textarea { padding: 8px 10px; border: 1px solid #ccd0d4; border-radius: 4px; font-size: 14px; }
        .st-meta-section-title { font-size: 15px; font-weight: bold; border-bottom: 2px solid #ddd; padding-bottom: 6px; margin: 20px 0 10px 0; grid-column: span 2; color: #0073aa; }
        .st-meta-hint { font-size: 12px; color: #666; margin-top: 4px; }
    </style>

    <div class="st-meta-grid">
        <!-- Location & Contact -->
        <div class="st-meta-section-title"><?php _e('Location & Contact Information', 'smarttours'); ?></div>

        <div class="st-meta-field full">
            <label for="_st_address"><?php _e('Street Address', 'smarttours'); ?></label>
            <input type="text" name="_st_address" id="_st_address" value="<?php echo esc_attr($address); ?>" placeholder="e.g. 1730 Mission Hill Rd" />
        </div>

        <div class="st-meta-field">
            <label for="_st_city"><?php _e('City / Municipality', 'smarttours'); ?></label>
            <input type="text" name="_st_city" id="_st_city" value="<?php echo esc_attr($city ?: 'Kelowna'); ?>" placeholder="e.g. West Kelowna" />
        </div>

        <div class="st-meta-field">
            <label for="_st_postal_code"><?php _e('Postal Code', 'smarttours'); ?></label>
            <input type="text" name="_st_postal_code" id="_st_postal_code" value="<?php echo esc_attr($postal_code); ?>" placeholder="e.g. V4T 2E4" />
        </div>

        <div class="st-meta-field">
            <label for="_st_latitude"><?php _e('Latitude', 'smarttours'); ?></label>
            <input type="text" name="_st_latitude" id="_st_latitude" value="<?php echo esc_attr($latitude); ?>" placeholder="e.g. 49.8354" />
            <span class="st-meta-hint"><?php _e('For Leaflet map pins and distance calculation.', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field">
            <label for="_st_longitude"><?php _e('Longitude', 'smarttours'); ?></label>
            <input type="text" name="_st_longitude" id="_st_longitude" value="<?php echo esc_attr($longitude); ?>" placeholder="e.g. -119.5841" />
        </div>

        <div class="st-meta-field">
            <label for="_st_phone"><?php _e('Phone Number', 'smarttours'); ?></label>
            <input type="text" name="_st_phone" id="_st_phone" value="<?php echo esc_attr($phone); ?>" placeholder="e.g. (250) 768-7604" />
        </div>

        <div class="st-meta-field">
            <label for="_st_website_url"><?php _e('Official Website URL', 'smarttours'); ?></label>
            <input type="url" name="_st_website_url" id="_st_website_url" value="<?php echo esc_url($website_url); ?>" placeholder="https://..." />
        </div>

        <!-- Experience Details & Pricing -->
        <div class="st-meta-section-title"><?php _e('Tour Details & Pricing', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_price_from"><?php _e('Price From (CAD)', 'smarttours'); ?></label>
            <input type="text" name="_st_price_from" id="_st_price_from" value="<?php echo esc_attr($price_from); ?>" placeholder="e.g. 45 or Free" />
            <span class="st-meta-hint"><?php _e('Numbers only or text like "Free".', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field">
            <label for="_st_duration"><?php _e('Duration', 'smarttours'); ?></label>
            <input type="text" name="_st_duration" id="_st_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g. 2 hours or Half Day" />
        </div>

        <!-- Booking Configuration -->
        <div class="st-meta-section-title"><?php _e('Booking Configuration', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_booking_type"><?php _e('Booking Type', 'smarttours'); ?></label>
            <select name="_st_booking_type" id="_st_booking_type">
                <option value="none" <?php selected($booking_type, 'none'); ?>><?php _e('None (Website Link only)', 'smarttours'); ?></option>
                <option value="affiliate_link" <?php selected($booking_type, 'affiliate_link'); ?>><?php _e('Affiliate Link (Tracked redirect)', 'smarttours'); ?></option>
                <option value="booking_widget" <?php selected($booking_type, 'booking_widget'); ?>><?php _e('Booking Widget (Embed Bókun, Rezdy, Viator, etc.)', 'smarttours'); ?></option>
                <option value="external_link" <?php selected($booking_type, 'external_link'); ?>><?php _e('External Link (Direct vendor booking)', 'smarttours'); ?></option>
            </select>
        </div>

        <div class="st-meta-field">
            <label for="_st_booking_url"><?php _e('Booking / Affiliate URL', 'smarttours'); ?></label>
            <input type="url" name="_st_booking_url" id="_st_booking_url" value="<?php echo esc_url($booking_url); ?>" placeholder="https://..." />
            <span class="st-meta-hint"><?php _e('Used for affiliate and external link types.', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field full">
            <label for="_st_booking_widget_code"><?php _e('Booking Widget Code (Embed Script / iframe)', 'smarttours'); ?></label>
            <textarea name="_st_booking_widget_code" id="_st_booking_widget_code" rows="4" placeholder="Paste embed code from Bókun / Rezdy / Viator / GetYourGuide here..."><?php echo esc_textarea($booking_widget_code); ?></textarea>
            <span class="st-meta-hint"><?php _e('Rendered when booking type is set to Booking Widget.', 'smarttours'); ?></span>
        </div>

        <!-- Social Media -->
        <div class="st-meta-section-title"><?php _e('Social Links & Media Gallery', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_facebook"><?php _e('Facebook URL', 'smarttours'); ?></label>
            <input type="url" name="_st_facebook" id="_st_facebook" value="<?php echo esc_url($facebook); ?>" placeholder="https://facebook.com/..." />
        </div>

        <div class="st-meta-field">
            <label for="_st_instagram"><?php _e('Instagram URL', 'smarttours'); ?></label>
            <input type="url" name="_st_instagram" id="_st_instagram" value="<?php echo esc_url($instagram); ?>" placeholder="https://instagram.com/..." />
        </div>

        <div class="st-meta-field">
            <label for="_st_twitter"><?php _e('X (Twitter) URL', 'smarttours'); ?></label>
            <input type="url" name="_st_twitter" id="_st_twitter" value="<?php echo esc_url($twitter); ?>" placeholder="https://x.com/..." />
        </div>

        <div class="st-meta-field full">
            <label for="_st_gallery_ids"><?php _e('Gallery Images (comma separated image URLs or attachment IDs)', 'smarttours'); ?></label>
            <input type="text" name="_st_gallery_ids" id="_st_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>" placeholder="https://..., https://..." />
            <span class="st-meta-hint"><?php _e('Up to 5 images for the hero lightbox carousel.', 'smarttours'); ?></span>
        </div>
    </div>
    <?php
}

function smarttours_save_experience_meta_box($post_id) {
    if (!isset($_POST['smarttours_meta_box_nonce']) || !wp_verify_nonce($_POST['smarttours_meta_box_nonce'], 'smarttours_save_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        '_st_address'             => 'sanitize_text_field',
        '_st_city'                => 'sanitize_text_field',
        '_st_postal_code'         => 'sanitize_text_field',
        '_st_latitude'            => 'sanitize_text_field',
        '_st_longitude'           => 'sanitize_text_field',
        '_st_phone'               => 'sanitize_text_field',
        '_st_website_url'         => 'esc_url_raw',
        '_st_price_from'          => 'sanitize_text_field',
        '_st_duration'            => 'sanitize_text_field',
        '_st_facebook'            => 'esc_url_raw',
        '_st_instagram'           => 'esc_url_raw',
        '_st_twitter'             => 'esc_url_raw',
        '_st_booking_type'        => 'sanitize_text_field',
        '_st_booking_url'         => 'esc_url_raw',
        '_st_booking_widget_code' => 'wp_kses_post',
        '_st_gallery_ids'         => 'sanitize_text_field',
    );

    foreach ($fields as $field => $sanitizer) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitizer, $_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_experience', 'smarttours_save_experience_meta_box');
