<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Alhadiya_Organic_Mehendi_Course_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

    <?php
    // Customizer SEO & Analytics
    $site_description = get_theme_mod('site_description', 'অর্গানিক হাতের মেহেদি বানানোর কোর্স - সহজ উপায়ে শিখুন প্রফেশনাল মেহেদি তৈরির কৌশল');
    $og_image_id = get_theme_mod('og_image');
    $og_image_url = $og_image_id ? wp_get_attachment_url($og_image_id) : '';
    $favicon_id = get_theme_mod('favicon');
    $favicon_url = $favicon_id ? wp_get_attachment_url($favicon_id) : '';
    $apple_touch_icon_id = get_theme_mod('apple_touch_icon');
    $apple_touch_icon_url = $apple_touch_icon_id ? wp_get_attachment_url($apple_touch_icon_id) : '';
    $google_analytics_id = get_theme_mod('google_analytics_id', '');
    $facebook_pixel_id = get_theme_mod('facebook_pixel_id', '');
    $google_tag_manager_id = get_theme_mod('google_tag_manager_id', '');
    ?>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($site_description); ?>">
    <meta property="og:title" content="<?php wp_title(''); ?>">
    <meta property="og:description" content="<?php echo esc_attr($site_description); ?>">
    <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
    <meta property="og:type" content="website">
    <?php if ($og_image_url): ?>
        <meta property="og:image" content="<?php echo esc_url($og_image_url); ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title(''); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($site_description); ?>">
    <?php if ($og_image_url): ?>
        <meta name="twitter:image" content="<?php echo esc_url($og_image_url); ?>">
    <?php endif; ?>

    <!-- Favicon and Apple Touch Icon -->
    <?php if ($favicon_url): ?>
        <link rel="icon" href="<?php echo esc_url($favicon_url); ?>" sizes="32x32" />
    <?php endif; ?>
    <?php if ($apple_touch_icon_url): ?>
        <link rel="apple-touch-icon" href="<?php echo esc_url($apple_touch_icon_url); ?>" />
    <?php endif; ?>
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/favicon.ico" sizes="any" />
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/icon.svg" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/apple-touch-icon.png" />
    <link rel="manifest" href="<?php echo esc_url(get_template_directory_uri()); ?>/manifest.webmanifest" />

    <!-- Google Analytics -->
    <?php if ($google_analytics_id): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($google_analytics_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_attr($google_analytics_id); ?>');
        </script>
    <?php endif; ?>

    <!-- Facebook Pixel -->
    <?php if ($facebook_pixel_id): ?>
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?php echo esc_attr($facebook_pixel_id); ?>');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=<?php echo esc_attr($facebook_pixel_id); ?>&ev=PageView&noscript=1"
        /></noscript>
    <?php endif; ?>

    <!-- Google Tag Manager (noscript) -->
    <?php if ($google_tag_manager_id): ?>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo esc_attr($google_tag_manager_id); ?>');</script>
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>
<?php if ($google_tag_manager_id): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($google_tag_manager_id); ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'alhadiya' ); ?></a>

<?php 
$header_announcement = get_theme_mod('header_announcement', '');
if (!empty($header_announcement)) : ?>
    <div class="header-announcement" style="background-color: #dd0055; color: white; text-align: center; padding: 10px; font-size: 16px;">
        <?php echo esc_html($header_announcement); ?>
    </div>
<?php endif; ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <nav id="site-navigation" class="main-navigation">
            <div class="container">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container_class' => 'main-menu-container',
                    'fallback_cb'     => false,
                ) );
                ?>
            </div>
        </nav><!-- #site-navigation -->
    </header><!-- #masthead -->

    <div id="content" class="site-content">
