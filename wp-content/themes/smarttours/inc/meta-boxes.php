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
        __('Experience Details, Viator Layout Sections & Booking', 'smarttours'),
        'smarttours_render_experience_meta_box',
        'experience',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'smarttours_register_meta_boxes');

function smarttours_render_experience_meta_box($post) {
    wp_nonce_field('smarttours_save_meta_box', 'smarttours_meta_box_nonce');

    // Basic & Contact
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

    // Viator-Style Extended Meta Fields
    $badge               = get_post_meta($post->ID, '_st_badge', true);
    $rating              = get_post_meta($post->ID, '_st_rating', true);
    $review_count        = get_post_meta($post->ID, '_st_review_count', true);
    $language            = get_post_meta($post->ID, '_st_language', true);
    $mobile_ticket       = get_post_meta($post->ID, '_st_mobile_ticket', true);
    $pickup              = get_post_meta($post->ID, '_st_pickup', true);
    $included            = get_post_meta($post->ID, '_st_included', true);
    $excluded            = get_post_meta($post->ID, '_st_excluded', true);
    $meeting_point       = get_post_meta($post->ID, '_st_meeting_point', true);
    $end_point           = get_post_meta($post->ID, '_st_end_point', true);
    $operating_hours     = get_post_meta($post->ID, '_st_operating_hours', true);
    $what_to_expect      = get_post_meta($post->ID, '_st_what_to_expect', true);
    $additional_info     = get_post_meta($post->ID, '_st_additional_info', true);
    $cancellation_policy = get_post_meta($post->ID, '_st_cancellation_policy', true);
    ?>
    <style>
        .st-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .st-meta-field { display: flex; flex-direction: column; }
        .st-meta-field.full { grid-column: span 2; }
        .st-meta-field label { font-weight: 600; margin-bottom: 5px; color: #1e1e1e; font-size: 13px; }
        .st-meta-field input, .st-meta-field select, .st-meta-field textarea { padding: 8px 10px; border: 1px solid #ccd0d4; border-radius: 4px; font-size: 14px; }
        .st-meta-section-title { font-size: 15px; font-weight: bold; border-bottom: 2px solid #ddd; padding-bottom: 6px; margin: 20px 0 10px 0; grid-column: span 2; color: #0F7B5F; }
        .st-meta-hint { font-size: 12px; color: #666; margin-top: 4px; }
    </style>

    <div class="st-meta-grid">
        <!-- Quick Facts & Badges -->
        <div class="st-meta-section-title"><?php _e('Quick Facts, Ratings & Badges', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_badge"><?php _e('Card Badge', 'smarttours'); ?></label>
            <input type="text" name="_st_badge" id="_st_badge" value="<?php echo esc_attr($badge); ?>" placeholder="e.g. Likely to Sell Out, Free Cancellation, Bestseller" />
            <span class="st-meta-hint"><?php _e('Displayed on experience cards and header.', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field">
            <label for="_st_rating"><?php _e('Star Rating (0 - 5)', 'smarttours'); ?></label>
            <input type="text" name="_st_rating" id="_st_rating" value="<?php echo esc_attr($rating); ?>" placeholder="e.g. 4.9" />
        </div>

        <div class="st-meta-field">
            <label for="_st_review_count"><?php _e('Review Count', 'smarttours'); ?></label>
            <input type="number" name="_st_review_count" id="_st_review_count" value="<?php echo esc_attr($review_count); ?>" placeholder="e.g. 128" />
        </div>

        <div class="st-meta-field">
            <label for="_st_language"><?php _e('Offered Languages', 'smarttours'); ?></label>
            <input type="text" name="_st_language" id="_st_language" value="<?php echo esc_attr($language ?: 'English'); ?>" placeholder="e.g. English, French" />
        </div>

        <div class="st-meta-field">
            <label for="_st_mobile_ticket"><?php _e('Mobile Ticket', 'smarttours'); ?></label>
            <select name="_st_mobile_ticket" id="_st_mobile_ticket">
                <option value="yes" <?php selected($mobile_ticket, 'yes'); ?>><?php _e('Yes - Mobile ticket accepted', 'smarttours'); ?></option>
                <option value="no" <?php selected($mobile_ticket, 'no'); ?>><?php _e('No - Printed voucher required', 'smarttours'); ?></option>
            </select>
        </div>

        <div class="st-meta-field">
            <label for="_st_pickup"><?php _e('Pickup / Transportation', 'smarttours'); ?></label>
            <input type="text" name="_st_pickup" id="_st_pickup" value="<?php echo esc_attr($pickup); ?>" placeholder="e.g. Pickup offered or Meeting point specified" />
        </div>

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
        <div class="st-meta-section-title"><?php _e('Pricing & Duration', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_price_from"><?php _e('Price From (CAD)', 'smarttours'); ?></label>
            <input type="text" name="_st_price_from" id="_st_price_from" value="<?php echo esc_attr($price_from); ?>" placeholder="e.g. 45 or Free" />
        </div>

        <div class="st-meta-field">
            <label for="_st_duration"><?php _e('Duration', 'smarttours'); ?></label>
            <input type="text" name="_st_duration" id="_st_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g. 2 hours or Half Day" />
        </div>

        <!-- Inclusions & Exclusions -->
        <div class="st-meta-section-title"><?php _e('What\'s Included & Excluded (Viator-style checklist)', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_included"><?php _e('What\'s Included (One item per line)', 'smarttours'); ?></label>
            <textarea name="_st_included" id="_st_included" rows="5" placeholder="Guided sommelier vineyard tour&#10;Tasting flight of 4 reserve wines&#10;Complimentary charcuterie pairing&#10;Bottled spring water"><?php echo esc_textarea($included); ?></textarea>
            <span class="st-meta-hint"><?php _e('Rendered with green checkmark icons. Hidden if empty.', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field">
            <label for="_st_excluded"><?php _e('What\'s Excluded (One item per line)', 'smarttours'); ?></label>
            <textarea name="_st_excluded" id="_st_excluded" rows="5" placeholder="Hotel pickup & drop-off&#10;Gratuities & tips&#10;Additional bottles for purchase"><?php echo esc_textarea($excluded); ?></textarea>
            <span class="st-meta-hint"><?php _e('Rendered with grey/red cross icons. Hidden if empty.', 'smarttours'); ?></span>
        </div>

        <!-- Meeting & Pickup Section -->
        <div class="st-meta-section-title"><?php _e('Meeting & Pickup Details', 'smarttours'); ?></div>

        <div class="st-meta-field">
            <label for="_st_meeting_point"><?php _e('Meeting Point Instructions', 'smarttours'); ?></label>
            <textarea name="_st_meeting_point" id="_st_meeting_point" rows="3" placeholder="e.g. Meet your guide at the visitor center courtyard by the central fountain."><?php echo esc_textarea($meeting_point); ?></textarea>
        </div>

        <div class="st-meta-field">
            <label for="_st_end_point"><?php _e('End Point', 'smarttours'); ?></label>
            <input type="text" name="_st_end_point" id="_st_end_point" value="<?php echo esc_attr($end_point); ?>" placeholder="e.g. This activity ends back at the meeting point." />
        </div>

        <!-- Operating Hours, Expect & Additional Info -->
        <div class="st-meta-section-title"><?php _e('What to Expect, Additional Info & Policies', 'smarttours'); ?></div>

        <div class="st-meta-field full">
            <label for="_st_operating_hours"><?php _e('Operating Hours (Leave empty if none)', 'smarttours'); ?></label>
            <input type="text" name="_st_operating_hours" id="_st_operating_hours" value="<?php echo esc_attr($operating_hours); ?>" placeholder="e.g. Daily tours depart at 10:00 AM, 1:00 PM, and 3:30 PM (May - Oct)" />
            <span class="st-meta-hint"><?php _e('Hidden if empty.', 'smarttours'); ?></span>
        </div>

        <div class="st-meta-field full">
            <label for="_st_what_to_expect"><?php _e('What to Expect (Paragraphs / Description)', 'smarttours'); ?></label>
            <textarea name="_st_what_to_expect" id="_st_what_to_expect" rows="4" placeholder="Detailed walk-through of the tour experience and stops..."><?php echo esc_textarea($what_to_expect); ?></textarea>
        </div>

        <div class="st-meta-field full">
            <label for="_st_additional_info"><?php _e('Additional Info (One bullet per line)', 'smarttours'); ?></label>
            <textarea name="_st_additional_info" id="_st_additional_info" rows="4" placeholder="Confirmation will be received at time of booking&#10;Minimum drinking age is 19 years&#10;Operates in all weather conditions; please dress appropriately&#10;Near public transportation"><?php echo esc_textarea($additional_info); ?></textarea>
        </div>

        <div class="st-meta-field full">
            <label for="_st_cancellation_policy"><?php _e('Cancellation Policy', 'smarttours'); ?></label>
            <textarea name="_st_cancellation_policy" id="_st_cancellation_policy" rows="3" placeholder="For a full refund, cancel at least 24 hours in advance of the start date of the experience."><?php echo esc_textarea($cancellation_policy); ?></textarea>
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

        <!-- Social Media & Media Gallery -->
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
            <span class="st-meta-hint"><?php _e('Images for the Viator-style vertical thumbnail and carousel gallery.', 'smarttours'); ?></span>
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

        // Viator Extended Fields
        '_st_badge'               => 'sanitize_text_field',
        '_st_rating'              => 'sanitize_text_field',
        '_st_review_count'        => 'sanitize_text_field',
        '_st_language'            => 'sanitize_text_field',
        '_st_mobile_ticket'       => 'sanitize_text_field',
        '_st_pickup'              => 'sanitize_text_field',
        '_st_included'            => 'sanitize_textarea_field',
        '_st_excluded'            => 'sanitize_textarea_field',
        '_st_meeting_point'       => 'sanitize_textarea_field',
        '_st_end_point'           => 'sanitize_text_field',
        '_st_operating_hours'     => 'sanitize_text_field',
        '_st_what_to_expect'      => 'wp_kses_post',
        '_st_additional_info'     => 'sanitize_textarea_field',
        '_st_cancellation_policy' => 'sanitize_textarea_field',
    );

    foreach ($fields as $field => $sanitizer) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitizer, $_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_experience', 'smarttours_save_experience_meta_box');
