<?php
/**
 * Modern Front Page Template — Canada Smart Tours (smarttours.ca)
 *
 * @package SmartTours
 */

get_header();

$hero_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=2000&q=85';
?>

<main id="primary" class="site-main st-home-page" role="main">
    <!-- Hero Section -->
    <section class="st-hero" style="background-image: url('<?php echo esc_url($hero_img); ?>');">
        <div class="st-hero__overlay"></div>
        <div class="st-container st-hero__container">
            <div class="st-hero__content">
                <span class="st-hero__badge">
                    <span class="st-hero__badge-flag">🇨🇦</span>
                    <span>Okanagan Valley, British Columbia</span>
                </span>

                <h1 class="st-hero__title">
                    Discover &amp; Book Unforgettable Okanagan Experiences
                </h1>

                <p class="st-hero__subtitle">
                    Sommelier-guided wine tours, private lake boat charters, historic canyon rail trails, and farm-to-table culinary adventures.
                </p>

                <!-- Big Modern Search Bar with Category & Date -->
                <div class="st-hero-search-box">
                    <form action="<?php echo esc_url(home_url('/experiences/')); ?>" method="get" class="st-hero-search-form">
                        <!-- Keyword input -->
                        <div class="st-hero-field st-hero-field--keyword">
                            <label for="hero-kw" class="st-hero-field__label">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <span>Search</span>
                            </label>
                            <input type="text" id="hero-kw" name="keyword" placeholder="What would you like to do?" autocomplete="off" />
                        </div>

                        <!-- Category dropdown -->
                        <div class="st-hero-field st-hero-field--category">
                            <label for="hero-cat" class="st-hero-field__label">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <span>Category</span>
                            </label>
                            <select id="hero-cat" name="experience_category">
                                <option value="">All Categories</option>
                                <?php
                                $hero_cats = get_terms(array(
                                    'taxonomy'   => 'experience_category',
                                    'parent'     => 0,
                                    'hide_empty' => false,
                                    'meta_key'   => 'st_display_order',
                                    'orderby'    => 'meta_value_num',
                                    'order'      => 'ASC',
                                ));
                                if (!empty($hero_cats) && !is_wp_error($hero_cats)):
                                    foreach ($hero_cats as $hc):
                                        if (is_numeric($hc->name)) continue;
                                        ?>
                                        <option value="<?php echo esc_attr($hc->slug); ?>"><?php echo esc_html($hc->name); ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>

                        <!-- Date picker -->
                        <div class="st-hero-field st-hero-field--date">
                            <label for="hero-date" class="st-hero-field__label">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>When</span>
                            </label>
                            <input type="date" id="hero-date" name="tour_date" />
                        </div>

                        <!-- Submit button -->
                        <div class="st-hero-field st-hero-field--submit">
                            <button type="submit" class="st-btn-hero-search">
                                <span>Search</span>
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </button>
                        </div>
                    </form>

                    <!-- Popular Search Chips -->
                    <div class="st-hero-chips">
                        <span class="st-hero-chips__label">Popular:</span>
                        <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>" class="st-chip">🍷 Wine Touring</a>
                        <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/lake-activities/boating-marinas/')); ?>" class="st-chip">🚤 Lake Boat Charters</a>
                        <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/myra-canyon-trestles/')); ?>" class="st-chip">🚴 Myra Canyon E-Bikes</a>
                        <a href="<?php echo esc_url(home_url('/experiences/food-drink/craft-beverages/breweries/')); ?>" class="st-chip">🍺 Craft Breweries</a>
                        <a href="<?php echo esc_url(home_url('/experiences/family-friendly/')); ?>" class="st-chip">👨‍👩‍👧 Family Friendly</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Experiences in the Okanagan: Horizontal Scroll Carousel -->
    <section class="st-section st-section--top-experiences">
        <div class="st-container">
            <div class="st-section-head">
                <div>
                    <span class="st-section-eyebrow">Curated Valley Tours</span>
                    <h2 class="st-section-title">Top Experiences in the Okanagan</h2>
                </div>
                <div class="st-carousel-nav">
                    <button type="button" class="st-carousel-btn js-carousel-prev" aria-label="<?php esc_attr_e('Previous experiences', 'smarttours'); ?>">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="st-carousel-btn js-carousel-next" aria-label="<?php esc_attr_e('Next experiences', 'smarttours'); ?>">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </div>

            <div class="st-carousel-wrapper">
                <div class="st-carousel-track js-carousel-track">
                    <?php
                    $featured_experiences = get_posts(array(
                        'post_type'      => 'experience',
                        'posts_per_page' => 10,
                        'post_status'    => 'publish',
                        'orderby'        => 'menu_order date',
                        'order'          => 'DESC',
                    ));

                    if (!empty($featured_experiences)):
                        foreach ($featured_experiences as $f_exp):
                            ?>
                            <div class="st-carousel-slide">
                                <?php echo smarttours_render_experience_card($f_exp, 'grid'); ?>
                            </div>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Explore by Category: 12 Image Tiles with Rounded Corners -->
    <section class="st-section st-section--categories">
        <div class="st-container">
            <div class="st-section-head st-section-head--center">
                <div>
                    <span class="st-section-eyebrow">Browse by Interest</span>
                    <h2 class="st-section-title">Explore by Category</h2>
                    <p class="st-section-desc">From world-famous vineyard benches to sandy beaches, pine trails, and orchard tours.</p>
                </div>
            </div>

            <div class="st-category-grid">
                <?php
                if (!empty($hero_cats) && !is_wp_error($hero_cats)):
                    foreach ($hero_cats as $cat):
                        if (is_numeric($cat->name)) continue;

                        $tile_img = get_term_meta($cat->term_id, 'st_tile_image', true);
                        if (!$tile_img) {
                            $tile_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
                        }

                        // Calculate total experiences count including children
                        $cat_query = new WP_Query(array(
                            'post_type'      => 'experience',
                            'posts_per_page' => 1,
                            'fields'         => 'ids',
                            'tax_query'      => array(
                                array(
                                    'taxonomy'         => 'experience_category',
                                    'field'            => 'term_id',
                                    'terms'            => $cat->term_id,
                                    'include_children' => true,
                                )
                            )
                        ));
                        $total_count = intval($cat_query->found_posts);
                        ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="st-category-tile">
                            <div class="st-category-tile__media">
                                <img src="<?php echo esc_url($tile_img); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy" class="st-category-tile__img" />
                                <div class="st-category-tile__gradient"></div>
                            </div>
                            <div class="st-category-tile__content">
                                <h3 class="st-category-tile__title"><?php echo esc_html($cat->name); ?></h3>
                                <?php if ($total_count > 0): ?>
                                    <span class="st-category-tile__count"><?php echo sprintf(_n('%s experience', '%s experiences', $total_count, 'smarttours'), $total_count); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Why Book with Smart Tours: 3 Short Trust Points -->
    <section class="st-section st-section--trust">
        <div class="st-container">
            <div class="st-section-head st-section-head--center">
                <div>
                    <span class="st-section-eyebrow">Trust &amp; Confidence</span>
                    <h2 class="st-section-title">Why Book with Smart Tours</h2>
                </div>
            </div>

            <div class="st-trust-grid">
                <!-- Point 1 -->
                <div class="st-trust-card">
                    <div class="st-trust-card__icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 7.2c0 7.3-8 11.8-8 11.8z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3 class="st-trust-card__title">100% Okanagan Local</h3>
                    <p class="st-trust-card__desc">
                        We live and work right here in the Okanagan Valley. Every winery, charter captain, and outdoor outfitter is hand-vetted for exceptional quality and guest safety.
                    </p>
                </div>

                <!-- Point 2 -->
                <div class="st-trust-card">
                    <div class="st-trust-card__icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M9 16l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="st-trust-card__title">Live Availability</h3>
                    <p class="st-trust-card__desc">
                        Direct connection to local reservation systems (Bókun, Rezdy, Checkfront) with live calendar synchronization so your spot is never double-booked.
                    </p>
                </div>

                <!-- Point 3 -->
                <div class="st-trust-card">
                    <div class="st-trust-card__icon-box">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                    </div>
                    <h3 class="st-trust-card__title">Free Cancellation</h3>
                    <p class="st-trust-card__desc">
                        Plans change, especially while travelling. Enjoy free cancellation and full refunds on the vast majority of our experiences up to 24 hours in advance.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Experiences Strip (SMART TOUCHES) -->
    <section class="st-section st-section--recently-viewed js-recently-viewed-section" style="display:none;">
        <div class="st-container">
            <div class="st-section-head">
                <div>
                    <span class="st-section-eyebrow">Your History</span>
                    <h2 class="st-section-title">Recently Viewed Experiences</h2>
                </div>
            </div>
            <div class="st-recently-viewed-grid js-recently-viewed-grid"></div>
        </div>
    </section>

    <!-- Blog / Local Tips: 3 Cards -->
    <section class="st-section st-section--blog">
        <div class="st-container">
            <div class="st-section-head">
                <div>
                    <span class="st-section-eyebrow">Insider Guides</span>
                    <h2 class="st-section-title">Okanagan Travel Tips &amp; Stories</h2>
                </div>
            </div>

            <div class="st-blog-grid">
                <?php
                $recent_posts = get_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish',
                    'post_type'   => 'post',
                ));

                if (!empty($recent_posts)):
                    foreach ($recent_posts as $p_item):
                        $thumb = get_the_post_thumbnail_url($p_item, 'large') ?: 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80';
                        ?>
                        <article class="st-blog-card">
                            <a href="<?php echo esc_url(get_permalink($p_item)); ?>" class="st-blog-card__media-link">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($p_item)); ?>" loading="lazy" class="st-blog-card__img" />
                            </a>
                            <div class="st-blog-card__body">
                                <span class="st-blog-card__date"><?php echo esc_html(get_the_date('M j, Y', $p_item)); ?></span>
                                <h3 class="st-blog-card__title">
                                    <a href="<?php echo esc_url(get_permalink($p_item)); ?>"><?php echo esc_html(get_the_title($p_item)); ?></a>
                                </h3>
                                <p class="st-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($p_item), 18)); ?></p>
                                <a href="<?php echo esc_url(get_permalink($p_item)); ?>" class="st-blog-card__read-more">
                                    <span>Read Guide</span>
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </div>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
