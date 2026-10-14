<?php
/**
 * Standard Page Template (Guides, Info, Terms, etc.)
 *
 * @package SmartTours
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <section class="st-hero" style="background-color: var(--black); padding: 70px 0 50px 0;">
        <div class="st-container">
            <div class="st-hero__content">
                <?php smarttours_render_breadcrumbs(); ?>
                <h1 class="st-hero__title"><?php the_title(); ?></h1>
            </div>
        </div>
    </section>

    <div class="st-container" style="padding: 60px 20px 90px 20px; max-width: 900px;">
        <article class="st-page-content" style="font-size: 1.15rem; line-height: 1.8;">
            <?php
            while (have_posts()): the_post();
                the_content();
            endwhile;
            ?>
        </article>
    </div>
</main>

<?php
get_footer();
