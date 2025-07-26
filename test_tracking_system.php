<?php
/**
 * Test Script for WordPress Tracking System
 * Run this file to verify all components are working correctly
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // If not in WordPress context, simulate basic environment
    define('ABSPATH', dirname(__FILE__) . '/');
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    
    // Load WordPress if possible
    if (file_exists(ABSPATH . 'wp-config.php')) {
        require_once(ABSPATH . 'wp-config.php');
    } else {
        echo "WordPress not found. Please run this script from your WordPress root directory.\n";
        exit;
    }
}

echo "=== WordPress Tracking System Test ===\n\n";

// Test 1: Check if functions exist
echo "1. Testing Function Existence:\n";
$functions_to_test = [
    'alhadiya_init_server_session',
    'alhadiya_log_server_event',
    'alhadiya_handle_server_event',
    'track_enhanced_device_info',
    'create_device_tracking_table',
    'create_device_events_table',
    'alhadiya_create_server_events_table'
];

foreach ($functions_to_test as $function) {
    if (function_exists($function)) {
        echo "   ✅ $function exists\n";
    } else {
        echo "   ❌ $function does not exist\n";
    }
}

// Test 2: Check database tables
echo "\n2. Testing Database Tables:\n";
global $wpdb;

$tables_to_check = [
    $wpdb->prefix . 'device_tracking',
    $wpdb->prefix . 'device_events',
    $wpdb->prefix . 'server_events'
];

foreach ($tables_to_check as $table) {
    $result = $wpdb->get_var("SHOW TABLES LIKE '$table'");
    if ($result) {
        echo "   ✅ Table $table exists\n";
        
        // Check table structure
        $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");
        echo "      Columns: " . count($columns) . "\n";
    } else {
        echo "   ❌ Table $table does not exist\n";
    }
}

// Test 3: Test session initialization
echo "\n3. Testing Session Initialization:\n";
try {
    $session_id = alhadiya_init_server_session();
    if ($session_id) {
        echo "   ✅ Session ID created: " . substr($session_id, 0, 20) . "...\n";
    } else {
        echo "   ❌ Failed to create session ID\n";
    }
} catch (Exception $e) {
    echo "   ❌ Session initialization error: " . $e->getMessage() . "\n";
}

// Test 4: Test event logging
echo "\n4. Testing Event Logging:\n";
try {
    $test_event = alhadiya_log_server_event('test_event', ['test' => 'data'], 'test_value');
    if ($test_event) {
        echo "   ✅ Event logged successfully\n";
        echo "      Session ID: " . substr($test_event['session_id'], 0, 20) . "...\n";
        echo "      Event Name: " . $test_event['event_name'] . "\n";
    } else {
        echo "   ❌ Failed to log event\n";
    }
} catch (Exception $e) {
    echo "   ❌ Event logging error: " . $e->getMessage() . "\n";
}

// Test 5: Check nonce creation
echo "\n5. Testing Nonce Creation:\n";
$nonce = wp_create_nonce('alhadiya_server_event_nonce');
if ($nonce) {
    echo "   ✅ Nonce created: " . substr($nonce, 0, 10) . "...\n";
} else {
    echo "   ❌ Failed to create nonce\n";
}

// Test 6: Check theme mods
echo "\n6. Testing Theme Mods:\n";
$tracking_enabled = get_theme_mod('enable_device_tracking', true);
$server_tracking_enabled = get_theme_mod('enable_server_tracking', true);

echo "   Device Tracking: " . ($tracking_enabled ? '✅ Enabled' : '❌ Disabled') . "\n";
echo "   Server Tracking: " . ($server_tracking_enabled ? '✅ Enabled' : '❌ Disabled') . "\n";

// Test 7: Check WooCommerce integration
echo "\n7. Testing WooCommerce Integration:\n";
if (class_exists('WooCommerce')) {
    echo "   ✅ WooCommerce is active\n";
    if (function_exists('WC')) {
        echo "   ✅ WC() function available\n";
    } else {
        echo "   ❌ WC() function not available\n";
    }
} else {
    echo "   ⚠️  WooCommerce not active (will use PHP sessions)\n";
}

// Test 8: Check for any PHP errors
echo "\n8. Checking for PHP Errors:\n";
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    $recent_errors = shell_exec("tail -n 10 $error_log 2>/dev/null");
    if ($recent_errors) {
        echo "   Recent errors found:\n";
        echo "   " . str_replace("\n", "\n   ", $recent_errors) . "\n";
    } else {
        echo "   ✅ No recent errors found\n";
    }
} else {
    echo "   ⚠️  Error log not accessible\n";
}

// Test 9: Check file permissions
echo "\n9. Testing File Permissions:\n";
$theme_dir = get_template_directory();
if (is_readable($theme_dir)) {
    echo "   ✅ Theme directory readable\n";
} else {
    echo "   ❌ Theme directory not readable\n";
}

if (is_writable($theme_dir)) {
    echo "   ✅ Theme directory writable\n";
} else {
    echo "   ⚠️  Theme directory not writable\n";
}

// Test 10: Check AJAX endpoints
echo "\n10. Testing AJAX Endpoints:\n";
$ajax_url = admin_url('admin-ajax.php');
if ($ajax_url) {
    echo "   ✅ AJAX URL: $ajax_url\n";
} else {
    echo "   ❌ AJAX URL not available\n";
}

echo "\n=== Test Complete ===\n";
echo "\nIf you see any ❌ errors above, please fix them before using the tracking system.\n";
echo "If all tests pass with ✅, your tracking system is ready to use!\n";

// Additional recommendations
echo "\n=== Recommendations ===\n";
echo "1. Test the frontend by visiting your website and checking browser console\n";
echo "2. Check the admin dashboard at: " . admin_url('admin.php?page=enhanced-device-tracking') . "\n";
echo "3. Monitor error logs for any issues\n";
echo "4. Test with different browsers and devices\n";
echo "5. Verify external API integrations (Facebook, GA4, etc.)\n";
?>