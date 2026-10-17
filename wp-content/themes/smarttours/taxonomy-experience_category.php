<?php
/**
 * Taxonomy Archive: Experience Category (Main, Sub, and Sub-sub Categories)
 * Clean, modern Viator-inspired layout with sub-category chips, white filter column, and map.
 * Canada Smart Tours — smarttours.ca
 *
 * @package SmartTours
 */

get_header();

$current_term = get_queried_object();
$desc = !empty($current_term->description) ? $current_term->description : sprintf(__('Discover top-rated tours, tastings, and adventures in %s, Okanagan Valley.', 'smarttours'), $current_term->name);

// Child terms for sub-category chips
$child_terms = get_terms(array(
    'taxonomy'   => 'experience_category',
    'parent'     => $current_term->term_id,
    'hide_empty' => false,
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
    'posts_per_page' => 12,
    'paged'          => $initial_paged,
    'orderby'        => 'menu_order date',
    'order'          => 'DESC',
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

<main id="primary" class="site-main st-listing-page" role="main">
    <div class="st-container">
        <!-- Breadcrumb Row -->
        <div class="st-listing-breadcrumbs">
            <?php smarttours_render_breadcrumbs(); ?>
        </div>

        <!-- Header: H1 + One-line intro -->
        <header class="st-listing-header">
            <h1 class="st-listing-title"><?php echo esc_html($current_term->name); ?></h1>
            <p class="st-listing-intro"><?php echo esc_html($desc); ?></p>

            <!-- Sub-Category Chips (Horizontal Scroll on Mobile) -->
            <?php if (!empty($child_terms) && !is_wp_error($child_terms)): ?>
                <div class="st-subcat-chips-wrap">
                    <div class="st-subcat-chips">
                        <a href="<?php echo esc_url(get_term_link($current_term)); ?>" class="st-subcat-chip is-active">
                            <span>All <?php echo esc_html($current_term->name); ?></span>
                        </a>
                        <?php foreach ($child_terms as $child): ?>
                            <a href="<?php echo esc_url(get_term_link($child)); ?>" class="st-subcat-chip">
                                <span><?php echo esc_html($child->name); ?></span>
                                <?php if ($child->count > 0): ?>
                                    <span class="st-subcat-chip__count"><?php echo intval($child->count); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </header>

        <!-- Main Listing Layout (Sidebar + Content Side-by-Side) -->
        <div class="st-listing-layout js-filter-wrapper">
            <!-- Filter Sidebar -->
            <aside class="st-filter-sidebar js-filter-sidebar" role="complementary" aria-label="<?php esc_attr_e('Filter experiences', 'smarttours'); ?>">
                <input type="hidden" name="filter_term_id" value="<?php echo esc_attr($current_term->term_id); ?>" />

                <div class="st-filter-sidebar__header">
                    <div class="st-filter-sidebar__title">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                        <span>FILTERS</span>
                    </div>
                    <button type="button" class="st-filter-clear-btn js-clear-filters">Reset</button>
                    <button type="button" class="st-filter-sidebar__close js-close-filters-sheet" aria-label="Close filters">&times;</button>
                </div>

                <div class="st-filter-sidebar__body">
                    <!-- Keyword Search (Tourism Kelowna Style) -->
                    <div class="st-filter-group">
                        <label class="st-filter-group__label" for="filter_keyword"><?php _e('KEYWORD', 'smarttours'); ?></label>
                        <form class="st-filter-search-box js-search-form">
                            <input type="text" name="filter_keyword" id="filter_keyword" placeholder="<?php esc_attr_e('Search', 'smarttours'); ?>" autocomplete="off" />
                            <button type="submit" class="st-filter-search-btn" aria-label="<?php esc_attr_e('Filter search', 'smarttours'); ?>">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Real Amenities Checkboxes with Counts -->
                    <div class="st-filter-group">
                        <span class="st-filter-group__label"><?php _e('AMENITIES', 'smarttours'); ?></span>
                        <ul class="st-checkbox-list">
                            <?php
                            if (!empty($all_amenities) && !is_wp_error($all_amenities)):
                                $cnt = 0;
                                foreach ($all_amenities as $am):
                                    if (is_numeric($am->name)) continue;
                                    $cnt++;
                                    $is_extra = ($cnt > 5);
                                    ?>
                                    <li class="st-checkbox-item <?php echo $is_extra ? 'st-amenity-extra' : ''; ?>" <?php echo $is_extra ? 'style="display:none;"' : ''; ?>>
                                        <label class="st-checkbox-label">
                                            <input type="checkbox" name="amenity[]" value="<?php echo esc_attr($am->term_id); ?>" class="js-filter-input" />
                                            <span class="st-checkbox-custom"></span>
                                            <span class="st-checkbox-text"><?php echo esc_html($am->name); ?></span>
                                            <span class="st-checkbox-badge">(<?php echo intval($am->count); ?>)</span>
                                        </label>
                                    </li>
                                    <?php
                                endforeach;

                                if ($cnt > 5):
                                    ?>
                                    <li>
                                        <button type="button" class="st-show-more-btn js-show-more-amenities">
                                            Show <?php echo ($cnt - 5); ?> more &#9662;
                                        </button>
                                    </li>
                                    <?php
                                endif;
                            endif;
                            ?>
                        </ul>
                    </div>

                    <!-- Free Cancellation Toggle -->
                    <div class="st-filter-group">
                        <label class="st-toggle-label">
                            <input type="checkbox" name="free_cancellation" id="filter_free_cancel" value="1" class="st-toggle-input js-filter-input" />
                            <span class="st-toggle-switch"></span>
                            <span class="st-toggle-text">
                                <strong>Free cancellation</strong>
                                <small>Full refund up to 24h before</small>
                            </span>
                        </label>
                    </div>

                    <!-- Price Range Slider -->
                    <div class="st-filter-group">
                        <div class="st-filter-group__head">
                            <span class="st-filter-group__label"><?php _e('Max Price', 'smarttours'); ?></span>
                            <span class="st-price-slider-val js-price-val">All Prices</span>
                        </div>
                        <input type="range" name="price_max" id="filter_price_max" min="20" max="400" step="10" value="400" class="st-slider js-price-slider" />
                        <div class="st-slider-ticks">
                            <span>$20 CAD</span>
                            <span>$400+ CAD</span>
                        </div>
                    </div>

                    <!-- Clear Filters Action Button at Bottom -->
                    <div class="st-filter-action-block st-filter-action-block--bottom">
                        <button type="button" class="st-btn-clear-filters js-clear-filters">
                            CLEAR FILTERS
                        </button>
                    </div>
                </div>

                <div class="st-filter-sidebar__footer">
                    <button type="button" class="st-btn st-btn--primary js-apply-mobile-filters" style="width: 100%;">
                        Apply Filters
                    </button>
                </div>
            </aside>

            <!-- Listing Main Content (Directly Next to Filter Sidebar) -->
            <div class="st-listing-content">
                <!-- Top Toolbar: VIEW, SORT Tabs, SHOW MAP, Counter -->
                <div class="st-toolbar">
                    <div class="st-toolbar__view">
                        <span class="st-toolbar__label">VIEW:</span>
                        <div class="st-view-toggle">
                            <button type="button" class="st-view-btn js-view-btn is-active" data-view="grid" title="Grid View" aria-label="Grid view">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                                <span>GRID</span>
                            </button>
                            <button type="button" class="st-view-btn js-view-btn" data-view="list" title="List View" aria-label="List view">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><line x1="8" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><line x1="8" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><line x1="8" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><circle cx="4" cy="6" r="2"></circle><circle cx="4" cy="12" r="2"></circle><circle cx="4" cy="18" r="2"></circle></svg>
                                <span>LIST</span>
                            </button>
                        </div>
                    </div>

                    <div class="st-toolbar__sort">
                        <span class="st-toolbar__label">SORT:</span>
                        <div class="st-sort-tabs">
                            <button type="button" class="st-sort-tab js-sort-tab is-active" data-sort="recommended">RECOMMENDED</button>
                            <button type="button" class="st-sort-tab js-sort-tab" data-sort="near_me">NEAR ME</button>
                            <button type="button" class="st-sort-tab js-sort-tab" data-sort="price_asc">PRICE: LOW &ndash; HIGH</button>
                            <button type="button" class="st-sort-tab js-sort-tab" data-sort="rating_desc">RATING</button>
                        </div>
                        <select name="filter_sort" id="filter_sort" class="st-select js-filter-sort" style="display:none;">
                            <option value="recommended" selected>Recommended</option>
                            <option value="near_me">Near Me</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="rating_desc">Highest Rated</option>
                            <option value="duration_asc">Duration</option>
                        </select>
                    </div>

                    <div class="st-toolbar__right-actions">
                        <button type="button" class="st-btn-map-toggle js-toggle-map" aria-label="Toggle map view">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>
                            <span>SHOW MAP</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Interactive Map Drawer -->
                <div class="st-map-drawer js-map-wrapper">
                    <div id="st-experience-map" style="height: 380px; width: 100%;"></div>
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
                            <div class="st-no-results__icon">🔍</div>
                            <h3>No experiences found in this category</h3>
                            <p>Try clearing your filters to see all Okanagan experiences.</p>
                            <button type="button" class="st-btn st-btn--primary js-clear-filters" style="margin-top: 15px;">Reset Filters</button>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>

                <!-- Pagination & Results Count Bar (Tourism Kelowna style) -->
                <div class="st-pagination-wrap">
                    <div class="st-pagination-bar">
                        <button type="button" class="st-pagination-arrow js-page-prev" disabled aria-label="Previous page">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <span class="st-pagination-info">
                            <span class="js-page-range">1 &ndash; <?php echo min(12, intval($exp_query->found_posts)); ?></span> of <strong class="js-results-count"><?php echo intval($exp_query->found_posts); ?></strong>
                        </span>
                        <button type="button" class="st-pagination-arrow js-page-next" <?php echo ($exp_query->max_num_pages <= 1) ? 'disabled' : ''; ?> aria-label="Next page">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>

                    <button type="button" class="st-btn-load-more js-load-more" style="<?php echo ($exp_query->max_num_pages > 1) ? '' : 'display:none;'; ?>">
                        <span>Load More Experiences</span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Backdrop for Mobile Bottom Sheet (outside flex layout) -->
    <div class="st-filter-backdrop js-close-filters-sheet"></div>

    <!-- Sticky Mobile Filter Button -->
    <div class="st-mobile-sticky-filter-bar">
        <button type="button" class="st-mobile-filter-btn js-open-filters-sheet">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
            <span>Filters</span>
        </button>
    </div>
</main>

<?php
get_footer();
