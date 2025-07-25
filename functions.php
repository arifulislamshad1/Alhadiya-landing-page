<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Error handling: Prevent fatal errors from breaking the site
function alhadiya_error_handler($errno, $errstr, $errfile, $errline) {
    // Only handle our theme's errors
    if (strpos($errfile, get_template_directory()) !== false) {
        error_log("Alhadiya Theme Error: [$errno] $errstr in $errfile on line $errline");
        // Return true to prevent PHP's built-in error handler from running
        return true;
    }
    // Return false to allow normal error handling for other files
    return false;
}

// Set custom error handler for theme
set_error_handler('alhadiya_error_handler', E_ERROR | E_WARNING | E_PARSE);

// Define DB version for schema updates
define('ALHADIYA_DEVICE_DB_VERSION', '2.0'); // Increment this version when schema changes

// Theme setup
function alhadiya_theme_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'alhadiya'),
    ));
}
add_action('after_setup_theme', 'alhadiya_theme_setup');

// Enqueue styles and scripts
function alhadiya_scripts() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css');
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_style('alhadiya-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    wp_enqueue_script('jquery');
    
    // Enqueue comprehensive device tracking script
    wp_enqueue_script('device-tracker', get_template_directory_uri() . '/device-tracker.js', array('jquery'), wp_get_theme()->get('Version'), true);
    
    // Localize script for AJAX
    wp_localize_script('jquery', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('alhadiya_nonce'),
        'wc_ajax_url' => class_exists('WC_AJAX') ? WC_AJAX::get_endpoint('%%endpoint%%') : '',
        'dhaka_delivery_charge' => get_theme_mod('dhaka_delivery_charge', 0),
        'outside_dhaka_delivery_charge' => get_theme_mod('outside_dhaka_delivery_charge', 0),
        'phone_number' => get_theme_mod('phone_number', '+8801737146996'),
        'device_info_nonce' => wp_create_nonce('alhadiya_device_info_nonce'), // New nonce for device info
        'event_nonce' => wp_create_nonce('alhadiya_event_nonce'), // New nonce for custom events
        'screen_size_nonce' => wp_create_nonce('alhadiya_screen_size_nonce'), // New nonce for screen size
        'track_nonce' => wp_create_nonce('alhadiya_track_nonce'), // Nonce for high-performance tracking
        'tracking_endpoint' => home_url('/?alhadiya_track=1') // High-performance tracking endpoint
    ));
}
add_action('wp_enqueue_scripts', 'alhadiya_scripts');

// Create custom post types for reviews and FAQs
function create_course_post_types() {
    // Reviews/Photos
    register_post_type('course_review', array(
        'labels' => array(
            'name' => __('Customer Reviews'),
            'singular_name' => __('Customer Review'),
            'add_new' => __('Add New Review'),
            'add_new_item' => __('Add New Customer Review'),
            'edit_item' => __('Edit Review'),
            'new_item' => __('New Review'),
            'view_item' => __('View Review'),
            'search_items' => __('Search Reviews'),
            'not_found' => __('No reviews found'),
            'not_found_in_trash' => __('No reviews found in trash')
        ),
        'public' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'menu_icon' => 'dashicons-camera',
        'menu_position' => 25
    ));
    
    // FAQs
    register_post_type('course_faq', array(
        'labels' => array(
            'name' => __('FAQs'),
            'singular_name' => __('FAQ'),
            'add_new' => __('Add New FAQ'),
            'add_new_item' => __('Add New FAQ'),
            'edit_item' => __('Edit FAQ'),
            'new_item' => __('New FAQ'),
            'view_item' => __('View FAQ'),
            'search_items' => __('Search FAQs'),
            'not_found' => __('No FAQs found'),
            'not_found_in_trash' => __('No FAQs found in trash')
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'page-attributes'),
        'menu_icon' => 'dashicons-editor-help',
        'menu_position' => 26
    ));
}
add_action('init', 'create_course_post_types');

// Insert default FAQs on theme activation if they don't exist
function alhadiya_insert_default_faqs() {
    $faqs = array(
        array(
            'title' => 'ক্লাস কীভাবে করানো হবে? এবং রেকর্ড থাকবে কি?',
            'content' => 'আমাদের ক্লাসগুলো অনলাইন প্ল্যাটফর্মে লাইভ করানো হবে। প্রতিটি ক্লাসের রেকর্ড থাকবে, যা আপনি পরবর্তীতে যেকোনো সময় দেখতে পারবেন।'
        ),
        array(
            'title' => 'কোর্স শেষে কোনো সমস্যা হলে কি আপনারা সাহায্য করবেন?',
            'content' => 'হ্যাঁ, কোর্স শেষেও আমাদের সাপোর্ট টিম আপনার যেকোনো সমস্যা সমাধানে সাহায্য করবে। আপনি আমাদের সাথে যোগাযোগ করতে পারবেন।'
        ),
        array(
            'title' => 'কোর্সটি করলে কি মেহেদীর রঙ গাঢ় হবে?',
            'content' => 'আমাদের কোর্সে মেহেদীর রঙ গাঢ় করার সকল সিক্রেট টিপস এবং সঠিক রেসিপি শেখানো হবে, যা অনুসরণ করলে আপনার মেহেদীর রঙ অবশ্যই গাঢ় হবে।'
        ),
        array(
            'title' => 'আমি কি কোর্সটি করে ব্যবসা করতে পারব?',
            'content' => 'অবশ্যই! এই কোর্সটি আপনাকে অর্গানিক মেহেদী তৈরি এবং বিক্রির সকল কৌশল শেখাবে, যা আপনাকে সফলভাবে ব্যবসা শুরু করতে সাহায্য করবে।'
        ),
        array(
            'title' => 'মেহেদী কোর্সটিতে কয়দিন ক্লাস হবে এবং ক্লাসের সময় কী?',
            'content' => 'মেহেদী কোর্সটিতে মোট ৭ দিন ক্লাস হবে। ক্লাসের সময়সূচী কোর্সের শুরুতে জানিয়ে দেওয়া হবে এবং এটি শিক্ষার্থীদের সুবিধার কথা মাথায় রেখে নির্ধারণ করা হবে।'
        ),
        array(
            'title' => 'কোর্স শেষে কি সার্টিফিকেট দেওয়া হবে?',
            'content' => 'হ্যাঁ, কোর্স সফলভাবে সম্পন্ন করার পর আপনাকে একটি সার্টিফিকেট প্রদান করা হবে।'
        )
    );

    foreach ($faqs as $faq_data) {
        $existing_faq = get_page_by_title($faq_data['title'], OBJECT, 'course_faq');
        if (null === $existing_faq) {
            wp_insert_post(array(
                'post_title'    => $faq_data['title'],
                'post_content'  => $faq_data['content'],
                'post_status'   => 'publish',
                'post_type'     => 'course_faq',
            ));
        }
    }
}
add_action('after_switch_theme', 'alhadiya_insert_default_faqs');

// Enhanced Device Tracking Table - ADDED NEW COLUMNS
function create_device_tracking_table() {
    global $wpdb;
    
    // Error handling: Check if database connection is available
    if (!$wpdb || $wpdb->last_error) {
        error_log('Alhadiya Theme: Database connection error - ' . $wpdb->last_error);
        return false;
    }
    
    $table_name = $wpdb->prefix . 'device_tracking';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        session_id varchar(255) NOT NULL,
        ip_address varchar(45) NOT NULL,
        user_agent text NOT NULL,
        device_type varchar(50),
        device_model varchar(100),
        browser varchar(50),
        browser_version varchar(50),
        os varchar(50),
        location varchar(255),
        isp varchar(100),
        referrer text,
        facebook_id varchar(100),
        visit_count int(11) DEFAULT 1,
        time_spent int(11) DEFAULT 0,
        pages_viewed int(11) DEFAULT 1,
        has_purchased tinyint(1) DEFAULT 0,
        customer_name varchar(255),
        customer_phone varchar(50),
        customer_address text,
        screen_size varchar(50),
        screen_resolution varchar(50),
        viewport_size varchar(50),
        language varchar(50),
        timezone varchar(100),
        connection_type varchar(50),
        connection_speed varchar(20),
        battery_level decimal(5,2),
        battery_charging tinyint(1),
        memory_info decimal(8,2),
        storage_info decimal(8,2),
        cpu_cores int(11),
        touchscreen_detected tinyint(1),
        device_orientation varchar(20),
        scroll_depth_max decimal(5,2) DEFAULT 0,
        click_count int(11) DEFAULT 0,
        keypress_count int(11) DEFAULT 0,
        mouse_movements int(11) DEFAULT 0,
        first_visit datetime DEFAULT CURRENT_TIMESTAMP,
        last_visit datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_id (session_id),
        KEY ip_address (ip_address),
        KEY browser (browser),
        KEY device_type (device_type),
        KEY last_visit (last_visit)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);
    
    // Log database creation result
    if ($wpdb->last_error) {
        error_log('Alhadiya Theme: Error creating device_tracking table - ' . $wpdb->last_error);
        return false;
    }
    
    return true;
}

// New table for specific device events
function create_device_events_table() {
    global $wpdb;
    
    // Error handling: Check if database connection is available
    if (!$wpdb || $wpdb->last_error) {
        error_log('Alhadiya Theme: Database connection error - ' . $wpdb->last_error);
        return false;
    }
    
    $table_name = $wpdb->prefix . 'device_events';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        session_id varchar(255) NOT NULL,
        event_type varchar(100) NOT NULL,
        event_name varchar(255) NOT NULL,
        event_value text,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY session_id (session_id),
        KEY event_type (event_type),
        KEY timestamp (timestamp)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);
    
    // Log database creation result
    if ($wpdb->last_error) {
        error_log('Alhadiya Theme: Error creating device_events table - ' . $wpdb->last_error);
        return false;
    }
    
    return true;
}

// Check and update DB schema on init
function alhadiya_check_db_version() {
    $installed_version = get_option('alhadiya_device_db_version');

    if ($installed_version != ALHADIYA_DEVICE_DB_VERSION) {
        create_device_tracking_table();
        create_device_events_table();
        update_option('alhadiya_device_db_version', ALHADIYA_DEVICE_DB_VERSION);
    }
}
add_action('init', 'alhadiya_check_db_version');

// Performance optimization: Database cleanup for large traffic
function alhadiya_device_tracking_cleanup() {
    global $wpdb;
    
    // Clean up old events (keep only last 90 days)
    $events_table = $wpdb->prefix . 'device_events';
    $wpdb->query($wpdb->prepare(
        "DELETE FROM $events_table WHERE timestamp < DATE_SUB(NOW(), INTERVAL 90 DAY)"
    ));
    
    // Clean up old tracking data (keep only last 180 days)
    $tracking_table = $wpdb->prefix . 'device_tracking';
    $wpdb->query($wpdb->prepare(
        "DELETE FROM $tracking_table WHERE last_visit < DATE_SUB(NOW(), INTERVAL 180 DAY)"
    ));
    
    // Optimize tables
    $wpdb->query("OPTIMIZE TABLE $events_table");
    $wpdb->query("OPTIMIZE TABLE $tracking_table");
}

// Schedule cleanup to run weekly
if (!wp_next_scheduled('alhadiya_device_cleanup')) {
    wp_schedule_event(time(), 'weekly', 'alhadiya_device_cleanup');
}
add_action('alhadiya_device_cleanup', 'alhadiya_device_tracking_cleanup');

