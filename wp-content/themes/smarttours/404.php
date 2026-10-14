<?php
/**
 * 404 Not Found Template
 *
 * @package SmartTours
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <div class="st-container" style="padding: 100px 20px; text-align: center; max-width: 700px;">
        <span style="font-family: var(--font-heading); font-size: 6rem; line-height: 1; color: var(--accent-yellow); text-shadow: 2px 2px 0 #000; display: block;">404</span>
        <h1 style="font-size: 3rem; margin: 20px 0 15px 0;"><?php _e('PAGE NOT FOUND', 'smarttours'); ?></h1>
        <p style="font-size: 1.2rem; color: #666; margin-bottom: 30px;">
            <?php _e('The Okanagan experience or trail you are looking for may have moved or no longer exists.', 'smarttours'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/experiences/')); ?>" class="st-btn st-btn--primary">
            <?php _e('EXPLORE ALL EXPERIENCES', 'smarttours'); ?>
        </a>
    </div>
</main>

<?php
get_footer();
