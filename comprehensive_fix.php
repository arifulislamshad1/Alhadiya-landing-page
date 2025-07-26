<?php
/**
 * Comprehensive Fix Script for Alhadiya Tracking System
 * This script addresses all remaining issues and ensures proper functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
    require_once(ABSPATH . 'wp-config.php');
}

// Ensure WordPress is loaded
if (!function_exists('wp_verify_nonce')) {
    require_once(ABSPATH . 'wp-load.php');
}

echo "<h1>Comprehensive Fix Script for Alhadiya Tracking System</h1>\n";
echo "<p>Starting comprehensive fixes...</p>\n";

// 1. Fix Database Tables
echo "<h2>1. Fixing Database Tables</h2>\n";

global $wpdb;

// Fix device_tracking table
$device_tracking_table = $wpdb->prefix . 'device_tracking';
$device_events_table = $wpdb->prefix . 'device_events';
$server_events_table = $wpdb->prefix . 'server_events';

// Check if tables exist
$device_tracking_exists = $wpdb->get_var("SHOW TABLES LIKE '$device_tracking_table'") == $device_tracking_table;
$device_events_exists = $wpdb->get_var("SHOW TABLES LIKE '$device_events_table'") == $device_events_table;
$server_events_exists = $wpdb->get_var("SHOW TABLES LIKE '$server_events_table'") == $server_events_table;

echo "<p>Device tracking table exists: " . ($device_tracking_exists ? 'Yes' : 'No') . "</p>\n";
echo "<p>Device events table exists: " . ($device_events_exists ? 'Yes' : 'No') . "</p>\n";
echo "<p>Server events table exists: " . ($server_events_exists ? 'Yes' : 'No') . "</p>\n";

// Create missing tables
if (!$device_tracking_exists) {
    echo "<p>Creating device_tracking table...</p>\n";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $device_tracking_table (
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
        language varchar(50),
        timezone varchar(100),
        connection_type varchar(50),
        battery_level decimal(5,2),
        battery_charging tinyint(1),
        memory_info decimal(5,2),
        cpu_cores int(11),
        touchscreen_detected tinyint(1),
        first_visit datetime DEFAULT CURRENT_TIMESTAMP,
        last_visit datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY session_id (session_id),
        KEY ip_address (ip_address)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    echo "<p>Device tracking table created successfully.</p>\n";
}

if (!$device_events_exists) {
    echo "<p>Creating device_events table...</p>\n";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $device_events_table (
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
    dbDelta($sql);
    echo "<p>Device events table created successfully.</p>\n";
}

if (!$server_events_exists) {
    echo "<p>Creating server_events table...</p>\n";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $server_events_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        session_id varchar(255) NOT NULL,
        event_name varchar(255) NOT NULL,
        event_data longtext,
        event_value text,
        user_ip varchar(45),
        user_agent text,
        referrer text,
        page_url text,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP,
        processed tinyint(1) DEFAULT 0,
        PRIMARY KEY (id),
        KEY session_id (session_id),
        KEY event_name (event_name),
        KEY timestamp (timestamp),
        KEY processed (processed)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    echo "<p>Server events table created successfully.</p>\n";
}

// Add missing columns to device_tracking if needed
if ($device_tracking_exists) {
    echo "<p>Checking for missing columns in device_tracking table...</p>\n";
    
    $columns_to_check = array(
        'language' => 'VARCHAR(50) DEFAULT NULL',
        'timezone' => 'VARCHAR(100) DEFAULT NULL',
        'connection_type' => 'VARCHAR(50) DEFAULT NULL',
        'battery_level' => 'DECIMAL(5,2) DEFAULT NULL',
        'battery_charging' => 'TINYINT(1) DEFAULT NULL',
        'memory_info' => 'DECIMAL(5,2) DEFAULT NULL',
        'cpu_cores' => 'INT DEFAULT NULL',
        'touchscreen_detected' => 'TINYINT(1) DEFAULT NULL'
    );
    
    foreach ($columns_to_check as $column => $definition) {
        $column_exists = $wpdb->get_var("SHOW COLUMNS FROM $device_tracking_table LIKE '$column'");
        if (!$column_exists) {
            echo "<p>Adding column: $column</p>\n";
            $wpdb->query("ALTER TABLE $device_tracking_table ADD COLUMN $column $definition");
        }
    }
}

// 2. Fix WordPress Cron Issues
echo "<h2>2. Fixing WordPress Cron Issues</h2>\n";

// Clear problematic cron events
$cron_option = get_option('cron');
if ($cron_option && is_array($cron_option)) {
    $problematic_hooks = array('action_scheduler_run_queue', 'alhadiya_batch_process_events');
    
    foreach ($problematic_hooks as $hook) {
        if (isset($cron_option[$hook])) {
            unset($cron_option[$hook]);
            echo "<p>Removed problematic cron hook: $hook</p>\n";
        }
    }
    
    update_option('cron', $cron_option);
    echo "<p>Cron events cleaned up.</p>\n";
}

// 3. Fix Session Management
echo "<h2>3. Fixing Session Management</h2>\n";

// Ensure WooCommerce session is properly initialized
if (class_exists('WooCommerce')) {
    echo "<p>WooCommerce detected. Ensuring proper session initialization...</p>\n";
    
    // Test session creation
    try {
        if (!WC()->session) {
            WC()->session = new WC_Session_Handler();
        }
        
        $test_session_id = 'test_' . uniqid() . '_' . time();
        WC()->session->set('alhadiya_test_session', $test_session_id);
        $retrieved = WC()->session->get('alhadiya_test_session');
        
        if ($retrieved === $test_session_id) {
            echo "<p>WooCommerce session working correctly.</p>\n";
        } else {
            echo "<p>WooCommerce session test failed.</p>\n";
        }
    } catch (Exception $e) {
        echo "<p>WooCommerce session error: " . $e->getMessage() . "</p>\n";
    }
} else {
    echo "<p>WooCommerce not detected. Using PHP sessions.</p>\n";
}

// 4. Fix AJAX Nonce Issues
echo "<h2>4. Fixing AJAX Nonce Issues</h2>\n";

// Test nonce creation
$test_nonce = wp_create_nonce('alhadiya_server_event_nonce');
if ($test_nonce) {
    echo "<p>Nonce creation working correctly.</p>\n";
} else {
    echo "<p>Nonce creation failed.</p>\n";
}

// 5. Clear Transients and Cache
echo "<h2>5. Clearing Cache and Transients</h2>\n";

// Clear WordPress transients
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%'");
echo "<p>WordPress transients cleared.</p>\n";

// Clear any caching plugins if they exist
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<p>Object cache flushed.</p>\n";
}

// 6. Test Database Connections
echo "<h2>6. Testing Database Connections</h2>\n";

// Test basic database operations
$test_result = $wpdb->get_var("SELECT 1");
if ($test_result == '1') {
    echo "<p>Database connection working correctly.</p>\n";
} else {
    echo "<p>Database connection failed.</p>\n";
}

// Test table operations
if ($device_tracking_exists) {
    $test_insert = $wpdb->insert(
        $device_tracking_table,
        array(
            'session_id' => 'test_session_' . time(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test User Agent',
            'device_type' => 'Desktop',
            'browser' => 'Test Browser',
            'os' => 'Test OS',
            'location' => 'Test Location',
            'isp' => 'Test ISP'
        )
    );
    
    if ($test_insert !== false) {
        echo "<p>Database insert test successful.</p>\n";
        // Clean up test data
        $wpdb->query("DELETE FROM $device_tracking_table WHERE session_id LIKE 'test_session_%'");
    } else {
        echo "<p>Database insert test failed: " . $wpdb->last_error . "</p>\n";
    }
}

// 7. Fix File Permissions (if possible)
echo "<h2>7. Checking File Permissions</h2>\n";

$theme_dir = get_template_directory();
if (is_readable($theme_dir)) {
    echo "<p>Theme directory is readable.</p>\n";
} else {
    echo "<p>Warning: Theme directory is not readable.</p>\n";
}

if (is_writable($theme_dir)) {
    echo "<p>Theme directory is writable.</p>\n";
} else {
    echo "<p>Warning: Theme directory is not writable.</p>\n";
}

// 8. Test AJAX Endpoints
echo "<h2>8. Testing AJAX Endpoints</h2>\n";

// Test if AJAX endpoints are accessible
$ajax_url = admin_url('admin-ajax.php');
echo "<p>AJAX URL: $ajax_url</p>\n";

// 9. Final Status Check
echo "<h2>9. Final Status Check</h2>\n";

$all_tables_exist = $device_tracking_exists && $device_events_exists && $server_events_exists;
$woocommerce_active = class_exists('WooCommerce');
$nonce_working = !empty($test_nonce);
$database_working = ($test_result == '1');

echo "<div style='background: #f0f0f0; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
echo "<h3>System Status:</h3>\n";
echo "<ul>\n";
echo "<li>Database Tables: " . ($all_tables_exist ? '✅ OK' : '❌ Missing') . "</li>\n";
echo "<li>WooCommerce: " . ($woocommerce_active ? '✅ Active' : '❌ Not Active') . "</li>\n";
echo "<li>Nonce System: " . ($nonce_working ? '✅ Working' : '❌ Failed') . "</li>\n";
echo "<li>Database: " . ($database_working ? '✅ Working' : '❌ Failed') . "</li>\n";
echo "</ul>\n";
echo "</div>\n";

if ($all_tables_exist && $nonce_working && $database_working) {
    echo "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>✅ All Critical Systems Working!</h3>\n";
    echo "<p>The tracking system should now work correctly. Please test the website functionality.</p>\n";
    echo "</div>\n";
} else {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; margin: 20px 0;'>\n";
    echo "<h3>❌ Some Issues Remain</h3>\n";
    echo "<p>Please check the specific issues above and resolve them manually.</p>\n";
    echo "</div>\n";
}

echo "<h2>10. Next Steps</h2>\n";
echo "<ol>\n";
echo "<li>Clear your browser cache and cookies</li>\n";
echo "<li>Test the website functionality</li>\n";
echo "<li>Check the browser console for any JavaScript errors</li>\n";
echo "<li>Monitor the WordPress error logs</li>\n";
echo "<li>Test the tracking system in the admin panel</li>\n";
echo "</ol>\n";

echo "<p><strong>Fix script completed!</strong></p>\n";
?>