// Performance optimization: Batch process events to reduce database load
function alhadiya_batch_process_events() {
    // This could be expanded for high-traffic sites to batch process events
    // For now, we use immediate processing with optimized queries
    do_action('alhadiya_process_batch_events');
}

// Security: Rate limiting for tracking requests
function alhadiya_rate_limit_tracking() {
    $ip = get_client_ip();
    $transient_key = 'alhadiya_tracking_limit_' . md5($ip);
    $requests = get_transient($transient_key);
    
    if ($requests === false) {
        set_transient($transient_key, 1, 60); // 1 minute window
        return true;
    } elseif ($requests < 30) { // Max 30 requests per minute
        set_transient($transient_key, $requests + 1, 60);
        return true;
    }
    
    return false; // Rate limit exceeded
}

// Security: Validate session ID format
function alhadiya_validate_session_id($session_id) {
    // Session ID should match the format: session_timestamp_randomstring
    return preg_match('/^session_[0-9]+_[a-z0-9]+$/', $session_id);
}

// Deactivation cleanup
function alhadiya_device_tracking_deactivate() {
    wp_clear_scheduled_hook('alhadiya_device_cleanup');
}
register_deactivation_hook(__FILE__, 'alhadiya_device_tracking_deactivate');


// Fixed IP Blocking System
function check_ip_blocking() {
    $user_ip = get_client_ip();
    $blocked_key = 'blocked_ip_' . md5($user_ip);
    $blocked_data = get_transient($blocked_key);
    
    if ($blocked_data && is_array($blocked_data)) {
        $block_time = $blocked_data['block_time'];
        $current_time = time();
        
        if ($current_time < $block_time) {
            $remaining_seconds = $block_time - $current_time;
            $remaining_minutes = ceil($remaining_seconds / 60);
            
            wp_send_json_error('আপনার IP ব্লক করা হয়েছে। অনুগ্রহ করে ' . $remaining_minutes . ' মিনিট পর আবার চেষ্টা করুন।');
            return false;
        } else {
            // Block expired, remove it
            delete_transient($blocked_key);
        }
    }
    return true;
}

function get_client_ip() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function block_ip_after_order($ip, $time = 5, $unit = 'minutes') {
    $seconds = 0;
    
    switch($unit) {
        case 'minutes':
            $seconds = $time * 60;
            break;
        case 'hours':
            $seconds = $time * 3600;
            break;
        case 'days':
            $seconds = $time * 86400;
            break;
    }
    
    $block_time = time() + $seconds;
    $blocked_data = array(
        'block_time' => $block_time,
        'blocked_at' => time(),
        'duration' => $seconds
    );
    
    $blocked_key = 'blocked_ip_' . md5($ip);
    set_transient($blocked_key, $blocked_data, $seconds);
    
    // Log the blocking for debugging
    error_log("IP Blocked: $ip for $time $unit (until " . date('Y-m-d H:i:s', $block_time) . ")");
}

// Enhanced device tracking - Initial visit/page load info
function track_enhanced_device_info() {
    global $wpdb;
    
    // Error handling: Check if database is available
    if (!$wpdb) {
        error_log('Alhadiya Theme: Database not available for tracking');
        return array('error' => 'Database not available');
    }
    
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip = get_client_ip();
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    
    // Generate or get session ID
    if (!isset($_COOKIE['device_session'])) {
        $session_id = uniqid('session_', true);
        $cookie_domain = defined('COOKIE_DOMAIN') && !empty(COOKIE_DOMAIN) ? COOKIE_DOMAIN : '';
        setcookie('device_session', $session_id, time() + (86400 * 30), '/', $cookie_domain, is_ssl(), true); // 30 days, secure, httponly
    } else {
        $session_id = $_COOKIE['device_session'];
    }
    
    // Parse user agent for device info
    $device_info = parse_user_agent($user_agent);
    
    // Get location and ISP info
    $location_data = get_location_and_isp($ip);
    
    // Disable Facebook ID tracking by setting it to empty string
    $facebook_id = ''; 
    
    $table_name = $wpdb->prefix . 'device_tracking';
    
    // Check if session exists
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE session_id = %s",
        $session_id
    ));
    
    // Initialize variables with default values or existing data (NULL-SAFE)
    $screen_size = ($existing && property_exists($existing, 'screen_size')) ? $existing->screen_size : '';
    $language = ($existing && property_exists($existing, 'language')) ? $existing->language : '';
    $timezone = ($existing && property_exists($existing, 'timezone')) ? $existing->timezone : '';
    $connection_type = ($existing && property_exists($existing, 'connection_type')) ? $existing->connection_type : '';
    $battery_level = ($existing && property_exists($existing, 'battery_level')) ? $existing->battery_level : null;
    $battery_charging = ($existing && property_exists($existing, 'battery_charging')) ? $existing->battery_charging : null;
    $memory_info = ($existing && property_exists($existing, 'memory_info')) ? $existing->memory_info : null;
    $cpu_cores = ($existing && property_exists($existing, 'cpu_cores')) ? $existing->cpu_cores : null;
    $touchscreen_detected = ($existing && property_exists($existing, 'touchscreen_detected')) ? $existing->touchscreen_detected : null;

    if ($existing) {
        // Update existing record
        $wpdb->update(
            $table_name,
            array(
                'visit_count' => $existing->visit_count + 1,
                'pages_viewed' => $existing->pages_viewed + 1,
                'last_visit' => current_time('mysql')
            ),
            array('session_id' => $session_id)
        );
    } else {
        // Insert new record with all enhanced fields
        $wpdb->insert(
            $table_name,
            array(
                'session_id' => $session_id,
                'ip_address' => $ip,
                'user_agent' => $user_agent,
                'device_type' => $device_info['device_type'],
                'device_model' => $device_info['device_model'],
                'browser' => $device_info['browser'],
                'browser_version' => '', // Will be updated by JavaScript
                'os' => $device_info['os'],
                'location' => $location_data['location'],
                'isp' => $location_data['isp'],
                'referrer' => $referrer,
                'facebook_id' => $facebook_id, // Will be empty string
                'screen_size' => $screen_size, // Initial empty, updated by AJAX
                'screen_resolution' => '', // Will be updated by JavaScript
                'viewport_size' => '', // Will be updated by JavaScript
                'language' => $language,
                'timezone' => $timezone,
                'connection_type' => $connection_type,
                'connection_speed' => '', // Will be updated by JavaScript
                'battery_level' => $battery_level,
                'battery_charging' => $battery_charging,
                'memory_info' => $memory_info,
                'storage_info' => null, // Will be updated by JavaScript
                'cpu_cores' => $cpu_cores,
                'touchscreen_detected' => $touchscreen_detected,
                'device_orientation' => '', // Will be updated by JavaScript
                'visit_count' => 1,
                'pages_viewed' => 1,
                'first_visit' => current_time('mysql'),
                'last_visit' => current_time('mysql')
            )
        );
    }
    
    return array(
        'session_id' => $session_id,
        'ip' => $ip,
        'user_agent' => $user_agent,
        'device_info' => $device_info,
        'location_data' => $location_data,
        'referrer' => $referrer,
        'facebook_id' => $facebook_id,
        'screen_size' => $screen_size,
        'timestamp' => current_time('mysql')
    );
}

function parse_user_agent($user_agent) {
    $device_type = 'Unknown';
    $device_model = 'Unknown';
    $browser = 'Unknown';
    $os = 'Unknown';
    
    // Detect mobile devices
    if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i', $user_agent)) {
        $device_type = 'Mobile';
        
        // Detect specific mobile devices
        if (preg_match('/iPhone/i', $user_agent)) {
            $device_model = 'iPhone';
            if (preg_match('/iPhone OS ([0-9_]+)/i', $user_agent, $matches)) {
                $os = 'iOS ' . str_replace('_', '.', $matches[1]);
            }
        } elseif (preg_match('/iPad/i', $user_agent)) {
            $device_model = 'iPad';
            $device_type = 'Tablet';
        } elseif (preg_match('/Android/i', $user_agent)) {
            $device_model = 'Android Device';
            if (preg_match('/Android ([0-9.]+)/i', $user_agent, $matches)) {
                $os = 'Android ' . $matches[1];
            }
        }
    } else {
        $device_type = 'Desktop';
    }
    
    // Detect browser and version
    if (preg_match('/(Chrome|Firefox|Safari|Edge|OPR)\/([0-9.]+)/i', $user_agent, $matches)) {
        $browser = $matches[1];
        $browser_version = $matches[2];
        if ($browser === 'OPR') {
            $browser = 'Opera';
        }
        $browser .= ' ' . $browser_version;
    } elseif (preg_match('/MSIE ([0-9.]+)/i', $user_agent, $matches)) {
        $browser = 'Internet Explorer ' . $matches[1];
    }
    
    // Detect OS for desktop if not already set by mobile detection
    if ($os === 'Unknown') {
        if (preg_match('/Windows NT ([0-9.]+)/i', $user_agent, $matches)) {
            $os_version = '';
            switch ($matches[1]) {
                case '10.0': $os_version = '10'; break;
                case '6.3': $os_version = '8.1'; break;
                case '6.2': $os_version = '8'; break;
                case '6.1': $os_version = '7'; break;
                case '6.0': $os_version = 'Vista'; break;
                case '5.1': $os_version = 'XP'; break;
                case '5.0': $os_version = '2000'; break;
            }
            $os = 'Windows ' . $os_version;
        } elseif (preg_match('/Mac OS X ([0-9_.]+)/i', $user_agent, $matches)) {
            $os = 'macOS ' . str_replace('_', '.', $matches[1]);
        } elseif (preg_match('/Linux/i', $user_agent)) {
            $os = 'Linux';
        }
    }
    
    return array(
        'device_type' => $device_type,
        'device_model' => $device_model,
        'browser' => $browser,
        'os' => $os
    );
}

function get_location_and_isp($ip) {
    $location = 'Unknown';
    $isp = 'Unknown';
    
    // Try multiple IP geolocation services
    $services = array(
        "http://ipapi.co/{$ip}/json/",
        "http://ip-api.com/json/{$ip}",
        "https://ipinfo.io/{$ip}/json"
    );
    
    foreach ($services as $service) {
        $response = wp_remote_get($service, array('timeout' => 5));
        
        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            
            if ($data) {
                if (isset($data['city']) && isset($data['country'])) {
                    $location = $data['city'] . ', ' . $data['country'];
                }
                if (isset($data['org'])) {
                    $isp = $data['org'];
                } elseif (isset($data['isp'])) {
                    $isp = $data['isp'];
                }
                break;
            }
        }
    }
    
    return array(
        'location' => $location,
        'isp' => $isp
    );
}

