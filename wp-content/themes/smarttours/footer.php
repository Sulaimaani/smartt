<?php
/**
 * Theme Footer
 *
 * @package SmartTours
 */
if (!defined('ABSPATH')) exit;
?>

<footer class="st-footer" role="contentinfo">
    <div class="st-container">
        <div class="st-footer__grid">
            <!-- Brand / About Column -->
            <div class="st-footer__brand">
                <h3>CANADA SMART TOURS</h3>
                <p><?php _e('Your premier Okanagan Valley tour and experience reseller. Connecting visitors with authentic wineries, lake charters, outdoor adventures, and unforgettable Okanagan memories.', 'smarttours'); ?></p>
                <div class="st-social-links" style="margin-top: 20px;">
                    <a href="https://facebook.com" class="st-social-circle" aria-label="Facebook" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="#000"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://instagram.com" class="st-social-circle" aria-label="Instagram" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#000" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="https://twitter.com" class="st-social-circle" aria-label="X Twitter" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="#000"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="st-footer-col">
                <h4>EXPLORE</h4>
                <ul class="st-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/experiences/wine-touring/')); ?>">Wine Touring</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/active-outdoors/')); ?>">Active & Outdoors</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/food-drink/')); ?>">Food & Drink</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/guided-tours/')); ?>">Guided Tours</a></li>
                    <li><a href="<?php echo esc_url(home_url('/experiences/family-friendly/')); ?>">Family Friendly</a></li>
                </ul>
            </div>

            <!-- Company & Partnerships -->
            <div class="st-footer-col">
                <h4>COMPANY</h4>
                <ul class="st-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About Smart Tours</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact & Visitor Support</a></li>
                    <li><a href="<?php echo esc_url(home_url('/partner/')); ?>">Partner With Us (Tour Operators)</a></li>
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="st-footer-col">
                <h4>OKANAGAN INSIDER</h4>
                <p style="color: #999; font-size: 0.9rem; margin-bottom: 12px;">Get exclusive wine tour deals, secret trail maps, and seasonal Okanagan highlights delivered to your inbox.</p>
                <form class="st-newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to Canada Smart Tours!'); this.reset();">
                    <input type="email" placeholder="Your email address" required aria-label="Email address" />
                    <button type="submit">JOIN</button>
                </form>
            </div>
        </div>

        <div class="st-footer__bottom">
            <div>
                &copy; <?php echo date('Y'); ?> Canada Smart Tours (smarttours.ca). All rights reserved. Okanagan Valley, British Columbia, Canada.
            </div>
            <div>
                Built for travellers visiting Kelowna, West Kelowna, Penticton, Lake Country & Vernon.
            </div>
        </div>
    </div>
</footer>

<!-- Global Quickview Modal Backdrop & Container -->
<div class="st-modal-backdrop js-quickview-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Experience Quickview', 'smarttours'); ?>">
    <div class="st-modal-window">
        <button type="button" class="st-modal-close js-modal-close" aria-label="<?php esc_attr_e('Close modal', 'smarttours'); ?>">&times;</button>
        <div class="js-modal-body"></div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
