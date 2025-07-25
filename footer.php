<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Alhadiya_Organic_Mehendi_Course_Theme
 */

?>
    </div><!-- #content -->

    <?php 
    // Check if footer is enabled via Customizer
    if ( get_theme_mod('enable_footer', true) ) : ?>
    <footer id="colophon" class="site-footer">
        <div class="container footer-content">
            <div class="row">
                <div class="col-lg-4 col-md-6 footer-section">
                    <h3 class="footer-title"><?php bloginfo('name'); ?></h3>
                    <p class="footer-description"><?php echo esc_html(get_theme_mod('footer_description', 'অর্গানিক হাতের মেহেদি বানানোর সম্পূর্ণ কোর্স। ঘরে বসে শিখুন প্রফেশনাল মেহেদি তৈরির সব কৌশল।')); ?></p>
                    <div class="social-links">
                        <?php if (get_theme_mod('facebook_url')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('facebook_url')); ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('youtube_url')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('youtube_url')); ?>" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('instagram_url')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('instagram_url')); ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 footer-section">
                    <h3 class="footer-subtitle">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#course-section-1">Our Course</a></li>
                        <li><a href="#review-section">Reviews</a></li>
                        <li><a href="#faq-section">FAQs</a></li>
                        <li><a href="#order">Order Now</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-section">
                    <h3 class="footer-subtitle">Contact Us</h3>
                    <div class="contact-info">
                        <?php if (get_theme_mod('business_address')): ?>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo esc_html(get_theme_mod('business_address')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (get_theme_mod('phone_number')): ?>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:<?php echo esc_attr(get_theme_mod('phone_number')); ?>"><?php echo esc_html(get_theme_mod('phone_number')); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (get_theme_mod('email_address')): ?>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?php echo esc_attr(get_theme_mod('email_address')); ?>"><?php echo esc_html(get_theme_mod('email_address')); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (get_theme_mod('business_hours')): ?>
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo esc_html(get_theme_mod('business_hours')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 footer-section">
                    <h3 class="footer-subtitle">Legal</h3>
                    <ul class="footer-links">
                        <?php if (get_theme_mod('privacy_policy_url')): ?>
                            <li><a href="<?php echo esc_url(get_theme_mod('privacy_policy_url')); ?>">Privacy Policy</a></li>
                        <?php endif; ?>
                        <?php if (get_theme_mod('terms_conditions_url')): ?>
                            <li><a href="<?php echo esc_url(get_theme_mod('terms_conditions_url')); ?>">Terms & Conditions</a></li>
                        <?php endif; ?>
                        <?php if (get_theme_mod('refund_policy_url')): ?>
                            <li><a href="<?php echo esc_url(get_theme_mod('refund_policy_url')); ?>">Refund Policy</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="trust-badges">
                        <div class="trust-badge"><i class="fas fa-shield-alt"></i> Secure Payments</div>
                        <div class="trust-badge"><i class="fas fa-check-circle"></i> Quality Guaranteed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 copyright">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                    </div>
                    <div class="col-md-6">
                        <ul class="footer-links-bottom">
                            <?php if (get_theme_mod('privacy_policy_url')): ?>
                                <li><a href="<?php echo esc_url(get_theme_mod('privacy_policy_url')); ?>">Privacy Policy</a></li>
                            <?php endif; ?>
                            <?php if (get_theme_mod('terms_conditions_url')): ?>
                                <li><a href="<?php echo esc_url(get_theme_mod('terms_conditions_url')); ?>">Terms & Conditions</a></li>
                            <?php endif; ?>
                            <?php if (get_theme_mod('refund_policy_url')): ?>
                                <li><a href="<?php echo esc_url(get_theme_mod('refund_policy_url')); ?>">Refund Policy</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
    <?php endif; // End if enable_footer ?>
</div><!-- #page -->

<?php wp_footer(); ?>

<!-- Alhadiya Device Tracking (Hidden - No Visual Impact) -->
<script type="text/javascript">
// Initialize device tracking on page load
jQuery(document).ready(function($) {
    // Ensure tracking starts immediately but silently
    if (typeof deviceTracker !== 'undefined' && !deviceTracker.isTracking) {
        console.log('Initializing fallback device tracking...');
        // Fallback initialization if main script didn't load
        setTimeout(function() {
            if (typeof ajax_object !== 'undefined') {
                $.post(ajax_object.ajax_url, {
                    action: 'track_custom_event',
                    nonce: ajax_object.event_nonce,
                    session_id: 'fallback_' + Date.now(),
                    event_type: 'page_view',
                    event_name: 'Page Load',
                    event_value: window.location.pathname
                });
            }
        }, 1000);
    }
});
</script>

</body>
</html>