// Track time spent on website
function track_time_spent() {
    ?>
    <script>
    (function() {
        var startTime = Date.now();
        var sessionId = getCookie('device_session');
        
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        
        function updateTimeSpent() {
            var timeSpent = Math.floor((Date.now() - startTime) / 1000);
            
            if (sessionId && timeSpent > 10) { // Only update if more than 10 seconds
                // Use navigator.sendBeacon for more reliable unload tracking
                if (navigator.sendBeacon) {
                    const formData = new FormData();
                    formData.append('action', 'update_time_spent');
                    formData.append('session_id', sessionId);
                    formData.append('time_spent', timeSpent);
                    formData.append('nonce', '<?php echo wp_create_nonce('time_tracking'); ?>');
                    navigator.sendBeacon('<?php echo admin_url('admin-ajax.php'); ?>', formData);
                } else {
                    // Fallback for older browsers
                    jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                        action: 'update_time_spent',
                        session_id: sessionId,
                        time_spent: timeSpent,
                        nonce: '<?php echo wp_create_nonce('time_tracking'); ?>'
                    });
                }
            }
        }
        
        // Update time spent every 30 seconds
        setInterval(updateTimeSpent, 30000);
        
        // Update on page unload
        window.addEventListener('beforeunload', updateTimeSpent);
    })();
    </script>
    <?php
}
add_action('wp_footer', 'track_time_spent');

// AJAX handler for updating time spent
function update_time_spent() {
    if (!wp_verify_nonce($_POST['nonce'], 'time_tracking')) {
        wp_die('Security check failed');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';
    
    $session_id = sanitize_text_field($_POST['session_id']);
    $time_spent = intval($_POST['time_spent']);
    
    $wpdb->update(
        $table_name,
        array('time_spent' => $time_spent),
        array('session_id' => $session_id)
    );
    
    wp_die();
}
add_action('wp_ajax_update_time_spent', 'update_time_spent');
add_action('wp_ajax_nopriv_update_time_spent', 'update_time_spent');

// AJAX handler for tracking custom events
function track_custom_event() {
    // Rate limiting check
    if (!alhadiya_rate_limit_tracking()) {
        wp_send_json_error('Rate limit exceeded');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_event_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_events';

    $session_id = sanitize_text_field($_POST['session_id']);
    $event_type = sanitize_text_field($_POST['event_type']);
    $event_name = sanitize_text_field($_POST['event_name']);
    $event_value = isset($_POST['event_value']) ? sanitize_text_field($_POST['event_value']) : '';

    $wpdb->insert(
        $table_name,
        array(
            'session_id' => $session_id,
            'event_type' => $event_type,
            'event_name' => $event_name,
            'event_value' => $event_value,
            'timestamp' => current_time('mysql')
        )
    );

    wp_send_json_success('Event tracked successfully');
}
add_action('wp_ajax_track_custom_event', 'track_custom_event');
add_action('wp_ajax_nopriv_track_custom_event', 'track_custom_event');

// AJAX handler for updating device screen size
function update_device_screen_size() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_screen_size_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';

    $session_id = sanitize_text_field($_POST['session_id']);
    $screen_size = sanitize_text_field($_POST['screen_size']);

    $wpdb->update(
        $table_name,
        array('screen_size' => $screen_size),
        array('session_id' => $session_id)
    );

    wp_send_json_success('Screen size updated successfully');
}
add_action('wp_ajax_update_device_screen_size', 'update_device_screen_size');
add_action('wp_ajax_nopriv_update_device_screen_size', 'update_device_screen_size');

// ENHANCED AJAX handler for updating comprehensive client-side device details
function update_client_device_details() {
    // Rate limiting check
    if (!alhadiya_rate_limit_tracking()) {
        wp_send_json_error('Rate limit exceeded');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_device_info_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';

    $session_id = sanitize_text_field($_POST['session_id']);
    if (empty($session_id) || !alhadiya_validate_session_id($session_id)) {
        wp_send_json_error('Invalid session ID');
        return;
    }

    $data_to_update = array();

    // Basic device information
    if (isset($_POST['language'])) $data_to_update['language'] = sanitize_text_field($_POST['language']);
    if (isset($_POST['timezone'])) $data_to_update['timezone'] = sanitize_text_field($_POST['timezone']);
    
    // Browser information
    if (isset($_POST['browser'])) $data_to_update['browser'] = sanitize_text_field($_POST['browser']);
    if (isset($_POST['browser_version'])) $data_to_update['browser_version'] = sanitize_text_field($_POST['browser_version']);
    
    // Screen and display information
    if (isset($_POST['screen_size'])) $data_to_update['screen_size'] = sanitize_text_field($_POST['screen_size']);
    if (isset($_POST['screen_resolution'])) $data_to_update['screen_resolution'] = sanitize_text_field($_POST['screen_resolution']);
    if (isset($_POST['viewport_size'])) $data_to_update['viewport_size'] = sanitize_text_field($_POST['viewport_size']);
    if (isset($_POST['device_orientation'])) $data_to_update['device_orientation'] = sanitize_text_field($_POST['device_orientation']);
    
    // Connection information
    if (isset($_POST['connection_type'])) $data_to_update['connection_type'] = sanitize_text_field($_POST['connection_type']);
    if (isset($_POST['connection_speed'])) $data_to_update['connection_speed'] = sanitize_text_field($_POST['connection_speed']);
    
    // Battery information
    if (isset($_POST['battery_level'])) $data_to_update['battery_level'] = floatval($_POST['battery_level']);
    if (isset($_POST['battery_charging'])) $data_to_update['battery_charging'] = intval($_POST['battery_charging']);
    
    // Performance information
    if (isset($_POST['memory_info'])) $data_to_update['memory_info'] = floatval($_POST['memory_info']);
    if (isset($_POST['storage_info'])) $data_to_update['storage_info'] = floatval($_POST['storage_info']);
    
    // Hardware information
    if (isset($_POST['cpu_cores'])) $data_to_update['cpu_cores'] = intval($_POST['cpu_cores']);
    if (isset($_POST['touchscreen_detected'])) $data_to_update['touchscreen_detected'] = intval($_POST['touchscreen_detected']);
    
    // User interaction data
    if (isset($_POST['scroll_depth_max'])) $data_to_update['scroll_depth_max'] = floatval($_POST['scroll_depth_max']);
    if (isset($_POST['click_count'])) $data_to_update['click_count'] = intval($_POST['click_count']);
    if (isset($_POST['keypress_count'])) $data_to_update['keypress_count'] = intval($_POST['keypress_count']);
    if (isset($_POST['mouse_movements'])) $data_to_update['mouse_movements'] = intval($_POST['mouse_movements']);

    if (!empty($data_to_update)) {
        $result = $wpdb->update(
            $table_name,
            $data_to_update,
            array('session_id' => $session_id)
        );
        
        if ($result === false) {
            wp_send_json_error('Database update failed: ' . $wpdb->last_error);
        } else {
            wp_send_json_success('Client device details updated successfully');
        }
    } else {
        wp_send_json_error('No data to update');
    }
}
add_action('wp_ajax_update_client_device_details', 'update_client_device_details');
add_action('wp_ajax_nopriv_update_client_device_details', 'update_client_device_details');

// High-performance tracking endpoint for large traffic
function alhadiya_tracking_endpoint() {
    // Check if this is our tracking endpoint
    if (isset($_GET['alhadiya_track']) && $_GET['alhadiya_track'] === '1') {
        // Validate nonce for security
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'alhadiya_track_nonce')) {
            wp_die('Security check failed', 'Unauthorized', array('response' => 403));
        }
        
        // Process tracking data quickly
        track_enhanced_device_info();
        
        // Return 1x1 transparent pixel
        header('Content-Type: image/gif');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // 1x1 transparent GIF
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        echo $pixel;
        exit;
    }
}
add_action('init', 'alhadiya_tracking_endpoint', 1);

// Performance: Optimize AJAX requests with compression
function alhadiya_optimize_ajax_response() {
    if (wp_doing_ajax()) {
        // Enable gzip compression for AJAX responses
        if (!headers_sent() && function_exists('gzencode')) {
            ob_start('ob_gzhandler');
        }
        
        // Set cache headers for non-sensitive AJAX responses
        if (isset($_POST['action']) && in_array($_POST['action'], array('track_custom_event', 'update_client_device_details'))) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
    }
}
add_action('wp_ajax_nopriv_track_custom_event', 'alhadiya_optimize_ajax_response', 1);
add_action('wp_ajax_track_custom_event', 'alhadiya_optimize_ajax_response', 1);


