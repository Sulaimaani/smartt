<?php
/**
 * Theme Header — Canada Smart Tours (smarttours.ca)
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
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="st-header" role="banner">
    <!-- Top Utility & Brand Bar -->
    <div class="st-header__top">
        <div class="st-container st-header__top-inner">
            <div class="st-header__left">
                <button type="button" class="st-hamburger js-hamburger" aria-label="<?php esc_attr_e('Toggle navigation menu', 'smarttours'); ?>">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="st-brand-logo" rel="home">
                    <span class="st-brand-badge">🇨🇦 SMART TOURS</span>
                    <span class="st-brand-title">CANADA SMART TOURS</span>
                    <span class="st-brand-tagline">smarttours.ca &bull; Okanagan Valley, BC</span>
                </a>
            </div>

            <div class="st-header__tools">
                <!-- Weather in the Okanagan -->
                <div class="st-weather-pill" title="Current weather in the Okanagan Valley">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="4" stroke="currentColor" stroke-width="2"></line>
                        <line x1="12" y1="20" x2="12" y2="23" stroke="currentColor" stroke-width="2"></line>
                        <line x1="4.22" y1="4.22" x2="6.34" y2="6.34" stroke="currentColor" stroke-width="2"></line>
                        <line x1="17.66" y1="17.66" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2"></line>
                        <line x1="1" y1="12" x2="4" y2="12" stroke="currentColor" stroke-width="2"></line>
                        <line x1="20" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"></line>
                    </svg>
                    <span>Okanagan 1°C</span>
                </div>

                <!-- Currency -->
                <span class="st-currency-badge">CAD ($)</span>

                <!-- My Trip Saved Badge -->
                <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-trip-btn" aria-label="<?php esc_attr_e('My Saved Trips', 'smarttours'); ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" class="st-heart-svg">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span>My Trip</span>
                    <span class="st-trip-count">0</span>
                </a>

                <!-- Search trigger -->
                <button type="button" class="st-search-btn js-toggle-header-search" aria-label="<?php esc_attr_e('Search Okanagan experiences', 'smarttours'); ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Collapsible Quick Search Dropdown -->
    <div class="st-header-search-bar js-header-search-bar">
        <div class="st-container">
            <form action="<?php echo esc_url(home_url('/experiences/')); ?>" method="get" class="st-quick-search-form">
                <input type="text" name="keyword" placeholder="Search Okanagan wine tours, boat rentals, trails, attractions..." aria-label="Search" />
                <button type="submit" class="st-btn st-btn--accent">SEARCH</button>
            </form>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="st-header__nav-bar" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'smarttours'); ?>">
        <div class="st-container">
            <ul class="st-nav-menu">
                <!-- EXPERIENCES with Desktop Mega Menu -->
                <li class="st-nav-item st-nav-item--mega">
                    <a href="<?php echo esc_url(home_url('/experiences/')); ?>" class="st-nav-link">
                        EXPERIENCES
                        <svg viewBox="0 0 24 24" width="14" height="14" style="margin-left: 6px;"><path fill="currentColor" d="M7 10l5 5 5-5z"/></svg>
                    </a>

                    <!-- Desktop Mega Menu: All 12 Main Categories + Children in columns -->
                    <div class="st-mega-menu">
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
                                        $sub_categories = get_terms(array(
                                            'taxonomy'   => 'experience_category',
                                            'parent'     => $main_cat->term_id,
                                            'hide_empty' => false,
                                        ));
                                        ?>
                                        <div class="st-mega-col">
                                            <a href="<?php echo esc_url(get_term_link($main_cat)); ?>" class="st-mega-col__title">
                                                <?php echo esc_html($main_cat->name); ?>
                                            </a>
                                            <ul class="st-mega-list">
                                                <?php
                                                if (!empty($sub_categories) && !is_wp_error($sub_categories)):
                                                    foreach ($sub_categories as $sub):
                                                        ?>
                                                        <li>
                                                            <a href="<?php echo esc_url(get_term_link($sub)); ?>">
                                                                <?php echo esc_html($sub->name); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                    endforeach;
                                                endif;

                                                if ($main_cat->slug === 'food-drink'):
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>">
                                                            Wineries &rarr;
                                                        </a>
                                                    </li>
                                                    <?php
                                                endif;
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>" class="st-nav-link">WINE TOURING</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/')); ?>" class="st-nav-link">ACTIVE & OUTDOORS</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/experiences/food-drink/')); ?>" class="st-nav-link">FOOD & DRINK</a>
                </li>
                <li class="st-nav-item">
                    <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-nav-link st-nav-link--highlight">
                        <span>MY TRIP PLANNER</span>
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
        <a href="<?php echo esc_url(home_url('/')); ?>" class="st-brand-logo" style="text-align: left;">
            <span class="st-brand-badge" style="font-size: 0.7rem;">🇨🇦 SMART TOURS</span>
            <span class="st-brand-title" style="font-size: 1.25rem;">CANADA SMART TOURS</span>
            <span class="st-brand-tagline">smarttours.ca</span>
        </a>
        <button type="button" class="st-close-drawer js-close-drawer" aria-label="<?php esc_attr_e('Close menu', 'smarttours'); ?>">&times;</button>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="<?php echo esc_url(home_url('/my-trip/')); ?>" class="st-btn st-btn--accent" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <span>MY TRIP PLANNER (<span class="st-trip-count">0</span>)</span>
        </a>
    </div>

    <ul class="st-accordion-menu">
        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/experiences/')); ?>"><strong>ALL EXPERIENCES</strong></a>
                <button type="button" class="st-accordion-toggle js-accordion-toggle" aria-expanded="false">+</button>
            </div>
            <div class="st-accordion-panel">
                <?php
                if (!empty($main_categories) && !is_wp_error($main_categories)):
                    foreach ($main_categories as $m_cat):
                        $subs = get_terms(array(
                            'taxonomy'   => 'experience_category',
                            'parent'     => $m_cat->term_id,
                            'hide_empty' => false,
                        ));
                        ?>
                        <div style="margin-bottom: 12px;">
                            <a href="<?php echo esc_url(get_term_link($m_cat)); ?>" style="font-weight: bold; font-size: 0.95rem; color: #000; display: block;">
                                <?php echo esc_html($m_cat->name); ?>
                            </a>
                            <?php if (!empty($subs) && !is_wp_error($subs)): ?>
                                <ul style="list-style: none; padding-left: 12px; margin-top: 4px;">
                                    <?php foreach ($subs as $s): ?>
                                        <li class="st-accordion-subitem">
                                            <a href="<?php echo esc_url(get_term_link($s)); ?>"><?php echo esc_html($s->name); ?></a>
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
        </li>

        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>">WINE TOURING</a>
            </div>
        </li>
        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/experiences/active-outdoors/')); ?>">ACTIVE & OUTDOORS</a>
            </div>
        </li>
        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/experiences/food-drink/')); ?>">FOOD & DRINK</a>
            </div>
        </li>
        <li class="st-accordion-item">
            <div class="st-accordion-header">
                <a href="<?php echo esc_url(home_url('/my-trip/')); ?>">MY SAVED TRIP</a>
            </div>
        </li>
    </ul>
</aside>

<!-- Floating Toast Container for "Added to Trip" -->
<div id="st-toast-container" class="st-toast-container" aria-live="polite"></div>
