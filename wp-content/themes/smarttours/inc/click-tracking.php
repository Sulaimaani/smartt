<?php
/**
 * Click Tracking & Redirect Route (/go/{experience_id}/)
 *
 * @package SmartTours
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create custom database table for tracking clicks
 */
function smarttours_create_clicks_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_clicks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        experience_id bigint(20) unsigned NOT NULL,
        clicked_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        referrer varchar(500) DEFAULT '',
        user_agent varchar(255) DEFAULT '',
        ip_address varchar(45) DEFAULT '',
        target_url text NOT NULL,
        PRIMARY KEY  (id),
        KEY experience_id (experience_id),
        KEY clicked_at (clicked_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'smarttours_create_clicks_table');
add_action('admin_init', function() {
    // Check if table exists, create if not
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_clicks';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        smarttours_create_clicks_table();
    }
});

/**
 * Handle /go/{experience_id}/ redirect
 */
function smarttours_handle_click_redirect() {
    $experience_id = get_query_var('st_go_id');
    if (!$experience_id) {
        return;
    }

    $experience_id = intval($experience_id);
    $post = get_post($experience_id);
    if (!$post || $post->post_type !== 'experience') {
        wp_safe_redirect(home_url('/experiences/'));
        exit;
    }

    $booking_url = get_post_meta($experience_id, '_st_booking_url', true);
    $website_url = get_post_meta($experience_id, '_st_website_url', true);
    $destination = !empty($booking_url) ? $booking_url : (!empty($website_url) ? $website_url : get_permalink($experience_id));

    // Log the click into custom DB table
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_clicks';
    $referrer   = isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field($_SERVER['HTTP_REFERER']) : '';
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? substr(sanitize_text_field($_SERVER['HTTP_USER_AGENT']), 0, 255) : '';
    $ip_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';

    $wpdb->insert(
        $table_name,
        array(
            'experience_id' => $experience_id,
            'clicked_at'    => current_time('mysql'),
            'referrer'      => $referrer,
            'user_agent'    => $user_agent,
            'ip_address'    => $ip_address,
            'target_url'    => $destination,
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s')
    );

    // 302 Redirect with nofollow / sponsored headers
    header("X-Robots-Tag: noindex, nofollow", true);
    wp_redirect($destination, 302);
    exit;
}
add_action('template_redirect', 'smarttours_handle_click_redirect');

/**
 * Register Click Reports admin page
 */
function smarttours_click_reports_menu() {
    add_submenu_page(
        'edit.php?post_type=experience',
        __('Click Reports', 'smarttours'),
        __('Click Reports', 'smarttours'),
        'manage_options',
        'smarttours-click-reports',
        'smarttours_render_click_reports_page'
    );
}
add_action('admin_menu', 'smarttours_click_reports_menu');

/**
 * Handle CSV Export
 */
function smarttours_handle_csv_export() {
    if (isset($_GET['page']) && $_GET['page'] === 'smarttours-click-reports' && isset($_GET['export_csv']) && $_GET['export_csv'] === '1') {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'st_clicks';
        $results = $wpdb->get_results("SELECT c.*, p.post_title FROM $table_name c LEFT JOIN {$wpdb->posts} p ON c.experience_id = p.ID ORDER BY c.clicked_at DESC", ARRAY_A);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=smarttours_clicks_' . date('Y-m-d') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'Experience ID', 'Experience Title', 'Date & Time', 'Target URL', 'Referrer', 'IP Address'));

        foreach ($results as $row) {
            fputcsv($output, array(
                $row['id'],
                $row['experience_id'],
                $row['post_title'] ?: 'Deleted Experience',
                $row['clicked_at'],
                $row['target_url'],
                $row['referrer'],
                $row['ip_address'],
            ));
        }
        fclose($output);
        exit;
    }
}
add_action('admin_init', 'smarttours_handle_csv_export');

/**
 * Render Click Reports Admin Page
 */
function smarttours_render_click_reports_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'st_clicks';

    $date_filter = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '30days';
    $where_sql = '1=1';

    if ($date_filter === 'today') {
        $where_sql = "DATE(c.clicked_at) = CURDATE()";
    } elseif ($date_filter === '7days') {
        $where_sql = "c.clicked_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    } elseif ($date_filter === '30days') {
        $where_sql = "c.clicked_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    }

    $summary = $wpdb->get_row("SELECT COUNT(*) as total_clicks, COUNT(DISTINCT experience_id) as unique_experiences FROM $table_name c WHERE $where_sql");

    $experiences_stats = $wpdb->get_results("
        SELECT c.experience_id, p.post_title, COUNT(*) as click_count, MAX(c.clicked_at) as last_clicked, MAX(c.target_url) as target_url
        FROM $table_name c
        LEFT JOIN {$wpdb->posts} p ON c.experience_id = p.ID
        WHERE $where_sql
        GROUP BY c.experience_id, p.post_title
        ORDER BY click_count DESC
        LIMIT 50
    ");

    $recent_clicks = $wpdb->get_results("
        SELECT c.*, p.post_title
        FROM $table_name c
        LEFT JOIN {$wpdb->posts} p ON c.experience_id = p.ID
        WHERE $where_sql
        ORDER BY c.clicked_at DESC
        LIMIT 30
    ");
    ?>
    <div class="wrap">
        <h1 style="display:flex; justify-content:space-between; align-items:center;">
            <span><?php _e('Experience Click & Affiliate Reports', 'smarttours'); ?></span>
            <a href="<?php echo esc_url(add_query_arg('export_csv', '1')); ?>" class="button button-primary"><?php _e('Export to CSV', 'smarttours'); ?></a>
        </h1>

        <!-- Date Range Filter -->
        <div class="tablenav top" style="margin: 20px 0;">
            <form method="get">
                <input type="hidden" name="post_type" value="experience" />
                <input type="hidden" name="page" value="smarttours-click-reports" />
                <select name="date_range" onchange="this.form.submit()">
                    <option value="today" <?php selected($date_filter, 'today'); ?>><?php _e('Today', 'smarttours'); ?></option>
                    <option value="7days" <?php selected($date_filter, '7days'); ?>><?php _e('Last 7 Days', 'smarttours'); ?></option>
                    <option value="30days" <?php selected($date_filter, '30days'); ?>><?php _e('Last 30 Days', 'smarttours'); ?></option>
                    <option value="all" <?php selected($date_filter, 'all'); ?>><?php _e('All Time', 'smarttours'); ?></option>
                </select>
                <noscript><input type="submit" value="Filter" class="button" /></noscript>
            </form>
        </div>

        <!-- Metrics Cards -->
        <div style="display: flex; gap: 20px; margin-bottom: 25px;">
            <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); flex: 1;">
                <div style="font-size: 13px; color: #666; text-transform: uppercase; font-weight: bold;"><?php _e('Total Clicks Tracked', 'smarttours'); ?></div>
                <div style="font-size: 32px; font-weight: bold; color: #0073aa; margin-top: 5px;"><?php echo number_format($summary->total_clicks ?: 0); ?></div>
            </div>
            <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); flex: 1;">
                <div style="font-size: 13px; color: #666; text-transform: uppercase; font-weight: bold;"><?php _e('Experiences Clicked', 'smarttours'); ?></div>
                <div style="font-size: 32px; font-weight: bold; color: #46b450; margin-top: 5px;"><?php echo number_format($summary->unique_experiences ?: 0); ?></div>
            </div>
        </div>

        <!-- Top Experiences Clicked -->
        <h2><?php _e('Clicks by Experience', 'smarttours'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th><?php _e('Experience', 'smarttours'); ?></th>
                    <th style="width: 120px;"><?php _e('Total Clicks', 'smarttours'); ?></th>
                    <th><?php _e('Target URL', 'smarttours'); ?></th>
                    <th style="width: 180px;"><?php _e('Last Clicked', 'smarttours'); ?></th>
                    <th style="width: 120px;"><?php _e('Action', 'smarttours'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($experiences_stats)): ?>
                    <tr><td colspan="6"><?php _e('No clicks recorded in this period yet.', 'smarttours'); ?></td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($experiences_stats as $stat): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo esc_html($stat->post_title ?: 'Deleted Experience #' . $stat->experience_id); ?></strong></td>
                            <td><span class="badge" style="background:#0073aa; color:#fff; padding:3px 8px; border-radius:12px; font-weight:bold;"><?php echo $stat->click_count; ?></span></td>
                            <td><a href="<?php echo esc_url($stat->target_url); ?>" target="_blank" style="word-break: break-all;"><?php echo esc_html(wp_trim_words($stat->target_url, 8)); ?></a></td>
                            <td><?php echo esc_html($stat->last_clicked); ?></td>
                            <td>
                                <a href="<?php echo esc_url(get_permalink($stat->experience_id)); ?>" class="button button-small" target="_blank"><?php _e('View', 'smarttours'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Recent Activity Feed -->
        <h2 style="margin-top: 35px;"><?php _e('Recent Click Activity (Latest 30)', 'smarttours'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 170px;"><?php _e('Timestamp', 'smarttours'); ?></th>
                    <th><?php _e('Experience', 'smarttours'); ?></th>
                    <th><?php _e('Referrer', 'smarttours'); ?></th>
                    <th><?php _e('Target URL', 'smarttours'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_clicks)): ?>
                    <tr><td colspan="4"><?php _e('No clicks recorded yet.', 'smarttours'); ?></td></tr>
                <?php else: ?>
                    <?php foreach ($recent_clicks as $click): ?>
                        <tr>
                            <td><?php echo esc_html($click->clicked_at); ?></td>
                            <td><strong><?php echo esc_html($click->post_title ?: '#' . $click->experience_id); ?></strong></td>
                            <td><small><?php echo esc_html($click->referrer ?: 'Direct / None'); ?></small></td>
                            <td><small style="word-break: break-all;"><?php echo esc_html($click->target_url); ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
