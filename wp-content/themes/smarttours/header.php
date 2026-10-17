<?php
/**
 * Theme Header — Canada Smart Tours (smarttours.ca)
 * Clean, modern Viator-inspired header with sticky layout, live suggestions, and mega menu.
 *
 * @package SmartTours
 */
if (!defined('ABSPATH')) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="st-header" role="banner">
    <!-- Row 1: Main Header Row -->
    <div class="st-header__main">
        <div class="st-container st-header__main-inner">
            <!-- Brand Logo Left -->
            <div class="st-header__brand">
                <button type="button" class="st-hamburger js-hamburger" aria-label="<?php esc_attr_e('Open navigation menu', 'smarttours'); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <a href="<?php echo esc_url(home_url('/')); ?>" class="st-logo" rel="home" aria-label="Canada Smart Tours">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/smarttoursdotus.jpg'); ?>" alt="Canada Smart Tours" class="st-logo__img" width="168" height="44" />
                </a>
            </div>

            <!-- Center: Large Rounded Search Bar with Live Suggestions -->
            <div class="st-header__search-wrap js-header-search-wrap">
                <form action="<?php echo esc_url(home_url('/experiences/')); ?>" method="get" class="st-search-bar js-header-search-form" role="search">
                    <div class="st-search-bar__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <input 
                        type="search" 
                        name="keyword" 
                        id="st-header-search-input" 
                        class="st-search-bar__input js-live-search-input" 
                        placeholder="<?php esc_attr_e('Search tours in Kelowna & Okanagan Valley...', 'smarttours'); ?>" 
                        autocomplete="off" 
                        aria-label="<?php esc_attr_e('Search tours in Kelowna & Okanagan Valley', 'smarttours'); ?>" 
                    />
                    <button type="submit" class="st-search-bar__btn" aria-label="<?php esc_attr_e('Submit search', 'smarttours'); ?>">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Search</span>
                    </button>
                </form>

                <!-- Floating Live Search Suggestions Panel -->
                <div class="st-live-search-dropdown js-live-search-results" style="display: none;"></div>
            </div>

            <!-- Right: Icons & Actions -->
            <div class="st-header__actions">
                <!-- Mobile Search Toggle Button -->
                <button type="button" class="st-action-icon-btn st-action-icon-btn--mobile-search js-toggle-mobile-search" aria-label="<?php esc_attr_e('Open search', 'smarttours'); ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>

                <!-- Dark Mode Toggle Button -->
                <button type="button" class="st-theme-toggle js-toggle-theme" aria-label="<?php esc_attr_e('Toggle dark mode', 'smarttours'); ?>" title="Toggle dark mode">
                    <span class="st-theme-toggle__icon st-theme-toggle__sun">☀️</span>
                    <span class="st-theme-toggle__icon st-theme-toggle__moon">🌙</span>
                </button>

                <!-- Currency Badge (CAD) -->
                <div class="st-currency-pill" title="Currency: Canadian Dollars">
                    <span class="st-currency-flag">🇨🇦</span>
                    <span class="st-currency-code">CAD</span>
                </div>

                <!-- Help Button -->
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="st-header-link st-header-link--help" title="Need help or visitor recommendations?">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span>Help</span>
                </a>

                <!-- My Trip Heart Button with Count Badge -->
                <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-trip-pill js-header-trip-pill" aria-label="<?php esc_attr_e('My Saved Trips', 'smarttours'); ?>">
                    <svg viewBox="0 0 24 24" width="19" height="19" class="st-heart-svg">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span class="st-trip-pill__label">My Trip</span>
                    <span class="st-trip-count">0</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Search Expandable Bar -->
    <div class="st-mobile-search-bar js-mobile-search-bar">
        <div class="st-container">
            <form action="<?php echo esc_url(home_url('/experiences/')); ?>" method="get" class="st-mobile-search-form">
                <input type="search" name="keyword" class="js-mobile-search-input" placeholder="Search tours in Kelowna &amp; Okanagan..." aria-label="Search" />
                <button type="submit" class="st-btn st-btn--primary">Search</button>
            </form>
            <div class="st-live-search-dropdown js-mobile-live-results" style="display:none;"></div>
        </div>
    </div>

    <!-- Row 2: Secondary Navigation Bar with Explore Mega Menu -->
    <nav class="st-header__nav-bar" role="navigation" aria-label="<?php esc_attr_e('Category Navigation', 'smarttours'); ?>">
        <div class="st-container">
            <ul class="st-nav-menu">
                <!-- EXPLORE Mega Menu Dropdown -->
                <li class="st-nav-item st-nav-item--has-mega">
                    <a href="<?php echo esc_url(home_url('/experiences/')); ?>" class="st-nav-link st-nav-link--explore">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Explore All</span>
                        <svg viewBox="0 0 24 24" width="14" height="14" class="st-chevron-down"><path fill="currentColor" d="M7 10l5 5 5-5z"/></svg>
                    </a>

                    <!-- Desktop Mega Menu: All 12 Main Categories + Children in columns -->
                    <div class="st-mega-dropdown">
                        <div class="st-container">
                            <div class="st-mega-grid">
                                <?php
                                $main_categories = get_terms(array(
                                    'taxonomy'   => 'experience_category',
                                    'parent'     => 0,
                                    'hide_empty' => false,
                                    'meta_key'   => 'st_display_order',
                                    'orderby'    => 'meta_value_num',
                                    'order'      => 'ASC',
                                ));

                                if (!empty($main_categories) && !is_wp_error($main_categories)):
                                    foreach ($main_categories as $main_cat):
                                        if (is_numeric($main_cat->name)) continue;
                                        $sub_categories = get_terms(array(
                                            'taxonomy'   => 'experience_category',
                                            'parent'     => $main_cat->term_id,
                                            'hide_empty' => false,
                                        ));
                                        ?>
                                        <div class="st-mega-col">
                                            <a href="<?php echo esc_url(get_term_link($main_cat)); ?>" class="st-mega-col__title">
                                                <span><?php echo esc_html($main_cat->name); ?></span>
                                                <svg viewBox="0 0 24 24" width="12" height="12"><path fill="currentColor" d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>
                                            </a>
                                            <?php if (!empty($sub_categories) && !is_wp_error($sub_categories)): ?>
                                                <ul class="st-mega-list">
                                                    <?php foreach ($sub_categories as $sub): ?>
                                                        <li>
                                                            <a href="<?php echo esc_url(get_term_link($sub)); ?>">
                                                                <?php echo esc_html($sub->name); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Popular Direct Links -->
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>" class="st-nav-link">Wine Touring</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/')); ?>" class="st-nav-link">Active &amp; Outdoors</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/food-drink/')); ?>" class="st-nav-link">Food &amp; Drink</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/family-friendly/')); ?>" class="st-nav-link">Family Friendly</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/attractions/')); ?>" class="st-nav-link">Attractions</a>
                </li>
                <li class="st-nav-item st-nav-item--push-right">
                    <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-nav-link st-nav-link--trip-highlight">
                        <span>Trip Planner</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Mobile Slide-In Navigation Drawer with Accordions -->
