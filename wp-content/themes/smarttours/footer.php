<?php
/**
 * Theme Footer — Canada Smart Tours (smarttours.ca)
 * Clean, modern four-column footer with newsletter and links.
 *
 * @package SmartTours
 */
if (!defined('ABSPATH')) exit;
?>

<footer class="st-footer" role="contentinfo">
    <div class="st-container">
        <div class="st-footer__grid">
            <!-- Brand / About Column -->
            <div class="st-footer__col st-footer__col--brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="st-footer__logo" aria-label="Canada Smart Tours">
                    <span class="st-footer__logo-mark">🇨🇦</span>
                    <span class="st-footer__logo-text">Canada Smart Tours</span>
                </a>
                <p class="st-footer__about-text">
                    <?php _e('Your premier Okanagan Valley tour and experience reseller. Connecting visitors with authentic wineries, lake boat charters, scenic rail trails, and unforgettable British Columbia memories.', 'smarttours'); ?>
                </p>
                <div class="st-footer__social-links">
                    <a href="https://facebook.com" class="st-social-btn" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://instagram.com" class="st-social-btn" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="https://twitter.com" class="st-social-btn" aria-label="X Twitter" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Explore Links Column -->
            <div class="st-footer__col">
                <h4 class="st-footer__heading"><?php _e('Explore', 'smarttours'); ?></h4>
                <ul class="st-footer__links">
                    <li><a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>">Wine Touring</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/active-outdoors/')); ?>">Active &amp; Outdoors</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/food-drink/')); ?>">Food &amp; Drink</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/guided-tours/')); ?>">Guided Tours</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/family-friendly/')); ?>">Family Friendly</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/attractions/')); ?>">Attractions</a></li>
                </ul>
            </div>

            <!-- Company Column -->
            <div class="st-footer__col">
                <h4 class="st-footer__heading"><?php _e('Company', 'smarttours'); ?></h4>
                <ul class="st-footer__links">
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About Smart Tours</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact &amp; Support</a></li>
                    <li><a href="<?php echo esc_url(home_url('/partner/')); ?>">Partner With Us</a></li>
                    <li><a href="<?php echo esc_url(home_url('/my-trip/')); ?>">My Trip Planner</a></li>
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="st-footer__col st-footer__col--newsletter">
                <h4 class="st-footer__heading"><?php _e('Okanagan Insider', 'smarttours'); ?></h4>
                <p class="st-footer__newsletter-desc">
                    <?php _e('Get curated wine tour deals, secret trail maps, and seasonal harvest highlights delivered to your inbox.', 'smarttours'); ?>
                </p>
                <form class="st-footer__newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to Canada Smart Tours!'); this.reset();">
                    <div class="st-newsletter-input-group">
                        <input type="email" placeholder="Your email address" required aria-label="Email address" class="st-newsletter-input" />
                        <button type="submit" class="st-btn-newsletter">Subscribe</button>
                    </div>
                </form>
                <span class="st-footer__secure-note">🔒 We respect your privacy. Unsubscribe at any time.</span>
            </div>
        </div>

        <div class="st-footer__bottom">
            <div class="st-footer__copyright">
                &copy; <?php echo date('Y'); ?> Canada Smart Tours (smarttours.ca). All rights reserved. Okanagan Valley, British Columbia, Canada.
            </div>
            <div class="st-footer__subtext">
                Connecting visitors across Kelowna, West Kelowna, Lake Country, Penticton, Naramata &amp; Vernon.
            </div>
        </div>
    </div>
</footer>

<!-- Quickview Modal (Tourism Kelowna / Viator Quick Preview) -->
<div id="st-quickview-modal" class="st-quickview-modal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="st-quickview-backdrop js-close-quickview"></div>
    <div class="st-quickview-dialog">
        <button type="button" class="st-quickview-close js-close-quickview" aria-label="Close preview">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="st-quickview-body js-quickview-body">
            <div class="st-quickview-loading">
                <div class="st-spinner"></div>
                <span>Loading preview...</span>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
