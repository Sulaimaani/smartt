<?php
/**
 * Taxonomy Archive: Experience Category (Main, Sub, and Sub-sub Categories)
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();

$current_term = get_queried_object();
$hero_img     = get_term_meta($current_term->term_id, 'st_hero_image', true);
if (!$hero_img) {
    $hero_img = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80';
}

// Child terms
$child_terms = get_terms(array(
    'taxonomy'   => 'experience_category',
    'parent'     => $current_term->term_id,
    'hide_empty' => false,
));

// Linked Guide Pages & Blog Posts for this term
$linked_articles = get_posts(array(
    'post_type'      => array('post', 'page'),
    'post_status'    => 'publish',
    'posts_per_page' => 10,
    'meta_query'     => array(
        array(
            'key'   => '_st_parent_term_id',
            'value' => $current_term->term_id,
        )
    )
));

// Real amenities with counts
$all_amenities = get_terms(array(
    'taxonomy'   => 'amenity',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
));

// Initial Query for experiences in this term & children
$initial_paged = max(1, get_query_var('paged'));
$exp_query = new WP_Query(array(
    'post_type'      => 'experience',
    'post_status'    => 'publish',
    'posts_per_page' => 15,
    'paged'          => $initial_paged,
    'tax_query'      => array(
        array(
            'taxonomy'         => 'experience_category',
            'field'            => 'term_id',
            'terms'            => $current_term->term_id,
            'include_children' => true,
        )
    )
));
?>

<main id="primary" class="site-main" role="main">
    <!-- Category Hero Banner with Breadcrumbs -->
    <section class="st-category-hero" style="background-image: url('<?php echo esc_url($hero_img); ?>');">
        <div class="st-category-hero__overlay"></div>
        <div class="st-container st-category-hero__content">
            <?php smarttours_render_breadcrumbs(); ?>
            <div class="st-hero-pill-badge" style="margin-bottom: 12px;">
                <span>🇨🇦</span>
                <span>CANADA SMART TOURS &bull; OKANAGAN VALLEY</span>
            </div>
            <h1 class="st-category-hero__title"><?php echo esc_html($current_term->name); ?></h1>
            <?php if (!empty($current_term->description)): ?>
                <p class="st-category-hero__desc"><?php echo esc_html($current_term->description); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <div class="st-container">
        <!-- Subcategories Grid (if child terms exist) -->
        <?php if (!empty($child_terms) || !empty($linked_articles)): ?>
            <section class="st-subcategories-section" style="padding-top: 40px; padding-bottom: 20px;">
                <?php if (!empty($child_terms)): ?>
                    <div class="st-section-header">
                        <div>
                            <span class="st-eyebrow">SUB-CATEGORIES</span>
                            <h2 style="font-size: 1.8rem; margin-top: 4px;"><?php printf(__('Explore %s Options', 'smarttours'), esc_html($current_term->name)); ?></h2>
                        </div>
                    </div>
                    <div class="st-subcats-grid">
                        <?php foreach ($child_terms as $child):
                            $child_tile = get_term_meta($child->term_id, 'st_tile_image', true) ?: $hero_img;
                            ?>
                            <a href="<?php echo esc_url(get_term_link($child)); ?>" class="st-subcat-pill">
                                <span class="st-subcat-pill__title"><?php echo esc_html($child->name); ?></span>
                                <span class="st-subcat-pill__count"><?php echo intval($child->count); ?> tours &rarr;</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($linked_articles)): ?>
                    <h3 style="font-size: 1.3rem; margin-top: 25px; margin-bottom: 12px;"><?php _e('Local Guides & Stories', 'smarttours'); ?></h3>
                    <div class="st-subcards-grid">
                        <?php foreach ($linked_articles as $art):
                            $is_guide = ($art->post_type === 'page');
                            ?>
                            <a href="<?php echo esc_url(get_permalink($art)); ?>" class="st-subcard">
                                <span class="st-subcard__badge"><?php echo $is_guide ? __('GUIDE', 'smarttours') : __('BLOG', 'smarttours'); ?></span>
                                <h4 class="st-subcard__title"><?php echo esc_html(get_the_title($art)); ?></h4>
                                <p style="font-size: 0.875rem; color: #666; margin: 0;"><?php echo esc_html(wp_trim_words($art->post_content, 12)); ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

        <!-- Main Listing Area (Smart Sidebar + Toolbar + Experiences Grid) -->
        <div class="st-archive-layout js-filter-wrapper">
            <input type="hidden" name="filter_term_id" value="<?php echo esc_attr($current_term->term_id); ?>" />

            <!-- Smart Modern Filter Sidebar -->
            <aside class="st-smart-sidebar" role="complementary" aria-label="<?php esc_attr_e('Filter experiences', 'smarttours'); ?>">
                <div class="st-smart-sidebar__header">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        <h3>FILTERS</h3>
                    </div>
                    <button type="button" class="st-clear-btn-text js-clear-filters">Clear All</button>
                </div>

                <!-- Keyword Search -->
                <div class="st-filter-block">
                    <label class="st-filter-block__title" for="filter_keyword"><?php _e('SEARCH KEYWORD', 'smarttours'); ?></label>
                    <form class="st-smart-search-box js-search-form">
                        <input type="text" name="filter_keyword" id="filter_keyword" placeholder="<?php esc_attr_e('e.g. Winery, kayak, e-bike...', 'smarttours'); ?>" />
                        <button type="submit" aria-label="<?php esc_attr_e('Search', 'smarttours'); ?>">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </form>
                </div>

                <!-- Real Amenities Checkboxes with Counts -->
                <div class="st-filter-block">
                    <span class="st-filter-block__title"><?php _e('AMENITIES &amp; FEATURES', 'smarttours'); ?></span>
                    <ul class="st-smart-checkbox-list">
                        <?php
                        if (!empty($all_amenities) && !is_wp_error($all_amenities)):
                            $cnt = 0;
                            foreach ($all_amenities as $am):
                                // Skip any accidental non-string terms
                                if (is_numeric($am->name)) continue;
                                $cnt++;
                                $is_extra = ($cnt > 5);
                                ?>
                                <li class="st-smart-checkbox-item <?php echo $is_extra ? 'st-amenity-extra' : ''; ?>" <?php echo $is_extra ? 'style="display:none;"' : ''; ?>>
                                    <label class="st-checkbox-label">
                                        <input type="checkbox" name="amenity[]" value="<?php echo esc_attr($am->term_id); ?>" />
                                        <span class="st-checkbox-custom"></span>
                                        <span class="st-checkbox-text"><?php echo esc_html($am->name); ?></span>
                                        <span class="st-checkbox-badge"><?php echo intval($am->count); ?></span>
                                    </label>
                                </li>
                                <?php
                            endforeach;

                            if ($cnt > 5):
                                ?>
                                <li>
                                    <button type="button" class="st-show-more-link js-show-more-amenities">
                                        + Show more amenities (<?php echo ($cnt - 5); ?>)
                                    </button>
                                </li>
                                <?php
                            endif;
                        endif;
                        ?>
                    </ul>
                </div>

                <div style="margin-top: 25px;">
                    <button type="button" class="st-btn st-btn--primary js-clear-filters" style="width: 100%; border-radius: 8px;">
                        RESET ALL FILTERS
                    </button>
                </div>
            </aside>

            <!-- Listing Main Content -->
            <div class="st-listing-content">
                <!-- Mobile Trigger -->
                <button type="button" class="st-mobile-filter-trigger js-mobile-filter-trigger" onclick="document.querySelector('.st-smart-sidebar').scrollIntoView({behavior:'smooth'})">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    <span>FILTER EXPERIENCES</span>
                </button>

                <!-- Smart Modern Toolbar -->
                <div class="st-smart-toolbar">
                    <div class="st-smart-toolbar__left">
                        <span class="st-results-counter">
                            Found <strong class="js-results-count"><?php echo intval($exp_query->found_posts); ?></strong> experiences
                        </span>

                        <div class="st-view-toggle-smart">
                            <button type="button" class="st-view-toggle-btn js-view-btn is-active" data-view="grid" title="Grid View">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <span>Grid</span>
                            </button>
                            <button type="button" class="st-view-toggle-btn js-view-btn" data-view="list" title="List View">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><line x1="8" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"></line><line x1="8" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"></line><line x1="8" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"></line><circle cx="4" cy="6" r="2"></circle><circle cx="4" cy="12" r="2"></circle><circle cx="4" cy="18" r="2"></circle></svg>
                                <span>List</span>
                            </button>
                        </div>
                    </div>

                    <div class="st-smart-toolbar__right">
                        <div class="st-sort-wrapper">
                            <label for="filter_sort">Sort by:</label>
                            <select name="filter_sort" id="filter_sort" class="st-smart-select">
                                <option value="recommended">Featured / Recommended</option>
                                <option value="title_asc">A &ndash; Z</option>
                                <option value="near_me">📍 Near Me (Okanagan GPS)</option>
                            </select>
                        </div>

                        <button type="button" class="st-btn-map-toggle js-toggle-map">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                            <span>SHOW MAP</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Interactive Map -->
                <div class="st-map-wrapper js-map-wrapper">
                    <div id="st-experience-map"></div>
                </div>

                <!-- Experiences Grid -->
                <div class="st-experience-grid js-experience-grid">
                    <?php
                    if ($exp_query->have_posts()):
                        while ($exp_query->have_posts()): $exp_query->the_post();
                            echo smarttours_render_experience_card(get_the_ID(), 'grid');
                        endwhile;
                        wp_reset_postdata();
                    else:
                        ?>
                        <div class="st-no-results">
                            <h3>No experiences found matching this criteria</h3>
                            <p>Try resetting your filters or clearing keyword search.</p>
                            <button type="button" class="st-btn st-btn--primary js-clear-filters" style="margin-top:15px;">Reset Filters</button>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>

                <!-- Load More Button -->
                <div class="st-pagination-wrapper">
                    <button type="button" class="st-load-more-btn js-load-more" style="<?php echo ($exp_query->max_num_pages > 1) ? '' : 'display:none;'; ?>">
                        LOAD MORE EXPERIENCES
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
