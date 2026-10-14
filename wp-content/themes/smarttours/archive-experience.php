<?php
/**
 * Experiences Landing Page & Home Archive
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();

$hero_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80';
?>

<main id="primary" class="site-main" role="main">
    <!-- Brand Hero Section with Smart Interactive Tour Finder -->
    <section class="st-brand-hero" style="background-image: url('<?php echo esc_url($hero_img); ?>');">
        <div class="st-brand-hero__overlay"></div>
        <div class="st-container st-brand-hero__content">
            <div class="st-hero-pill-badge">
                <span>🇨🇦</span>
                <span>CANADA SMART TOURS &bull; OKANAGAN VALLEY, BC</span>
            </div>

            <h1 class="st-brand-hero__title">
                DISCOVER &amp; BOOK THE OKANAGAN'S BEST TOURS
            </h1>

            <p class="st-brand-hero__desc">
                Welcome to Canada's most visited wine, lake, and mountain destination. Browse curated sommelier tastings, private boat charters, historic rail trails, and family adventures across Kelowna, West Kelowna, Lake Country &amp; Penticton.
            </p>

            <!-- Smart Interactive Tour Finder Box -->
            <div class="st-hero-search-card">
                <form action="<?php echo esc_url(home_url('/experiences/')); ?>" method="get" class="st-hero-search-form">
                    <div class="st-hero-search-field">
                        <label for="hero-search-kw">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span>WHAT ARE YOU LOOKING FOR?</span>
                        </label>
                        <input type="text" id="hero-search-kw" name="keyword" placeholder="e.g. Wine tour, E-bike, Pontoon boat, Skiing..." />
                    </div>

                    <div class="st-hero-search-field st-hero-search-select">
                        <label for="hero-search-cat">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            <span>CATEGORY</span>
                        </label>
                        <select id="hero-search-cat" name="experience_category">
                            <option value="">All Okanagan Experiences</option>
                            <?php
                            $all_main_cats = get_terms(array(
                                'taxonomy'   => 'experience_category',
                                'parent'     => 0,
                                'hide_empty' => false,
                                'meta_key'   => 'st_display_order',
                                'orderby'    => 'meta_value_num',
                                'order'      => 'ASC',
                            ));
                            if (!empty($all_main_cats) && !is_wp_error($all_main_cats)):
                                foreach ($all_main_cats as $mc):
                                    ?>
                                    <option value="<?php echo esc_attr($mc->slug); ?>"><?php echo esc_html($mc->name); ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>

                    <div class="st-hero-search-submit">
                        <button type="submit" class="st-btn-search">
                            <span>FIND EXPERIENCES</span>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                </form>

                <!-- Quick category chips -->
                <div class="st-hero-chips">
                    <span class="st-chips-label">Popular:</span>
                    <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>" class="st-chip">🍷 Wine Touring</a>
                    <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/lake-activities/boating-marinas/')); ?>" class="st-chip">🚤 Lake Boat Charters</a>
                    <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/myra-canyon-trestles/')); ?>" class="st-chip">🚴 Myra Trestles E-Bikes</a>
                    <a href="<?php echo esc_url(home_url('/experiences/food-drink/craft-beverages/breweries/')); ?>" class="st-chip">🍺 Craft Breweries</a>
                    <a href="<?php echo esc_url(home_url('/experiences/family-friendly/')); ?>" class="st-chip">👨‍👩‍👧 Family Friendly</a>
                </div>
            </div>

            <!-- Trust Bar -->
            <div class="st-trust-strip">
                <div class="st-trust-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#F5EE3A"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span>Top-Rated Okanagan Operators</span>
                </div>
                <div class="st-trust-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#F5EE3A"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Instant &amp; Direct Booking</span>
                </div>
                <div class="st-trust-item">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="#F5EE3A"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2v-2zm0-10h2v8h-2V6z"/></svg>
                    <span>100% Okanagan Local Residents</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Experiences Section -->
    <section class="st-featured-section">
        <div class="st-container">
            <div class="st-section-header">
                <div>
                    <span class="st-eyebrow">TRENDING IN THE OKANAGAN</span>
                    <h2 class="st-section-title">TOP RECOMMENDED TOURS &amp; TASTINGS</h2>
                </div>
                <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>" class="st-btn-outline">
                    VIEW ALL TOURS &rarr;
                </a>
            </div>

            <div class="st-experience-grid st-experience-grid--featured">
                <?php
                $featured_posts = get_posts(array(
                    'post_type'      => 'experience',
                    'posts_per_page' => 6,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order date',
                    'order'          => 'DESC',
                ));

                if (!empty($featured_posts)):
                    foreach ($featured_posts as $f_post):
                        echo smarttours_render_experience_card($f_post, 'grid');
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- 12 Experience Categories Grid -->
    <section class="st-categories-section">
        <div class="st-container">
            <div class="st-section-header st-section-header--center">
                <span class="st-eyebrow">EXPLORE BY PASSION</span>
                <h2 class="st-section-title">12 WAYS TO EXPERIENCE THE OKANAGAN</h2>
                <p class="st-section-sub">From world-famous vineyard benches to sandy lake beaches, alpine peaks, and orchard harvest dates.</p>
            </div>

            <div class="st-tiles-grid st-tiles-grid--smart">
                <?php
                if (!empty($all_main_cats) && !is_wp_error($all_main_cats)):
                    foreach ($all_main_cats as $cat):
                        $tile_img = get_term_meta($cat->term_id, 'st_tile_image', true);
                        if (!$tile_img) {
                            $tile_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
                        }
                        $sub_count = wp_count_terms(array(
                            'taxonomy'   => 'experience_category',
                            'parent'     => $cat->term_id,
                            'hide_empty' => false,
                        ));
                        ?>
                        <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="st-tile-card">
                            <div class="st-tile-card__media">
                                <img src="<?php echo esc_url($tile_img); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy" class="st-tile-card__img" />
                                <div class="st-tile-card__gradient"></div>
                            </div>
                            <div class="st-tile-card__content">
                                <span class="st-tile-card__count"><?php echo intval($cat->count); ?> Experiences</span>
                                <h3 class="st-tile-card__title"><?php echo esc_html($cat->name); ?></h3>
                                <span class="st-tile-card__arrow">EXPLORE &rarr;</span>
                            </div>
                        </a>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Why Canada Smart Tours -->
    <section class="st-why-section">
        <div class="st-container">
            <div class="st-section-header st-section-header--center">
                <span class="st-eyebrow">WHY SMARTTOURS.CA</span>
                <h2 class="st-section-title">THE SMART WAY TO TRAVEL THE OKANAGAN</h2>
            </div>

            <div class="st-why-grid">
                <div class="st-why-card">
                    <div class="st-why-icon">🍷</div>
                    <h3>Local Valley Curation</h3>
                    <p>We live right here in the Okanagan Valley. Every winery, charter, and outdoor adventure is hand-selected for quality and visitor enjoyment.</p>
                </div>
                <div class="st-why-card">
                    <div class="st-why-icon">⚡</div>
                    <h3>Direct &amp; Instant Booking</h3>
                    <p>Connect straight to operator booking widgets (Bókun, Rezdy, Viator) with zero markups and instant calendar reservations.</p>
                </div>
                <div class="st-why-card">
                    <div class="st-why-icon">🗺️</div>
                    <h3>Interactive Itinerary Builder</h3>
                    <p>Heart any experience to save it to "My Trip". Review your saved stops on an interactive route map and plan your perfect Okanagan day.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Local Stories / Blog Section -->
    <section class="st-blog-section">
        <div class="st-container">
            <div class="st-section-header">
                <div>
                    <span class="st-eyebrow">OKANAGAN INSIDER STORIES</span>
                    <h2 class="st-section-title">TRAIL GUIDES, WINE ADVICE &amp; LOCAL TIPS</h2>
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
                        $thumb = get_the_post_thumbnail_url($p_item, 'st-card') ?: 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&w=800&q=80';
                        ?>
                        <article class="st-blog-card">
                            <div class="st-blog-card__media">
                                <a href="<?php echo esc_url(get_permalink($p_item)); ?>">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($p_item)); ?>" loading="lazy" />
                                </a>
                            </div>
                            <div class="st-blog-card__body">
                                <span class="st-blog-card__date"><?php echo esc_html(get_the_date('M j, Y', $p_item)); ?></span>
                                <h3 class="st-blog-card__title">
                                    <a href="<?php echo esc_url(get_permalink($p_item)); ?>"><?php echo esc_html(get_the_title($p_item)); ?></a>
                                </h3>
                                <p class="st-blog-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($p_item), 20)); ?></p>
                                <a href="<?php echo esc_url(get_permalink($p_item)); ?>" class="st-blog-card__link">READ STORY &rarr;</a>
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
