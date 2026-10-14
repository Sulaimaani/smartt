<?php
/**
 * Single Blog Post Template
 *
 * @package SmartTours
 */

get_header();

$thumb = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=1200&q=80';
?>

<main id="primary" class="site-main" role="main">
    <section class="st-hero" style="background-image: url('<?php echo esc_url($thumb); ?>');">
        <div class="st-container">
            <div class="st-hero__content">
                <?php smarttours_render_breadcrumbs(); ?>
                <span style="font-family: var(--font-sans); font-size: 0.8125rem; font-weight: 800; color: var(--accent-yellow); text-transform: uppercase;">
                    OKANAGAN TRAIL & LIFESTYLE STORY
                </span>
                <h1 class="st-hero__title" style="margin-top: 10px;"><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

    <div class="st-container" style="padding: 60px 20px 90px 20px; max-width: 850px;">
        <article class="st-post-content" style="font-size: 1.15rem; line-height: 1.85;">
            <?php
            while (have_posts()): the_post();
                the_content();
            endwhile;
            ?>
        </article>

        <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid var(--border);">
            <a href="<?php echo esc_url(home_url('/experiences/')); ?>" class="st-btn st-btn--primary">
                &larr; <?php _e('BACK TO EXPERIENCES', 'smarttours'); ?>
            </a>
        </div>
    </div>
</main>

<?php
get_footer();