<div class="st-drawer-overlay js-drawer-overlay"></div>
<aside class="st-mobile-drawer js-mobile-drawer" aria-label="<?php esc_attr_e('Mobile Navigation', 'smarttours'); ?>">
    <div class="st-mobile-header">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="st-logo" style="text-align: left;">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/smarttoursdotus.jpg'); ?>" alt="Canada Smart Tours" class="st-logo__img" width="145" height="38" />
        </a>
        <button type="button" class="st-close-drawer js-close-drawer" aria-label="<?php esc_attr_e('Close menu', 'smarttours'); ?>">&times;</button>
    </div>

    <div class="st-mobile-drawer__trip-cta">
        <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-btn st-btn--primary" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <span>My Trip Planner (<span class="st-trip-count">0</span>)</span>
        </a>
    </div>

    <!-- Category Accordions in Mobile Drawer -->
    <div class="st-mobile-drawer__section-title"><?php _e('Explore Categories', 'smarttours'); ?></div>
    <ul class="st-accordion-menu">
        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/experiences/')); ?>"><strong>All Experiences</strong></a>
            </div>
        </li>
        <?php
        if (!empty($main_categories) && !is_wp_error($main_categories)):
            foreach ($main_categories as $m_cat):
                if (is_numeric($m_cat->name)) continue;
                $subs = get_terms(array(
                    'taxonomy'   => 'experience_category',
                    'parent'     => $m_cat->term_id,
                    'hide_empty' => false,
                ));
                $has_subs = !empty($subs) && !is_wp_error($subs);
                ?>
                <li class="st-accordion-item">
                    <div class="st-accordion-header">
                        <a href="<?php echo esc_url(get_term_link($m_cat)); ?>">
                            <?php echo esc_html($main_cat->name); ?>
                        </a>
                        <?php if ($has_subs): ?>
                            <button type="button" class="st-accordion-toggle js-accordion-toggle" aria-expanded="false" aria-label="Toggle subcategories">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                            </button>
                        <?php endif; ?>
                    </div>
                    <?php if ($has_subs): ?>
                        <div class="st-accordion-panel">
                            <ul>
                                <?php foreach ($subs as $s): ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_term_link($s)); ?>"><?php echo esc_html($s->name); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
                <?php
            endforeach;
        endif;
        ?>
    </ul>

    <div class="st-mobile-drawer__footer">
        <div class="st-mobile-drawer__footer-item">
            <span>Currency: <strong>CAD ($)</strong></span>
        </div>
        <div class="st-mobile-drawer__footer-item">
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Help &amp; Visitor Support</a>
        </div>
    </div>
</aside>

<!-- Floating Toast Container for "Added to Trip" -->
<div id="st-toast-container" class="st-toast-container" aria-live="polite"></div>
