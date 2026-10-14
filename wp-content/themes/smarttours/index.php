<?php
/**
 * Main Template Fallback
 *
 * @package SmartTours
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <div class="st-container" style="padding: 70px 20px 90px 20px;">
        <h1 style="margin-bottom: 30px;"><?php _e('Latest Articles & News', 'smarttours'); ?></h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php
            if (have_posts()):
                while (have_posts()): the_post();
                    ?>
                    <article class="st-card">
                        <div class="st-card__body">
                            <h2 class="st-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="st-card__details"><?php _e('READ STORY &gt;', 'smarttours'); ?></a>
                        </div>
                    </article>
                    <?php
                endwhile;
            else:
                ?>
                <p><?php _e('No posts found.', 'smarttours'); ?></p>
                <?php
            endif;
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
