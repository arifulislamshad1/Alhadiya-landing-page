<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

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
        'screen_size_nonce' => wp_create_nonce('alhadiya_screen_size_nonce') // New nonce for screen size
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
        language varchar(50), -- New column
        timezone varchar(100), -- New column
        connection_type varchar(50), -- New column
        battery_level decimal(5,2), -- New column
        battery_charging tinyint(1), -- New column
        memory_info decimal(5,2), -- New column
        cpu_cores int(11), -- New column
        touchscreen_detected tinyint(1), -- New column
        first_visit datetime DEFAULT CURRENT_TIMESTAMP,
        last_visit datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_id (session_id),
        KEY ip_address (ip_address)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_device_tracking_table');

// New table for specific device events
function create_device_events_table() {
    global $wpdb;
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
        KEY session_id (session_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_device_events_table');


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
    // Check if device tracking is enabled
    if (!get_theme_mod('enable_device_tracking', true)) {
        return false;
    }
    
    global $wpdb;
    
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
    $ip = get_client_ip();
    $referrer = isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field($_SERVER['HTTP_REFERER']) : '';
    
    // Generate or get session ID
    if (!isset($_COOKIE['device_session'])) {
        $session_id = uniqid('session_', true);
        setcookie('device_session', $session_id, time() + (86400 * 30), '/', COOKIE_DOMAIN, is_ssl(), true); // 30 days, secure, httponly
    } else {
        $session_id = sanitize_text_field($_COOKIE['device_session']);
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
    
    // Screen size and new device info will be updated via AJAX from client-side
    $screen_size = $existing ? $existing->screen_size : '';
    $language = $existing ? $existing->language : '';
    $timezone = $existing ? $existing->timezone : '';
    $connection_type = $existing ? $existing->connection_type : '';
    $battery_level = $existing ? $existing->battery_level : null;
    $battery_charging = $existing ? $existing->battery_charging : null;
    $memory_info = $existing ? $existing->memory_info : null;
    $cpu_cores = $existing ? $existing->cpu_cores : null;
    $touchscreen_detected = $existing ? $existing->touchscreen_detected : null;

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
        // Insert new record
        $wpdb->insert(
            $table_name,
            array(
                'session_id' => $session_id,
                'ip_address' => $ip,
                'user_agent' => $user_agent,
                'device_type' => $device_info['device_type'],
                'device_model' => $device_info['device_model'],
                'browser' => $device_info['browser'],
                'os' => $device_info['os'],
                'location' => $location_data['location'],
                'isp' => $location_data['isp'],
                'referrer' => $referrer,
                'facebook_id' => $facebook_id, // Will be empty string
                'screen_size' => $screen_size, // Initial empty, updated by AJAX
                'language' => $language,
                'timezone' => $timezone,
                'connection_type' => $connection_type,
                'battery_level' => $battery_level,
                'battery_charging' => $battery_charging,
                'memory_info' => $memory_info,
                'cpu_cores' => $cpu_cores,
                'touchscreen_detected' => $touchscreen_detected,
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
    $device_type = 'Desktop';
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
    }
    
    // Detect browser
    if (preg_match('/Chrome/i', $user_agent)) {
        $browser = 'Chrome';
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/Safari/i', $user_agent)) {
        $browser = 'Safari';
    } elseif (preg_match('/Edge/i', $user_agent)) {
        $browser = 'Edge';
    }
    
    // Detect OS for desktop
    if ($device_type == 'Desktop') {
        if (preg_match('/Windows NT ([0-9.]+)/i', $user_agent, $matches)) {
            $os = 'Windows ' . $matches[1];
        } elseif (preg_match('/Mac OS X ([0-9_]+)/i', $user_agent, $matches)) {
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
    // Check if time spent tracking is enabled
    if (!get_theme_mod('enable_time_spent_tracking', true)) {
        wp_send_json_error('Time spent tracking is disabled');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_device_info_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';

    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
    $time_spent = isset($_POST['time_spent']) ? intval($_POST['time_spent']) : 0;

    if (!empty($session_id) && $time_spent > 0) {
        $wpdb->query($wpdb->prepare(
            "UPDATE $table_name SET time_spent = time_spent + %d WHERE session_id = %s",
            $time_spent,
            $session_id
        ));
        wp_send_json_success('Time spent updated successfully');
    } else {
        wp_send_json_error('Invalid data provided');
    }
}

// AJAX handler for updating time spent
function update_time_spent() {
    // Check if time spent tracking is enabled
    if (!get_theme_mod('enable_time_spent_tracking', true)) {
        return;
    }
    
    if (!is_admin()) {
        $session_id = isset($_COOKIE['device_session']) ? sanitize_text_field($_COOKIE['device_session']) : '';
        if (!empty($session_id)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'device_tracking';
            
            $wpdb->query($wpdb->prepare(
                "UPDATE $table_name SET time_spent = time_spent + 1 WHERE session_id = %s",
                $session_id
            ));
        }
    }
}
add_action('wp_ajax_update_time_spent', 'update_time_spent');
add_action('wp_ajax_nopriv_update_time_spent', 'update_time_spent');

// AJAX handler for tracking custom events
function track_custom_event() {
    // Check if custom events tracking is enabled
    if (!get_theme_mod('enable_custom_events_tracking', true)) {
        wp_send_json_error('Custom events tracking is disabled');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_event_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_events';

    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
    $event_type = isset($_POST['event_type']) ? sanitize_text_field($_POST['event_type']) : '';
    $event_name = isset($_POST['event_name']) ? sanitize_text_field($_POST['event_name']) : '';
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
    // Check if device details tracking is enabled
    if (!get_theme_mod('enable_device_details_tracking', true)) {
        wp_send_json_error('Device details tracking is disabled');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_screen_size_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';

    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
    $screen_size = isset($_POST['screen_size']) ? sanitize_text_field($_POST['screen_size']) : '';

    if (empty($session_id) || empty($screen_size)) {
        wp_send_json_error('Missing session_id or screen_size');
        return;
    }

    $result = $wpdb->update(
        $table_name,
        array('screen_size' => $screen_size),
        array('session_id' => $session_id)
    );

    if ($result === false) {
        wp_send_json_error('Database update failed: ' . $wpdb->last_error);
    } else {
        wp_send_json_success('Screen size updated successfully');
    }
}
add_action('wp_ajax_update_device_screen_size', 'update_device_screen_size');
add_action('wp_ajax_nopriv_update_device_screen_size', 'update_device_screen_size');

// NEW AJAX handler for updating client-side device details
function update_client_device_details() {
    // Check if device details tracking is enabled
    if (!get_theme_mod('enable_device_details_tracking', true)) {
        wp_send_json_error('Device details tracking is disabled');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'alhadiya_device_info_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'device_tracking';

    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
    $data_to_update = array();

    if (isset($_POST['language']) && $_POST['language'] !== 'N/A') $data_to_update['language'] = sanitize_text_field($_POST['language']);
    if (isset($_POST['timezone']) && $_POST['timezone'] !== 'N/A') $data_to_update['timezone'] = sanitize_text_field($_POST['timezone']);
    if (isset($_POST['connection_type']) && $_POST['connection_type'] !== 'N/A') $data_to_update['connection_type'] = sanitize_text_field($_POST['connection_type']);
    if (isset($_POST['battery_level']) && $_POST['battery_level'] !== 'N/A' && $_POST['battery_level'] !== null) $data_to_update['battery_level'] = floatval($_POST['battery_level']);
    if (isset($_POST['battery_charging']) && $_POST['battery_charging'] !== 'N/A' && $_POST['battery_charging'] !== null) $data_to_update['battery_charging'] = intval($_POST['battery_charging']);
    if (isset($_POST['memory_info']) && $_POST['memory_info'] !== 'N/A') $data_to_update['memory_info'] = floatval($_POST['memory_info']);
    if (isset($_POST['cpu_cores']) && $_POST['cpu_cores'] !== 'N/A') $data_to_update['cpu_cores'] = intval($_POST['cpu_cores']);
    if (isset($_POST['touchscreen_detected']) && $_POST['touchscreen_detected'] !== 'N/A') $data_to_update['touchscreen_detected'] = intval($_POST['touchscreen_detected']);

    if (!empty($data_to_update) && !empty($session_id)) {
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
        wp_send_json_error('No data to update or missing session_id');
    }
}
add_action('wp_ajax_update_client_device_details', 'update_client_device_details');
add_action('wp_ajax_nopriv_update_client_device_details', 'update_client_device_details');


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
    $submission_key = 'order_submission_' . md5($user_ip . (isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : '') . date('Y-m-d-H'));
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
    
    // Cookie Consent Settings
    $wp_customize->add_section('cookie_consent_settings', array(
        'title' => __('Cookie & Tracking Settings', 'alhadiya'),
        'priority' => 35,
    ));
    
    // Enable/Disable Cookie Consent
    $wp_customize->add_setting('enable_cookie_consent', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_cookie_consent', array(
        'label' => __('Enable Cookie Consent Banner', 'alhadiya'),
        'description' => __('Show cookie consent banner to users (not required for Bangladesh)', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Device Tracking Settings
    $wp_customize->add_setting('enable_device_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_device_tracking', array(
        'label' => __('Enable Device Tracking', 'alhadiya'),
        'description' => __('Track device information and analytics', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Page Visit Tracking
    $wp_customize->add_setting('enable_page_visit_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_page_visit_tracking', array(
        'label' => __('Enable Page Visit Tracking', 'alhadiya'),
        'description' => __('Track which pages users visit', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Time Spent Tracking
    $wp_customize->add_setting('enable_time_spent_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_time_spent_tracking', array(
        'label' => __('Enable Time Spent Tracking', 'alhadiya'),
        'description' => __('Track how long users spend on the site', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Custom Events Tracking
    $wp_customize->add_setting('enable_custom_events_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_custom_events_tracking', array(
        'label' => __('Enable Custom Events Tracking', 'alhadiya'),
        'description' => __('Track button clicks, form submissions, etc.', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Video Tracking
    $wp_customize->add_setting('enable_video_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_video_tracking', array(
        'label' => __('Enable Video Tracking', 'alhadiya'),
        'description' => __('Track YouTube video interactions', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
    ));
    
    // Device Details Tracking
    $wp_customize->add_setting('enable_device_details_tracking', array(
        'default' => true,
        'sanitize_callback' => 'sanitize_checkbox',
    ));
    
    $wp_customize->add_control('enable_device_details_tracking', array(
        'label' => __('Enable Device Details Tracking', 'alhadiya'),
        'description' => __('Track screen size, battery, memory, etc.', 'alhadiya'),
        'section' => 'cookie_consent_settings',
        'type' => 'checkbox',
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
    
    // Get all tracking data
    $tracking_data = $wpdb->get_results("SELECT * FROM $tracking_table ORDER BY last_visit DESC LIMIT 100");
    
    ?>
    <div class="wrap">
        <h1>Enhanced Device Tracking</h1>
        <div class="tablenav top">
            <div class="alignleft actions">
                <button type="button" class="button" onclick="location.reload()">Refresh</button>
            </div>
        </div>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>IP Address</th>
                    <th>Device Info</th>
                    <th>Location & ISP</th>
                    <th>Referrer</th>
                    <th>Facebook ID</th>
                    <th>Visit Count</th>
                    <th>Time Spent</th>
                    <th>Pages Viewed</th>
                    <th>Purchased</th>
                    <th>Customer Info</th>
                    <th>Client Device Details</th> <!-- New column header -->
                    <th>Events Log</th>
                    <th>First Visit</th>
                    <th>Last Visit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tracking_data as $data): ?>
                <tr>
                    <td><small><?php echo substr($data->session_id, 0, 15); ?>...</small></td>
                    <td><?php echo $data->ip_address; ?></td>
                    <td>
                        <small>
                            <strong>Type:</strong> <?php echo $data->device_type; ?><br>
                            <strong>Model:</strong> <?php echo $data->device_model; ?><br>
                            <strong>Browser:</strong> <?php echo $data->browser; ?><br>
                            <strong>OS:</strong> <?php echo $data->os; ?>
                        </small>
                    </td>
                    <td>
                        <small>
                            <strong>Location:</strong> <?php echo $data->location; ?><br>
                            <strong>ISP:</strong> <?php echo $data->isp; ?>
                        </small>
                    </td>
                    <td>
                        <small>
                            <?php 
                            if ($data->referrer) {
                                $domain = parse_url($data->referrer, PHP_URL_HOST);
                                echo $domain ? $domain : 'Direct';
                            } else {
                                echo 'Direct';
                            }
                            ?>
                        </small>
                    </td>
                    <td><?php echo $data->facebook_id ? $data->facebook_id : 'N/A'; ?></td>
                    <td><span class="badge"><?php echo $data->visit_count; ?></span></td>
                    <td>
                        <?php 
                        $minutes = floor($data->time_spent / 60);
                        $seconds = $data->time_spent % 60;
                        echo $minutes . 'm ' . $seconds . 's';
                        ?>
                    </td>
                    <td><span class="badge"><?php echo $data->pages_viewed; ?></span></td>
                    <td>
                        <?php if ($data->has_purchased): ?>
                            <span style="color: green;">✓ Yes</span>
                        <?php else: ?>
                            <span style="color: red;">✗ No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($data->has_purchased): ?>
                            <small>
                                <strong>Name:</strong> <?php echo esc_html($data->customer_name); ?><br>
                                <strong>Phone:</strong> <?php echo esc_html($data->customer_phone); ?><br>
                                <strong>Address:</strong> <?php echo esc_html($data->customer_address); ?>
                            </small>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <small>
                            <strong>Screen:</strong> <?php echo $data->screen_size ? esc_html($data->screen_size) : 'N/A'; ?><br>
                            <strong>Lang:</strong> <?php echo $data->language ? esc_html($data->language) : 'N/A'; ?><br>
                            <strong>TZ:</strong> <?php echo $data->timezone ? esc_html($data->timezone) : 'N/A'; ?><br>
                            <strong>Conn:</strong> <?php echo $data->connection_type ? esc_html($data->connection_type) : 'N/A'; ?><br>
                            <strong>Batt:</strong> <?php echo $data->battery_level !== null ? (esc_html($data->battery_level * 100) . '% ' . ($data->battery_charging ? '(Charging)' : '(Discharging)')) : 'N/A'; ?><br>
                            <strong>Mem:</strong> <?php echo $data->memory_info !== null ? (esc_html($data->memory_info) . 'GB') : 'N/A'; ?><br>
                            <strong>CPU:</strong> <?php echo $data->cpu_cores !== null ? esc_html($data->cpu_cores) : 'N/A'; ?><br>
                            <strong>Touch:</strong> <?php echo $data->touchscreen_detected !== null ? ($data->touchscreen_detected ? 'Yes' : 'No') : 'N/A'; ?>
                        </small>
                    </td>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=device-session-details&session_id=' . $data->session_id)); ?>" class="events-log-link">View Events</a>
                    </td>
                    <td><small><?php echo date('M j, Y H:i', strtotime($data->first_visit)); ?></small></td>
                    <td><small><?php echo date('M j, Y H:i', strtotime($data->last_visit)); ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
                <p><strong>Screen Size:</strong> <?php echo $session_data->screen_size ? esc_html($session_data->screen_size) : 'N/A'; ?></p>
                <p><strong>Language:</strong> <?php echo $session_data->language ? esc_html($session_data->language) : 'N/A'; ?></p>
                <p><strong>Timezone:</strong> <?php echo $session_data->timezone ? esc_html($session_data->timezone) : 'N/A'; ?></p>
                <p><strong>Connection Type:</strong> <?php echo $session_data->connection_type ? esc_html($session_data->connection_type) : 'N/A'; ?></p>
                <p><strong>Battery Level:</strong> <?php echo $session_data->battery_level !== null ? (esc_html($session_data->battery_level * 100) . '%') : 'N/A'; ?></p>
                <p><strong>Battery Charging:</strong> <?php echo $session_data->battery_charging !== null ? ($session_data->battery_charging ? 'Yes' : 'No') : 'N/A'; ?></p>
                <p><strong>Memory Info:</strong> <?php echo $session_data->memory_info !== null ? (esc_html($session_data->memory_info) . 'GB') : 'N/A'; ?></p>
                <p><strong>CPU Cores:</strong> <?php echo $session_data->cpu_cores !== null ? esc_html($session_data->cpu_cores) : 'N/A'; ?></p>
                <p><strong>Touchscreen Detected:</strong> <?php echo $session_data->touchscreen_detected !== null ? ($session_data->touchscreen_detected ? 'Yes' : 'No') : 'N/A'; ?></p>
                <p><strong>First Visit:</strong> <?php echo date('M j, Y H:i:s', strtotime($session_data->first_visit)); ?></p>
                <p><strong>Last Visit:</strong> <?php echo date('M j, Y H:i:s', strtotime($session_data->last_visit)); ?></p>
            </div>
        <?php else: ?>
            <p>No session data found for the provided ID.</p>
        <?php endif; ?>

        <div class="event-log-table-container">
            <h2>Event Log</h2>
            <form method="GET" class="event-filter-form" id="event-filter-form">
                <input type="hidden" name="page" value="device-session-details">
                <input type="hidden" name="session_id" value="<?php echo esc_attr($session_id); ?>">
                
                <div class="filter-group">
                    <label for="filter_start_date">Start Date:</label>
                    <input type="date" name="filter_start_date" id="filter_start_date" value="<?php echo esc_attr($filter_start_date); ?>">
                    <label for="filter_start_time">Time:</label>
                    <input type="time" name="filter_start_time" id="filter_start_time" value="<?php echo esc_attr($filter_start_time); ?>">
                </div>

                <div class="filter-group">
                    <label for="filter_end_date">End Date:</label>
                    <input type="date" name="filter_end_date" id="filter_end_date" value="<?php echo esc_attr($filter_end_date); ?>">
                    <label for="filter_end_time">Time:</label>
                    <input type="time" name="filter_end_time" id="filter_end_time" value="<?php echo esc_attr($filter_end_time); ?>">
                </div>

                <div class="filter-group preset-buttons">
                    <button type="button" class="button" data-preset="today">Today</button>
                    <button type="button" class="button" data-preset="yesterday">Yesterday</button>
                    <button type="button" class="button" data-preset="last7days">Last 7 Days</button>
                    <button type="button" class="button" data-preset="last15days">Last 15 Days</button>
                    <button type="button" class="button" data-preset="last30days">Last 30 Days</button>
                </div>

                <div class="filter-group event-type-filters">
                    <label>Event Types:</label>
                    <?php foreach ($all_event_types as $type_slug => $type_label): ?>
                        <label class="event-type-checkbox">
                            <input type="checkbox" name="filter_event_types[]" value="<?php echo esc_attr($type_slug); ?>" <?php checked(in_array($type_slug, $filter_event_types), true); ?>>
                            <?php echo esc_html($type_label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                
                <input type="submit" class="button button-primary" value="Filter Events">
                <a href="<?php echo esc_url(admin_url('admin.php?page=device-session-details&session_id=' . $session_id)); ?>" class="button">Reset Filter</a>
            </form>

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
        const presetButtons = document.querySelectorAll('.preset-buttons .button');

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
    // Check if page visit tracking is enabled
    if (!get_theme_mod('enable_page_visit_tracking', true)) {
        return;
    }
    
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