// Fixed WooCommerce order submission with fixed IP blocking
function handle_woocommerce_order() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        wp_send_json_error('WooCommerce is not active');
        return;
    }
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Check IP blocking - Fixed implementation
    if (!check_ip_blocking()) {
        return; // Function already sends JSON error
    }
    
    // Track enhanced device info
    $device_info = track_enhanced_device_info();
    
    // Prevent duplicate submissions with better key
    $user_ip = get_client_ip();
    $submission_key = 'order_submission_' . md5($user_ip . $_POST['billing_phone'] . date('Y-m-d-H'));
    if (get_transient($submission_key)) {
        wp_send_json_error('ডুপ্লিকেট অর্ডার সনাক্ত করা হয়েছে। অনুগ্রহ করে একটু অপেক্ষা করুন।');
        return;
    }
    set_transient($submission_key, true, 300); // 5 minutes
    
    // Sanitize and validate input data
    $billing_first_name = isset($_POST['billing_first_name']) ? sanitize_text_field($_POST['billing_first_name']) : '';
    $billing_phone = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : '';
    $billing_address_1 = isset($_POST['billing_address_1']) ? sanitize_textarea_field($_POST['billing_address_1']) : '';
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $delivery_zone = isset($_POST['delivery_zone']) ? sanitize_text_field($_POST['delivery_zone']) : '';
    $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
    $transaction_number = isset($_POST['transaction_number']) ? sanitize_text_field($_POST['transaction_number']) : '';
    
    // Validate required fields
    if (empty($billing_first_name)) {
        wp_send_json_error('অনুগ্রহ করে আপনার নাম লিখুন');
        return;
    }
    
    if (empty($billing_phone)) {
        wp_send_json_error('অনুগ্রহ করে মোবাইল নাম্বার লিখুন');
        return;
    }
    
    if (strlen($billing_phone) < 10) {
        wp_send_json_error('অনুগ্রহ করে সঠিক মোবাইল নাম্বার লিখুন');
        return;
    }
    
    if (empty($billing_address_1)) {
        wp_send_json_error('অনুগ্রহ করে ঠিকানা লিখুন');
        return;
    }
    
    if (empty($product_id)) {
        wp_send_json_error('অনুগ্রহ করে একটি প্যাকেজ সিলেক্ট করুন');
        return;
    }
    
    if (empty($delivery_zone)) {
        wp_send_json_error('অনুগ্রহ করে ডেলিভারি অপশন সিলেক্ট করুন');
        return;
    }
    
    if (empty($payment_method)) {
        wp_send_json_error('অনুগ্রহ করে পেমেন্ট পদ্ধতি সিলেক্ট করুন');
        return;
    }
    
    // Validate transaction number for digital payments
    if (in_array($payment_method, ['bkash', 'nagad', 'rocket']) && empty($transaction_number)) {
        wp_send_json_error('অনুগ্রহ করে ট্রানজেকশন নাম্বার লিখুন');
        return;
    }
    
    // Get product
    $product = wc_get_product($product_id);
    if (!$product || !$product->exists()) {
        wp_send_json_error('প্রোডাক্ট পাওয়া যায়নি। অনুগ্রহ করে আবার চেষ্টা করুন।');
        return;
    }
    
    try {
        // Create order
        $order = wc_create_order();
        
        if (is_wp_error($order)) {
            wp_send_json_error('অর্ডার তৈরি করতে সমস্যা হয়েছে: ' . $order->get_error_message());
            return;
        }
        
        // Add product to order
        $item_id = $order->add_product($product, 1);
        if (!$item_id) {
            wp_send_json_error('প্রোডাক্ট অর্ডারে যোগ করতে সমস্যা হয়েছে');
            return;
        }
        
        // Set billing address
        $order->set_billing_first_name($billing_first_name);
        $order->set_billing_last_name('');
        $order->set_billing_phone($billing_phone);
        $order->set_billing_address_1($billing_address_1);
        
        // Set shipping address same as billing
        $order->set_shipping_first_name($billing_first_name);
        $order->set_shipping_address_1($billing_address_1);
        
        // Add customer info to device tracking table upon successful purchase
        global $wpdb;
        $tracking_table = $wpdb->prefix . 'device_tracking';
        $wpdb->update(
            $tracking_table,
            array(
                'has_purchased' => 1,
                'customer_name' => $billing_first_name,
                'customer_phone' => $billing_phone,
                'customer_address' => $billing_address_1,
            ),
            array('session_id' => $device_info['session_id'])
        );

        // Set delivery zone and charges
        $delivery_charges = array(
            '1' => floatval(get_theme_mod('dhaka_delivery_charge', 0)),
            '2' => floatval(get_theme_mod('outside_dhaka_delivery_charge', 0))
        );
        
        $delivery_charge = isset($delivery_charges[$delivery_zone]) ? $delivery_charges[$delivery_zone] : 0;
        
        // Add metadata
        $order->update_meta_data('_delivery_zone', $delivery_zone);
        $order->update_meta_data('_delivery_charge', $delivery_charge);
        $order->update_meta_data('_order_source', 'landing_page');
        $order->update_meta_data('_payment_method_selected', $payment_method);
        $order->update_meta_data('_device_info', json_encode($device_info));
        $order->update_meta_data('_session_id', $device_info['session_id']);
        
        if (!empty($transaction_number)) {
            $order->update_meta_data('_transaction_number', $transaction_number);
        }
        
        // Add delivery charge if applicable
        if ($delivery_charge > 0) {
            $delivery_title = $delivery_zone == '1' ? 
                get_theme_mod('dhaka_delivery_title', 'ঢাকার মধ্যে ডেলিভারি') : 
                get_theme_mod('outside_dhaka_delivery_title', 'ঢাকার বাইরে ডেলিভারি');
            
            $order->add_fee($delivery_title, $delivery_charge);
        }
        
        // Set payment method
        $payment_methods = array(
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            'pay_later' => 'Pay Later'
        );
        
        $payment_title = isset($payment_methods[$payment_method]) ? $payment_methods[$payment_method] : 'Cash on Delivery';
        $order->set_payment_method($payment_method);
        $order->set_payment_method_title($payment_title);
        
        // Calculate totals
        $order->calculate_totals();
        
        // Set order status based on payment method
        if ($payment_method === 'pay_later') {
            $order->set_status('pending');
        } else {
            $order->set_status('processing');
        }
        
        // Add order notes
        $zone_text = $delivery_zone == '1' ? 'ঢাকার মধ্যে' : 'ঢাকার বাইরে';
        $note = 'অর্ডার ল্যান্ডিং পেজ থেকে এসেছে। ডেলিভারি জোন: ' . $zone_text . '. পেমেন্ট পদ্ধতি: ' . $payment_title;
        
        if (!empty($transaction_number)) {
            $note .= '. ট্রানজেকশন নাম্বার: ' . $transaction_number;
        }
        
        $order->add_order_note($note);
        
        // Save order
        $order_id = $order->save();
        
        if (!$order_id) {
            wp_send_json_error('অর্ডার সেভ করতে সমস্যা হয়েছে');
            return;
        }
        
        // Block IP for specified time - Fixed implementation
        $block_time = get_theme_mod('ip_block_time', 1);
        $block_unit = get_theme_mod('ip_block_unit', 'minutes');
        block_ip_after_order($user_ip, $block_time, $block_unit);
        
        // Clear any caches
        if (function_exists('wc_delete_shop_order_transients')) {
            wc_delete_shop_order_transients($order_id);
        }
        
        // Return success response with enhanced data
        wp_send_json_success(array(
            'message' => 'অর্ডার সফলভাবে সম্পন্ন হয়েছে! আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবে।',
            'order_id' => $order_id,
            'payment_method' => $payment_title,
            'transaction_number' => $transaction_number,
            'customer_name' => $billing_first_name,
            'customer_phone' => $billing_phone,
            'total_amount' => $order->get_total(),
            'redirect_url' => home_url('/order-success/?order_id=' . $order_id . '&security=' . wp_create_nonce('order_success_' . $order_id))
        ));
        
    } catch (Exception $e) {
        error_log('Order creation error: ' . $e->getMessage());
        wp_send_json_error('অর্ডার তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
    }
}
add_action('wp_ajax_submit_wc_order', 'handle_woocommerce_order');
add_action('wp_ajax_nopriv_submit_wc_order', 'handle_woocommerce_order');

// Enhanced customizer options
function alhadiya_customize_register($wp_customize) {
    // Logo Settings Section
    $wp_customize->add_section('alhadiya_logo_settings', array(
        'title' => __('Logo Settings', 'alhadiya'),
        'priority' => 25,
    ));
    
    // Logo Size
    $wp_customize->add_setting('logo_size', array('default' => 200));
    $wp_customize->add_control('logo_size', array(
        'label' => __('Logo Size (px)', 'alhadiya'),
        'section' => 'alhadiya_logo_settings',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 500,
            'step' => 10,
        ),
    ));
    
    // Logo Alignment
    $wp_customize->add_setting('logo_alignment', array('default' => 'center'));
    $wp_customize->add_control('logo_alignment', array(
        'label' => __('Logo Alignment', 'alhadiya'),
        'section' => 'alhadiya_logo_settings',
        'type' => 'select',
        'choices' => array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right'
        ),
    ));

    // Content Management Section
    $wp_customize->add_section('alhadiya_content_management', array(
        'title' => __('Content Management', 'alhadiya'),
        'priority' => 28,
    ));

    // Course Items with individual colors
    $course_items = array(
        'item1' => 'অর্গানিক হাতের মেহেদী তৈরি',
        'item2' => 'ড্রাই রিলিজ কিভাবে করে',
        'item3' => 'মেহেদী কোণ তৈরি',
        'item4' => 'প্রফেশনাল রেসিপি শিট',
        'item5' => 'কি কি তেল ব্যবহার করা যাবে',
        'item6' => 'কিভাবে মেহেদির রঙ গাড় হবে (সিক্রেট টিপস)',
        'item7' => 'মেহেদির মূল্য নির্ধারণ',
        'item8' => 'দীর্ঘদিন সংরক্ষণ কিভাবে করবেন',
        'item9' => 'মেহেদী কিভাবে বিক্রি করবেন',
        'item10' => 'সার্টিফিকেট প্রদান',
        'item11' => 'মেহেদী কোণ প্যাকেজিং',
        'item12' => 'প্যাকেজিং ডিজাইন ও লেবেলিং',
        'item13' => 'বিক্রির জন্য প্রস্তুতি'
    );

    foreach ($course_items as $key => $default_text) {
        // Text setting
        $wp_customize->add_setting('course_' . $key . '_text', array('default' => $default_text));
        $wp_customize->add_control('course_' . $key . '_text', array(
            'label' => __('Course Item ' . substr($key, 4) . ' Text', 'alhadiya'),
            'section' => 'alhadiya_content_management',
            'type' => 'text',
        ));

        // Color setting
        $wp_customize->add_setting('course_' . $key . '_color', array('default' => '#28a745'));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'course_' . $key . '_color', array(
            'label' => __('Course Item ' . substr($key, 4) . ' Color', 'alhadiya'),
            'section' => 'alhadiya_content_management',
        )));
    }

    // Section titles and colors
    $wp_customize->add_setting('section1_title', array('default' => '🌱 অর্গানিক মেহেদী তৈরির সহজ উপায়'));
    $wp_customize->add_control('section1_title', array(
        'label' => __('Section 1 Title', 'alhadiya'),
        'section' => 'alhadiya_content_management',
        'type' => 'text',
    ));

    $wp_customize->add_setting('section1_color', array('default' => '#28a745'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'section1_color', array(
        'label' => __('Section 1 Color', 'alhadiya'),
        'section' => 'alhadiya_content_management',
    )));

    $wp_customize->add_setting('section2_title', array('default' => '🔥 মেহেদী রঙ বাড়ানোর গোপন টিপস'));
    $wp_customize->add_control('section2_title', array(
        'label' => __('Section 2 Title', 'alhadiya'),
        'section' => 'alhadiya_content_management',
        'type' => 'text',
    ));

    $wp_customize->add_setting('section2_color', array('default' => '#dc3545'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'section2_color', array(
        'label' => __('Section 2 Color', 'alhadiya'),
        'section' => 'alhadiya_content_management',
    )));

    $wp_customize->add_setting('section3_title', array('default' => '📦 প্যাকেজিং ও সার্টিফিকেশন'));
    $wp_customize->add_control('section3_title', array(
        'label' => __('Section 3 Title', 'alhadiya'),
        'section' => 'alhadiya_content_management',
        'type' => 'text',
    ));

    $wp_customize->add_setting('section3_color', array('default' => '#6f42c1'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'section3_color', array(
        'label' => __('Section 3 Color', 'alhadiya'),
        'section' => 'alhadiya_content_management',
    )));
    
    // Payment Settings Section
    $wp_customize->add_section('alhadiya_payment_settings', array(
        'title' => __('Payment Settings', 'alhadiya'),
        'priority' => 35,
    ));

    // Payment method text toggle
    $wp_customize->add_setting('show_payment_text', array('default' => true));
    $wp_customize->add_control('show_payment_text', array(
        'label' => __('Show Payment Method Text', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'checkbox',
        'description' => __('Enable to show text with icons, disable to show only icons', 'alhadiya'),
    ));
    
    // Payment method icons
    $wp_customize->add_setting('bkash_icon');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'bkash_icon', array(
        'label' => __('bKash Icon', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'mime_type' => 'image',
    )));

    $wp_customize->add_setting('nagad_icon');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'nagad_icon', array(
        'label' => __('Nagad Icon', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'mime_type' => 'image',
    )));

    $wp_customize->add_setting('rocket_icon');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'rocket_icon', array(
        'label' => __('Rocket Icon', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'mime_type' => 'image',
    )));
    
    // bKash Number and Color
    $wp_customize->add_setting('bkash_number', array('default' => '01975669946'));
    $wp_customize->add_control('bkash_number', array(
        'label' => __('bKash Number', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('bkash_color', array('default' => '#e2136e'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bkash_color', array(
        'label' => __('bKash Color', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
    )));

    $wp_customize->add_setting('bkash_instruction', array('default' => 'আর এই নাম্বারে বিকাশ সেন্ডমানি করে ফর্ম এ লিখুন (Personal)'));
    $wp_customize->add_control('bkash_instruction', array(
        'label' => __('bKash Instruction Text', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'textarea',
    ));
    
    // Nagad Number and Color
    $wp_customize->add_setting('nagad_number', array('default' => '01737146996'));
    $wp_customize->add_control('nagad_number', array(
        'label' => __('Nagad Number', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('nagad_color', array('default' => '#f47920'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nagad_color', array(
        'label' => __('Nagad Color', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
    )));

    $wp_customize->add_setting('nagad_instruction', array('default' => 'আর এই নাম্বারে নগদে সেন্ডমানি করে ফর্ম এ লিখুন (Personal)'));
    $wp_customize->add_control('nagad_instruction', array(
        'label' => __('Nagad Instruction Text', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'textarea',
    ));
    
    // Rocket Number and Color
    $wp_customize->add_setting('rocket_number', array('default' => '01737146996'));
    $wp_customize->add_control('rocket_number', array(
        'label' => __('Rocket Number', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('rocket_color', array('default' => '#8b1538'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'rocket_color', array(
        'label' => __('Rocket Color', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
    )));

    $wp_customize->add_setting('rocket_instruction', array('default' => 'আর এই নাম্বারে রকেটে সেন্ডমানি করে ফর্ম এ লিখুন (Personal)'));
    $wp_customize->add_control('rocket_instruction', array(
        'label' => __('Rocket Instruction Text', 'alhadiya'),
        'section' => 'alhadiya_payment_settings',
        'type' => 'textarea',
    ));

    // IP Blocking Settings - Fixed defaults
    $wp_customize->add_section('alhadiya_security_settings', array(
        'title' => __('Security Settings', 'alhadiya'),
        'priority' => 36,
    ));

    $wp_customize->add_setting('ip_block_time', array('default' => 1));
    $wp_customize->add_control('ip_block_time', array(
        'label' => __('IP Block Time', 'alhadiya'),
        'section' => 'alhadiya_security_settings',
        'type' => 'number',
        'description' => __('How long to block IP after order submission', 'alhadiya'),
        'input_attrs' => array(
            'min' => 1,
            'max' => 365,
        ),
    ));

    $wp_customize->add_setting('ip_block_unit', array('default' => 'minutes'));
    $wp_customize->add_control('ip_block_unit', array(
        'label' => __('IP Block Unit', 'alhadiya'),
        'section' => 'alhadiya_security_settings',
        'type' => 'select',
        'choices' => array(
            'minutes' => 'Minutes',
            'hours' => 'Hours',
            'days' => 'Days'
        ),
    ));
    
    // Main Content Settings
    $wp_customize->add_section('alhadiya_content_settings', array(
        'title' => __('Content Settings', 'alhadiya'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('main_heading_text', array('default' => 'অর্গানিক হাতের মেহেদি বানানোর কোর্স মাত্র ৪৯০ টাকা'));
    $wp_customize->add_control('main_heading_text', array(
        'label' => __('Main Heading Text', 'alhadiya'),
        'section' => 'alhadiya_content_settings',
        'type' => 'text',
    ));
    
    // Video Settings
    $wp_customize->add_section('alhadiya_video_settings', array(
        'title' => __('Video Settings', 'alhadiya'),
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('youtube_video_url', array('default' => ''));
    $wp_customize->add_control('youtube_video_url', array(
        'label' => __('YouTube Video URL', 'alhadiya'),
        'section' => 'alhadiya_video_settings',
        'type' => 'url',
    ));
    
    // Delivery Settings
    $wp_customize->add_section('alhadiya_delivery_settings', array(
        'title' => __('Delivery Settings', 'alhadiya'),
        'priority' => 45,
    ));
    
    $wp_customize->add_setting('dhaka_delivery_title', array('default' => 'ঢাকার মধ্যে ডেলিভারি'));
    $wp_customize->add_control('dhaka_delivery_title', array(
        'label' => __('Dhaka Delivery Title', 'alhadiya'),
        'section' => 'alhadiya_delivery_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('dhaka_delivery_charge', array('default' => 0));
    $wp_customize->add_control('dhaka_delivery_charge', array(
        'label' => __('Dhaka Delivery Charge (৳)', 'alhadiya'),
        'section' => 'alhadiya_delivery_settings',
        'type' => 'number',
    ));
    
    $wp_customize->add_setting('outside_dhaka_delivery_title', array('default' => 'ঢাকার বাইরে ডেলিভারি'));
    $wp_customize->add_control('outside_dhaka_delivery_title', array(
        'label' => __('Outside Dhaka Delivery Title', 'alhadiya'),
        'section' => 'alhadiya_delivery_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('outside_dhaka_delivery_charge', array('default' => 0));
    $wp_customize->add_control('outside_dhaka_delivery_charge', array(
        'label' => __('Outside Dhaka Delivery Charge (৳)', 'alhadiya'),
        'section' => 'alhadiya_delivery_settings',
        'type' => 'number',
    ));
    
    // Other Settings
    $wp_customize->add_section('alhadiya_other_settings', array(
        'title' => __('Other Settings', 'alhadiya'),
        'priority' => 50,
    ));
    
    $wp_customize->add_setting('phone_number', array('default' => '+8801737146996'));
    $wp_customize->add_control('phone_number', array(
        'label' => __('Phone Number', 'alhadiya'),
        'section' => 'alhadiya_other_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('whatsapp_number', array('default' => '1737146996'));
    $wp_customize->add_control('whatsapp_number', array(
        'label' => __('WhatsApp Number', 'alhadiya'),
        'section' => 'alhadiya_other_settings',
        'type' => 'text',
    ));
    
    // Footer Settings
    $wp_customize->add_section('alhadiya_footer_settings', array(
        'title' => __('Footer Settings', 'alhadiya'),
        'priority' => 55,
    ));
    
    // NEW: Footer Enable/Disable Setting
    $wp_customize->add_setting('enable_footer', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    $wp_customize->add_control('enable_footer', array(
        'label' => __('Enable Footer', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'checkbox',
        'description' => __('Check to display the footer, uncheck to hide it.', 'alhadiya'),
    ));

    $wp_customize->add_setting('footer_description', array('default' => 'অর্গানিক হাতের মেহেদি বানানোর সম্পূর্ণ কোর্স। ঘরে বসে শিখুন প্রফেশনাল মেহেদি তৈরির সব কৌশল।'));
    $wp_customize->add_control('footer_description', array(
        'label' => __('Footer Description', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('facebook_url', array('default' => ''));
    $wp_customize->add_control('facebook_url', array(
        'label' => __('Facebook URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('youtube_url', array('default' => ''));
    $wp_customize->add_control('youtube_url', array(
        'label' => __('YouTube URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('instagram_url', array('default' => ''));
    $wp_customize->add_control('instagram_url', array(
        'label' => __('Instagram URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('email_address', array('default' => ''));
    $wp_customize->add_control('email_address', array(
        'label' => __('Email Address', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'email',
    ));
    
    $wp_customize->add_setting('business_address', array('default' => ''));
    $wp_customize->add_control('business_address', array(
        'label' => __('Business Address', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('business_hours', array('default' => 'সকাল ৯টা - রাত ৯টা'));
    $wp_customize->add_control('business_hours', array(
        'label' => __('Business Hours', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('privacy_policy_url', array('default' => ''));
    $wp_customize->add_control('privacy_policy_url', array(
        'label' => __('Privacy Policy URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('terms_conditions_url', array('default' => ''));
    $wp_customize->add_control('terms_conditions_url', array(
        'label' => __('Terms & Conditions URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('refund_policy_url', array('default' => ''));
    $wp_customize->add_control('refund_policy_url', array(
        'label' => __('Refund Policy URL', 'alhadiya'),
        'section' => 'alhadiya_footer_settings',
        'type' => 'url',
    ));
    
    // SEO & Analytics Settings
    $wp_customize->add_section('alhadiya_seo_settings', array(
        'title' => __('SEO & Analytics', 'alhadiya'),
        'priority' => 60,
    ));
    
    $wp_customize->add_setting('site_description', array('default' => 'অর্গানিক হাতের মেহেদি বানানোর কোর্স - সহজ উপায়ে শিখুন প্রফেশনাল মেহেদি তৈরির কৌশল'));
    $wp_customize->add_control('site_description', array(
        'label' => __('Site Description (Meta)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('og_image');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'og_image', array(
        'label' => __('OG Image (1200x630px)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'mime_type' => 'image',
    )));
    
    $wp_customize->add_setting('favicon');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'favicon', array(
        'label' => __('Favicon (32x32px)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'mime_type' => 'image',
    )));
    
    $wp_customize->add_setting('apple_touch_icon');
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'apple_touch_icon', array(
        'label' => __('Apple Touch Icon (180x180px)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'mime_type' => 'image',
    )));
    
    $wp_customize->add_setting('google_analytics_id', array('default' => ''));
    $wp_customize->add_control('google_analytics_id', array(
        'label' => __('Google Analytics ID (G-XXXXXXXXXX)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('facebook_pixel_id', array('default' => ''));
    $wp_customize->add_control('facebook_pixel_id', array(
        'label' => __('Facebook Pixel ID', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'type' => 'text',
    ));

    // New: Google Tag Manager ID
    $wp_customize->add_setting('google_tag_manager_id', array('default' => ''));
    $wp_customize->add_control('google_tag_manager_id', array(
        'label' => __('Google Tag Manager ID (GTM-XXXXXXX)', 'alhadiya'),
        'section' => 'alhadiya_seo_settings',
        'type' => 'text',
    ));
    
    // Header Settings
    $wp_customize->add_section('alhadiya_header_settings', array(
        'title' => __('Header Settings', 'alhadiya'),
        'priority' => 20,
    ));
    
    $wp_customize->add_setting('header_announcement', array('default' => ''));
    $wp_customize->add_control('header_announcement', array(
        'label' => __('Header Announcement', 'alhadiya'),
        'section' => 'alhadiya_header_settings',
        'type' => 'textarea',
        'description' => __('Add an announcement to display at the top of the page (leave empty to hide)', 'alhadiya'),
    ));
}
add_action('customize_register', 'alhadiya_customize_register');

// Sanitize checkbox input for Customizer
function sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

// Add custom fields for reviews
function add_review_meta_boxes() {
    add_meta_box('review_details', 'Review Details', 'review_details_callback', 'course_review');
}
add_action('add_meta_boxes', 'add_review_meta_boxes');

function review_details_callback($post) {
    wp_nonce_field('save_review_details', 'review_details_nonce');
    $customer_name = get_post_meta($post->ID, '_customer_name', true);
    $customer_rating = get_post_meta($post->ID, '_customer_rating', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="customer_name">Customer Name</label></th>
            <td><input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="customer_rating">Rating (1-5)</label></th>
            <td>
                <select id="customer_rating" name="customer_rating">
                    <option value="">Select Rating</option>
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php selected($customer_rating, $i); ?>><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Save review meta
function save_review_details($post_id) {
    if (!isset($_POST['review_details_nonce']) || !wp_verify_nonce($_POST['review_details_nonce'], 'save_review_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['customer_name'])) {
        update_post_meta($post_id, '_customer_name', sanitize_text_field($_POST['customer_name']));
    }
    
    if (isset($_POST['customer_rating'])) {
        update_post_meta($post_id, '_customer_rating', sanitize_text_field($_POST['customer_rating']));
    }
}
add_action('save_post', 'save_review_details');

// AJAX handler for getting WooCommerce product data
function get_wc_product_data() {
    if (!wp_verify_nonce($_POST['nonce'], 'alhadiya_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $product_id = intval($_POST['id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Product not found');
        return;
    }
    
    // Get numeric price values
    $price = floatval($product->get_price());
    $regular_price = floatval($product->get_regular_price());
    $sale_price = $product->get_sale_price() ? floatval($product->get_sale_price()) : 0;
    
    wp_send_json(array(
        'name' => $product->get_name(),
        'price' => $price,
        'regular_price' => $regular_price,
        'sale_price' => $sale_price
    ));
}
add_action('wp_ajax_get_wc_product_data', 'get_wc_product_data');
add_action('wp_ajax_nopriv_get_wc_product_data', 'get_wc_product_data');

// Convert YouTube URL to embed URL
function get_youtube_embed_url($url) {
    if (empty($url)) return '';
    
    $video_id = '';
    
    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
        $video_id = $id[1];
    } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
        $video_id = $id[1];
    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
        $video_id = $id[1];
    }
    
    if ($video_id) {
        return 'https://www.youtube.com/embed/' . $video_id;
    }
    
    return '';
}

// Add custom columns to WooCommerce orders
add_filter('manage_edit-shop_order_columns', 'add_custom_order_columns');
add_action('manage_shop_order_posts_custom_column', 'populate_custom_order_columns', 10, 2);

function add_custom_order_columns($columns) {
    $columns['delivery_zone'] = __('Delivery Zone', 'alhadiya');
    $columns['payment_method_used'] = __('Payment Method', 'alhadiya');
    $columns['transaction_number'] = __('Transaction Number', 'alhadiya');
    $columns['device_info'] = __('Device Info', 'alhadiya');
    return $columns;
}

function populate_custom_order_columns($column, $post_id) {
    $order = wc_get_order($post_id);
    
    if ($column === 'delivery_zone') {
        $delivery_zone = $order->get_meta('_delivery_zone');
        echo $delivery_zone == '1' ? 'ঢাকার মধ্যে' : 'ঢাকার বাইরে';
    }
    
    if ($column === 'payment_method_used') {
        $payment_method = $order->get_meta('_payment_method_selected');
        $methods = array(
            'bkash' => '<span style="color: #e2136e;">bKash</span>',
            'nagad' => '<span style="color: #f47920;">Nagad</span>',
            'rocket' => '<span style="color: #8b1538;">Rocket</span>',
            'pay_later' => '<span style="color: #6c757d;">Pay Later</span>'
        );
        echo isset($methods[$payment_method]) ? $methods[$payment_method] : 'N/A';
    }
    
    if ($column === 'transaction_number') {
        $transaction_number = $order->get_meta('_transaction_number');
        echo $transaction_number ? $transaction_number : 'N/A';
    }

    if ($column === 'device_info') {
        $device_info = $order->get_meta('_device_info');
        if ($device_info) {
            $info = json_decode($device_info, true);
            echo '<small>IP: ' . $info['ip'] . '<br>Location: ' . $info['location_data']['location'] . '</small>';
        } else {
            echo 'N/A';
        }
    }
}

// Enhanced Admin menu for device tracking
function add_enhanced_device_tracking_menu() {
    add_menu_page(
        'Enhanced Device Tracking',
        'Device Tracking',
        'manage_options',
        'enhanced-device-tracking',
        'enhanced_device_tracking_page',
        'dashicons-admin-users',
        30
    );

    add_submenu_page(
        'enhanced-device-tracking',
        'Live User Tracking',
        'Live Tracking',
        'manage_options',
        'live-user-tracking',
        'alhadiya_live_tracking_page'
    );
    
    add_submenu_page(
        'enhanced-device-tracking',
        'Session Details',
        'Session Details',
        'manage_options',
        'device-session-details',
        'device_session_details_page'
    );
}
add_action('admin_menu', 'add_enhanced_device_tracking_menu');

function enhanced_device_tracking_page() {
    global $wpdb;
    $tracking_table = $wpdb->prefix . 'device_tracking';
    $events_table = $wpdb->prefix . 'device_events';
    
    // Get summary statistics
    $total_visitors = $wpdb->get_var("SELECT COUNT(DISTINCT session_id) FROM $tracking_table");
    $active_sessions = $wpdb->get_var("SELECT COUNT(*) FROM $tracking_table WHERE last_visit >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
    $total_events = $wpdb->get_var("SELECT COUNT(*) FROM $events_table WHERE DATE(timestamp) = CURDATE()");
    $avg_time = $wpdb->get_var("SELECT AVG(time_spent) FROM $tracking_table WHERE time_spent > 0");
    $avg_scroll = $wpdb->get_var("SELECT AVG(scroll_depth_max) FROM $tracking_table WHERE scroll_depth_max > 0");
    
    // Get recent tracking data with enhanced info
    $tracking_data = $wpdb->get_results("
        SELECT *, 
               CASE WHEN last_visit >= DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1 ELSE 0 END as is_online
        FROM $tracking_table 
        ORDER BY last_visit DESC 
        LIMIT 50
    ");
    
    ?>
    <div class="wrap">
        <h1>🚀 Alhadiya Device Tracking Dashboard</h1>
        
        <!-- Summary Cards -->
        <div class="alhadiya-admin-dashboard">
            <div class="alhadiya-summary-cards">
                <div class="alhadiya-summary-card">
                    <h3><?php echo number_format($total_visitors); ?></h3>
                    <p>Total Visitors</p>
                </div>
                <div class="alhadiya-summary-card">
                    <h3>
                        <span class="alhadiya-live-indicator"></span>
                        <?php echo number_format($active_sessions); ?>
                    </h3>
                    <p>Live Users (5 min)</p>
                </div>
                <div class="alhadiya-summary-card">
                    <h3><?php echo number_format($total_events); ?></h3>
                    <p>Today's Events</p>
                </div>
                <div class="alhadiya-summary-card">
                    <h3><?php echo round($avg_time, 1); ?>s</h3>
                    <p>Avg. Time Spent</p>
                </div>
                <div class="alhadiya-summary-card">
                    <h3><?php echo round($avg_scroll, 1); ?>%</h3>
                    <p>Avg. Scroll Depth</p>
                </div>
            </div>
            
            <!-- Live Users Alert -->
            <?php if ($active_sessions > 0): ?>
            <div class="alhadiya-live-users">
                <span class="alhadiya-live-indicator"></span>
                <strong><?php echo $active_sessions; ?> users are currently active on your site!</strong>
                <a href="<?php echo admin_url('admin.php?page=live-user-tracking'); ?>" class="button button-primary" style="margin-left: 15px;">View Live Tracking</a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <button type="button" class="button" onclick="location.reload()">🔄 Refresh</button>
                <a href="<?php echo admin_url('admin.php?page=live-user-tracking'); ?>" class="button">📺 Live Tracking</a>
            </div>
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Session Info</th>
                    <th>Device & Browser</th>
                    <th>Enhanced Tracking</th>
                    <th>User Activity</th>
                    <th>Location & Network</th>
                    <th>Customer Info</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tracking_data as $data): ?>
                <tr <?php echo $data->is_online ? 'style="background-color: #f0fff0; border-left: 4px solid #4caf50;"' : ''; ?>>
                    <!-- Status -->
                    <td>
                        <?php if ($data->is_online): ?>
                            <span class="alhadiya-live-indicator"></span>
                            <small style="color: #4caf50;"><strong>LIVE</strong></small>
                        <?php else: ?>
                            <span style="color: #999;">●</span>
                            <small style="color: #999;">Offline</small>
                        <?php endif; ?>
                        <br>
                        <?php if ($data->has_purchased): ?>
                            <span style="color: green; font-size: 12px;">💰 Customer</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Session Info -->
                    <td>
                        <small>
                            <strong>ID:</strong> <?php echo substr($data->session_id, 8, 8); ?>...<br>
                            <strong>IP:</strong> <?php echo $data->ip_address; ?><br>
                            <strong>Visits:</strong> <?php echo $data->visit_count; ?><br>
                            <strong>Pages:</strong> <?php echo $data->pages_viewed; ?><br>
                            <strong>Time:</strong> 
                            <?php 
                            $minutes = floor($data->time_spent / 60);
                            $seconds = $data->time_spent % 60;
                            echo $minutes . 'm ' . $seconds . 's';
                            ?>
                        </small>
                    </td>
                    
                    <!-- Device & Browser -->
                    <td>
                        <small>
                            <strong><?php echo $data->device_type; ?></strong><br>
                            <?php echo $data->browser; ?> 
                            <?php echo property_exists($data, 'browser_version') ? $data->browser_version : ''; ?><br>
                            <?php echo $data->os; ?><br>
                            <?php echo property_exists($data, 'screen_size') && $data->screen_size ? $data->screen_size : 'N/A'; ?>
                            <?php echo property_exists($data, 'touchscreen_detected') && $data->touchscreen_detected ? ' 👆' : ''; ?>
                        </small>
                    </td>
                    
                    <!-- Enhanced Tracking -->
                    <td>
                        <small>
                            <strong>Lang:</strong> <?php echo property_exists($data, 'language') && $data->language ? substr($data->language, 0, 5) : 'N/A'; ?><br>
                            <strong>TZ:</strong> <?php echo property_exists($data, 'timezone') && $data->timezone ? explode('/', $data->timezone)[1] ?? $data->timezone : 'N/A'; ?><br>
                            <strong>Conn:</strong> <?php echo property_exists($data, 'connection_type') && $data->connection_type ? $data->connection_type : 'N/A'; ?><br>
                            <strong>Batt:</strong> 
                            <?php 
                            if (property_exists($data, 'battery_level') && $data->battery_level !== null) {
                                echo round($data->battery_level * 100) . '%';
                                if (property_exists($data, 'battery_charging') && $data->battery_charging) {
                                    echo ' ⚡';
                                } else {
                                    echo ' 🔋';
                                }
                            } else {
                                echo 'N/A';
                            }
                            ?><br>
                            <strong>RAM:</strong> <?php echo property_exists($data, 'memory_info') && $data->memory_info ? round($data->memory_info) . 'MB' : 'N/A'; ?><br>
                            <strong>CPU:</strong> <?php echo property_exists($data, 'cpu_cores') && $data->cpu_cores ? $data->cpu_cores . ' cores' : 'N/A'; ?>
                        </small>
                    </td>
                    
                    <!-- User Activity -->
                    <td>
                        <small>
                            <strong>Scroll:</strong> <?php echo property_exists($data, 'scroll_depth_max') && $data->scroll_depth_max ? round($data->scroll_depth_max) . '%' : '0%'; ?><br>
                            <strong>Clicks:</strong> <?php echo property_exists($data, 'click_count') && $data->click_count ? $data->click_count : '0'; ?><br>
                            <strong>Keys:</strong> <?php echo property_exists($data, 'keypress_count') && $data->keypress_count ? $data->keypress_count : '0'; ?><br>
                            <strong>Mouse:</strong> <?php echo property_exists($data, 'mouse_movements') && $data->mouse_movements ? $data->mouse_movements : '0'; ?>
                        </small>
                    </td>
                    
                    <!-- Location & Network -->
                    <td>
                        <small>
                            <strong>Location:</strong> <?php echo $data->location ?: 'Unknown'; ?><br>
                            <strong>ISP:</strong> <?php echo $data->isp ?: 'Unknown'; ?><br>
                            <strong>Referrer:</strong> 
                            <?php 
                            if ($data->referrer) {
                                $domain = parse_url($data->referrer, PHP_URL_HOST);
                                echo $domain ? substr($domain, 0, 20) : 'Direct';
                            } else {
                                echo 'Direct';
                            }
                            ?>
                        </small>
                    </td>
                    
                    <!-- Customer Info -->
                    <td>
                        <?php if ($data->has_purchased && $data->customer_name): ?>
                            <small>
                                <strong><?php echo esc_html($data->customer_name); ?></strong><br>
                                📞 <?php echo esc_html($data->customer_phone); ?><br>
                                📍 <?php echo esc_html(substr($data->customer_address, 0, 30)) . (strlen($data->customer_address) > 30 ? '...' : ''); ?>
                            </small>
                        <?php else: ?>
                            <span style="color: #999;">Not a customer</span>
                        <?php endif; ?>
                    </td>
                                         <!-- Actions -->
                     <td>
                         <a href="<?php echo esc_url(admin_url('admin.php?page=device-session-details&session_id=' . $data->session_id)); ?>" class="button button-small">📊 Events</a>
                         <br><br>
                         <small>
                             <strong>First:</strong> <?php echo date('M j, H:i', strtotime($data->first_visit)); ?><br>
                             <strong>Last:</strong> <?php echo date('M j, H:i', strtotime($data->last_visit)); ?>
                         </small>
                     </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Live User Tracking Page
function alhadiya_live_tracking_page() {
    global $wpdb;
    $tracking_table = $wpdb->prefix . 'device_tracking';
    $events_table = $wpdb->prefix . 'device_events';
    
    // Get live users (active within last 5 minutes)
    $live_users = $wpdb->get_results("
        SELECT *, 
               TIMESTAMPDIFF(SECOND, last_visit, NOW()) as seconds_ago
        FROM $tracking_table 
        WHERE last_visit >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        ORDER BY last_visit DESC
    ");
    
    // Get recent events from live users
    $recent_events = $wpdb->get_results("
        SELECT e.*, dt.ip_address, dt.device_type, dt.browser, dt.location
        FROM $events_table e
        JOIN $tracking_table dt ON e.session_id = dt.session_id
        WHERE e.timestamp >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        ORDER BY e.timestamp DESC
        LIMIT 100
    ");
    
    ?>
    <div class="wrap">
        <h1>📺 Live User Tracking</h1>
        
        <!-- Live Status -->
        <div class="alhadiya-admin-dashboard">
            <div class="alhadiya-live-users">
                <span class="alhadiya-live-indicator"></span>
                <strong><?php echo count($live_users); ?> users are currently active on your site</strong>
                <button class="button" onclick="location.reload()" style="margin-left: 15px;">🔄 Refresh</button>
            </div>
            
            <!-- Live Users Grid -->
            <?php if (!empty($live_users)): ?>
            <h2>🟢 Currently Active Users</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;">
                <?php foreach ($live_users as $user): ?>
                <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 4px solid #4caf50;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <strong>🔴 LIVE USER</strong>
                        <small style="color: #4caf50;"><?php echo $user->seconds_ago; ?>s ago</small>
                    </div>
                    
                    <div style="font-size: 12px; line-height: 1.4;">
                        <strong>Device:</strong> <?php echo $user->device_type; ?> | <?php echo $user->browser; ?><br>
                        <strong>Location:</strong> <?php echo $user->location ?: 'Unknown'; ?><br>
                        <strong>Screen:</strong> <?php echo property_exists($user, 'screen_size') && $user->screen_size ? $user->screen_size : 'N/A'; ?><br>
                        <strong>Activity:</strong> 
                        Scroll: <?php echo property_exists($user, 'scroll_depth_max') ? round($user->scroll_depth_max) . '%' : '0%'; ?>, 
                        Clicks: <?php echo property_exists($user, 'click_count') ? $user->click_count : '0'; ?><br>
                        <strong>Time on site:</strong> <?php echo floor($user->time_spent / 60); ?>m <?php echo $user->time_spent % 60; ?>s<br>
                        
                        <?php if (property_exists($user, 'battery_level') && $user->battery_level): ?>
                        <strong>Battery:</strong> <?php echo round($user->battery_level * 100); ?>% 
                        <?php echo property_exists($user, 'battery_charging') && $user->battery_charging ? '⚡' : '🔋'; ?><br>
                        <?php endif; ?>
                        
                        <?php if (property_exists($user, 'connection_type') && $user->connection_type): ?>
                        <strong>Connection:</strong> <?php echo $user->connection_type; ?><br>
                        <?php endif; ?>
                    </div>
                    
                    <div style="margin-top: 10px;">
                        <a href="<?php echo admin_url('admin.php?page=device-session-details&session_id=' . $user->session_id); ?>" class="button button-small">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #666;">
                <h3>😴 No users currently active</h3>
                <p>Users will appear here when they visit your site</p>
            </div>
            <?php endif; ?>
            
            <!-- Recent Activity Feed -->
            <h2>⚡ Live Activity Feed (Last 30 minutes)</h2>
            <div style="background: white; border-radius: 8px; padding: 20px; max-height: 400px; overflow-y: auto;">
                <?php if (!empty($recent_events)): ?>
                    <?php foreach ($recent_events as $event): ?>
                    <div style="padding: 8px; border-bottom: 1px solid #eee; font-size: 12px;">
                        <span style="color: #666;"><?php echo date('H:i:s', strtotime($event->timestamp)); ?></span> - 
                        <strong><?php echo $event->device_type; ?></strong> from 
                        <span style="color: #0073aa;"><?php echo $event->location ?: 'Unknown'; ?></span>: 
                        <span style="color: #d63384;"><?php echo $event->event_name; ?></span>
                        <?php if ($event->event_value): ?>
                        <small style="color: #6c757d;"> (<?php echo substr($event->event_value, 0, 50); ?>)</small>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #666;">No recent activity</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Auto-refresh script -->
        <script>
        setTimeout(function() {
            location.reload();
        }, 30000); // Refresh every 30 seconds
        </script>
    </div>
    <?php
}

function device_session_details_page() {
    global $wpdb;
    $session_id = isset($_GET['session_id']) ? sanitize_text_field($_GET['session_id']) : '';
    $filter_start_date = isset($_GET['filter_start_date']) ? sanitize_text_field($_GET['filter_start_date']) : '';
    $filter_start_time = isset($_GET['filter_start_time']) ? sanitize_text_field($_GET['filter_start_time']) : '';
    $filter_end_date = isset($_GET['filter_end_date']) ? sanitize_text_field($_GET['filter_end_date']) : '';
    $filter_end_time = isset($_GET['filter_end_time']) ? sanitize_text_field($_GET['filter_end_time']) : '';
    $filter_event_types = isset($_GET['filter_event_types']) && is_array($_GET['filter_event_types']) ? array_map('sanitize_text_field', $_GET['filter_event_types']) : array();

    if (empty($session_id)) {
        echo '<div class="wrap"><h1>Session Details</h1><p>No session ID provided.</p></div>';
        return;
    }

    $tracking_table = $wpdb->prefix . 'device_tracking';
    $events_table = $wpdb->prefix . 'device_events';

    $session_data = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $tracking_table WHERE session_id = %s",
        $session_id
    ));

    $where_clauses = array();
    $where_clauses[] = $wpdb->prepare("session_id = %s", $session_id);

    // Date and Time Filtering
    if (!empty($filter_start_date)) {
        $start_datetime = $filter_start_date . (empty($filter_start_time) ? ' 00:00:00' : ' ' . $filter_start_time . ':00');
        $where_clauses[] = $wpdb->prepare("timestamp >= %s", $start_datetime);
    }
    if (!empty($filter_end_date)) {
        $end_datetime = $filter_end_date . (empty($filter_end_time) ? ' 23:59:59' : ' ' . $filter_end_time . ':59');
        $where_clauses[] = $wpdb->prepare("timestamp <= %s", $end_datetime);
    }

    // Event Type Filtering
    if (!empty($filter_event_types)) {
        $event_types_placeholder = implode(', ', array_fill(0, count($filter_event_types), '%s'));
        $where_clauses[] = $wpdb->prepare("event_type IN ($event_types_placeholder)", $filter_event_types);
    }

    $where_sql = count($where_clauses) > 0 ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

    $session_events = $wpdb->get_results(
        "SELECT * FROM $events_table" . $where_sql . " ORDER BY timestamp ASC"
    );

    // List of all possible event types for checkboxes
    $all_event_types = array(
        'page_view' => 'Page View',
        'swiper_slide_change' => 'Swiper Slide Change',
        'swiper_nav_click' => 'Swiper Navigation Click',
        'swiper_pagination_click' => 'Swiper Pagination Click',
        'section_view' => 'Section Viewed',
        'section_time_spent' => 'Section Time Spent',
        'button_visible' => 'Button Visible',
        'button_time_spent' => 'Button Time Spent',
        'button_click' => 'Button Click',
        'scroll' => 'Scroll',
        'scroll_depth' => 'Scroll Depth', // New event type
        'click_position' => 'Click Position', // New event type
        'key_press' => 'Key Press', // New event type
        'product_select' => 'Product Select',
        'delivery_option_select' => 'Delivery Option Select',
        'payment_method_select' => 'Payment Method Select',
        'form_field_focus' => 'Form Field Focus',
        'form_field_change' => 'Form Field Change',
        'faq_toggle' => 'FAQ Toggle',
        'order_form_submit' => 'Order Form Submit',
        'order_success' => 'Order Success',
        'order_failure' => 'Order Failure',
        'order_error' => 'Order Error',
        'video_ready' => 'Video Ready',
        'video_play' => 'Video Play',
        'video_pause' => 'Video Pause',
        'video_ended' => 'Video Ended',
        'video_buffering' => 'Video Buffering',
        'battery_status_change' => 'Battery Status Change', // New event type
    );

    ?>
    <div class="wrap session-details-wrap">
        <h1>Session Details for <small><?php echo substr(esc_html($session_id), 0, 20); ?>...</small></h1>
        <a href="<?php echo esc_url(admin_url('admin.php?page=enhanced-device-tracking')); ?>" class="button-secondary">← Back to All Sessions</a>

        <?php if ($session_data): ?>
            <div class="session-summary">
                <h2>Session Summary</h2>
                <p><strong>Session ID:</strong> <?php echo esc_html($session_data->session_id); ?></p>
                <p><strong>IP Address:</strong> <?php echo esc_html($session_data->ip_address); ?></p>
                <p><strong>Device Info:</strong> 
                    <?php echo esc_html($session_data->device_type); ?> (<?php echo esc_html($session_data->device_model); ?>) - 
                    <?php echo esc_html($session_data->browser); ?> on <?php echo esc_html($session_data->os); ?>
                </p>
                <p><strong>Location:</strong> <?php echo esc_html($session_data->location); ?></p>
                <p><strong>ISP:</strong> <?php echo esc_html($session_data->isp); ?></p>
                <p><strong>Referrer:</strong> <?php echo esc_html($session_data->referrer ? $session_data->referrer : 'Direct'); ?></p>
                <p><strong>Facebook ID:</strong> <?php echo esc_html($session_data->facebook_id ? $session_data->facebook_id : 'N/A'); ?></p>
                <p><strong>Visit Count:</strong> <?php echo esc_html($session_data->visit_count); ?></p>
                <p><strong>Time Spent:</strong> 
                    <?php 
                    $minutes = floor($session_data->time_spent / 60);
                    $seconds = $session_data->time_spent % 60;
                    echo $minutes . 'm ' . $seconds . 's';
                    ?>
                </p>
                <p><strong>Pages Viewed:</strong> <?php echo esc_html($session_data->pages_viewed); ?></p>
                <p><strong>Purchased:</strong> 
                    <?php if ($session_data->has_purchased): ?>
                        <span style="color: green;">✓ Yes</span>
                    <?php else: ?>
                        <span style="color: red;">✗ No</span>
                    <?php endif; ?>
                </p>
                <?php if ($session_data->has_purchased): ?>
                    <p><strong>Customer Name:</strong> <?php echo esc_html($session_data->customer_name); ?></p>
                    <p><strong>Customer Phone:</strong> <?php echo esc_html($session_data->customer_phone); ?></p>
                    <p><strong>Customer Address:</strong> <?php echo esc_html($session_data->customer_address); ?></p>
                <?php endif; ?>
                <p><strong>Screen Size:</strong> <?php echo property_exists($session_data, 'screen_size') && $session_data->screen_size ? esc_html($session_data->screen_size) : 'N/A'; ?></p>
                <p><strong>Language:</strong> <?php echo property_exists($session_data, 'language') && $session_data->language ? esc_html($session_data->language) : 'N/A'; ?></p>
                <p><strong>Timezone:</strong> <?php echo property_exists($session_data, 'timezone') && $session_data->timezone ? esc_html($session_data->timezone) : 'N/A'; ?></p>
                <p><strong>Connection Type:</strong> <?php echo property_exists($session_data, 'connection_type') && $session_data->connection_type ? esc_html($session_data->connection_type) : 'N/A'; ?></p>
                <p><strong>Battery Level:</strong> <?php echo property_exists($session_data, 'battery_level') && $session_data->battery_level !== null ? (esc_html($session_data->battery_level * 100) . '%') : 'N/A'; ?></p>
                <p><strong>Battery Charging:</strong> <?php echo property_exists($session_data, 'battery_charging') && $session_data->battery_charging !== null ? ($session_data->battery_charging ? 'Yes' : 'No') : 'N/A'; ?></p>
                <p><strong>Memory Info:</strong> <?php echo property_exists($session_data, 'memory_info') && $session_data->memory_info !== null ? (esc_html($session_data->memory_info) . 'GB') : 'N/A'; ?></p>
                <p><strong>CPU Cores:</strong> <?php echo property_exists($session_data, 'cpu_cores') && $session_data->cpu_cores !== null ? esc_html($session_data->cpu_cores) : 'N/A'; ?></p>
                <p><strong>Touchscreen Detected:</strong> <?php echo property_exists($session_data, 'touchscreen_detected') && $session_data->touchscreen_detected !== null ? ($session_data->touchscreen_detected ? 'Yes' : 'No') : 'N/A'; ?></p>
                <p><strong>First Visit:</strong> <?php echo date('M j, Y H:i:s', strtotime($session_data->first_visit)); ?></p>
                <p><strong>Last Visit:</strong> <?php echo date('M j, Y H:i:s', strtotime($session_data->last_visit)); ?></p>
            </div>
        <?php else: ?>
            <p>No session data found for the provided ID.</p>
        <?php endif; ?>

        <div class="event-log-table-container">
            <h2>📊 Event Log & Session Timeline</h2>
            
            <!-- Enhanced Filter Panel -->
            <div class="alhadiya-filter-panel">
                <h3>🔍 Filter Events</h3>
                <form method="GET" class="event-filter-form" id="event-filter-form">
                    <input type="hidden" name="page" value="device-session-details">
                    <input type="hidden" name="session_id" value="<?php echo esc_attr($session_id); ?>">
                    
                    <!-- Date & Time Filters -->
                    <div class="alhadiya-filter-row">
                        <div class="alhadiya-filter-group">
                            <label for="filter_start_date">📅 Start Date:</label>
                            <input type="date" name="filter_start_date" id="filter_start_date" value="<?php echo esc_attr($filter_start_date); ?>">
                        </div>
                        <div class="alhadiya-filter-group">
                            <label for="filter_start_time">🕐 Start Time:</label>
                            <input type="time" name="filter_start_time" id="filter_start_time" value="<?php echo esc_attr($filter_start_time); ?>">
                        </div>
                        <div class="alhadiya-filter-group">
                            <label for="filter_end_date">📅 End Date:</label>
                            <input type="date" name="filter_end_date" id="filter_end_date" value="<?php echo esc_attr($filter_end_date); ?>">
                        </div>
                        <div class="alhadiya-filter-group">
                            <label for="filter_end_time">🕐 End Time:</label>
                            <input type="time" name="filter_end_time" id="filter_end_time" value="<?php echo esc_attr($filter_end_time); ?>">
                        </div>
                    </div>

                    <!-- Preset Buttons -->
                    <div class="alhadiya-filter-row">
                        <div class="alhadiya-filter-group">
                            <label>⚡ Quick Filters:</label>
                            <div class="alhadiya-preset-buttons">
                                <button type="button" class="alhadiya-preset-btn" data-preset="today">Today</button>
                                <button type="button" class="alhadiya-preset-btn" data-preset="yesterday">Yesterday</button>
                                <button type="button" class="alhadiya-preset-btn" data-preset="last7days">Last 7 Days</button>
                                <button type="button" class="alhadiya-preset-btn" data-preset="last15days">Last 15 Days</button>
                                <button type="button" class="alhadiya-preset-btn" data-preset="last30days">Last 30 Days</button>
                            </div>
                        </div>
                    </div>

                    <!-- Event Type Filters -->
                    <div class="alhadiya-filter-row">
                        <div class="alhadiya-filter-group" style="flex: 1;">
                            <label>🎯 Event Types:</label>
                            <div class="alhadiya-event-types">
                                <?php foreach ($all_event_types as $type_slug => $type_label): ?>
                                    <label class="alhadiya-event-type-checkbox">
                                        <input type="checkbox" name="filter_event_types[]" value="<?php echo esc_attr($type_slug); ?>" <?php checked(in_array($type_slug, $filter_event_types), true); ?>>
                                        <?php echo esc_html($type_label); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="alhadiya-filter-row">
                        <div class="alhadiya-filter-group">
                            <input type="submit" class="button button-primary" value="🔍 Apply Filters">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=device-session-details&session_id=' . $session_id)); ?>" class="button">🔄 Reset All</a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($session_events)): ?>
                <table class="event-log-table wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Event Type</th>
                            <th>Section/Details</th>
                            <th>Value/Time Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($session_events as $event): ?>
                            <tr>
                                <td><?php echo date('d M Y, H:i:s', strtotime($event->timestamp)); ?></td>
                                <td><?php echo esc_html($event->event_type); ?></td>
                                <td>
                                    <?php 
                                    // Display event_name as Section/Details
                                    echo esc_html($event->event_name);
                                    ?>
                                </td>
                                <td class="event-value-col">
                                    <?php 
                                    if ($event->event_type === 'section_time_spent' || $event->event_type === 'video_play_time') {
                                        echo esc_html(round(floatval($event->event_value), 2)) . 's';
                                    } else if ($event->event_type === 'scroll_depth') {
                                        echo esc_html(round(floatval($event->event_value), 2)) . '%';
                                    } else if ($event->event_type === 'battery_status_change') {
                                        $battery_data = json_decode($event->event_value, true);
                                        if ($battery_data) {
                                            echo (isset($battery_data['level']) ? round($battery_data['level'] * 100) . '%' : 'N/A') . ' ' . (isset($battery_data['charging']) ? ($battery_data['charging'] ? '(Charging)' : '(Discharging)') : '');
                                        } else {
                                            echo esc_html($event->event_value);
                                        }
                                    } else {
                                        echo esc_html($event->event_value);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No events found for this session with the selected filters.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('event-filter-form');
        const startDateInput = document.getElementById('filter_start_date');
        const startTimeInput = document.getElementById('filter_start_time');
        const endDateInput = document.getElementById('filter_end_date');
        const endTimeInput = document.getElementById('filter_end_time');
        const presetButtons = document.querySelectorAll('.alhadiya-preset-btn');

        presetButtons.forEach(button => {
            button.addEventListener('click', function() {
                const preset = this.dataset.preset;
                const today = new Date();
                let startDate = new Date();
                let endDate = new Date();

                // Reset time inputs for presets
                startTimeInput.value = '00:00';
                endTimeInput.value = '23:59';

                switch (preset) {
                    case 'today':
                        // Already set to today
                        break;
                    case 'yesterday':
                        startDate.setDate(today.getDate() - 1);
                        endDate.setDate(today.getDate() - 1);
                        break;
                    case 'last7days':
                        startDate.setDate(today.getDate() - 6); // Today is day 7
                        break;
                    case 'last15days':
                        startDate.setDate(today.getDate() - 14); // Today is day 15
                        break;
                    case 'last30days':
                        startDate.setDate(today.getDate() - 29); // Today is day 30
                        break;
                }

                startDateInput.value = startDate.toISOString().split('T')[0];
                endDateInput.value = endDate.toISOString().split('T')[0];

                // Submit the form after setting values
                form.submit();
            });
        });
    });
    </script>
    <?php
}

// Track page visits on every page load
function track_page_visit() {
    if (!is_admin()) {
        $tracking_data = track_enhanced_device_info();
        // Track initial page view as an event
        global $wpdb;
        $events_table = $wpdb->prefix . 'device_events';
        $current_page_url = home_url(add_query_arg(NULL, NULL)); // Get current URL without query params for cleaner name
        $page_name = 'Page View: ' . basename(parse_url($current_page_url, PHP_URL_PATH));
        if (empty(basename(parse_url($current_page_url, PHP_URL_PATH)))) {
            $page_name = 'Page View: Home';
        } else if (is_singular('course_faq')) {
            $page_name = 'Page View: FAQ - ' . get_the_title();
        } else if (is_singular('course_review')) {
            $page_name = 'Page View: Review - ' . get_the_title();
        } else if (is_page('order-success')) { // Assuming 'order-success' is a page slug
            $page_name = 'Page View: Order Success';
        }

        $wpdb->insert(
            $events_table,
            array(
                'session_id' => $tracking_data['session_id'],
                'event_type' => 'page_view',
                'event_name' => $page_name,
                'event_value' => $current_page_url,
                'timestamp' => current_time('mysql')
            )
        );
    }
}
add_action('wp', 'track_page_visit');

// Add body class based on customizer setting
function alhadiya_body_classes( $classes ) {
    if ( ! get_theme_mod('show_payment_text', true) ) {
        $classes[] = 'hide-payment-text';
    }
    return $classes;
}
add_filter( 'body_class', 'alhadiya_body_classes' );

// Custom Walker for Bootstrap 5 Nav Menu (if needed, otherwise remove)
// This is a placeholder. You would typically include a separate file for this.
if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
            $output .= "{$n}{$indent}<ul class=\"dropdown-menu\">{$n}";
        }

        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = 'id="menu-item-' . $item->ID . '"';

            $output .= $indent . '<li' . $id . $class_names . '>';

            $atts = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
            $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }
}
