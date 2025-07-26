<?php
/**
 * Database Tables Fix Script
 * Run this script to ensure all tracking tables are properly created and updated
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // If not in WordPress context, define basic constants
    if (!defined('ABSPATH')) {
        define('ABSPATH', dirname(__FILE__) . '/');
    }
    if (!defined('DB_NAME')) {
        // You'll need to set these manually if running outside WordPress
        echo "Please run this script from within WordPress or set database constants manually.\n";
        exit;
    }
}

// Include WordPress functions
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

// Function to create/update device tracking table
function fix_device_tracking_table() {
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
    
    dbDelta($sql);
    
    // Check if table was created successfully
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if ($table_exists) {
        echo "✓ Device tracking table created/updated successfully\n";
        
        // Check for missing columns and add them
        $columns_to_check = array(
            'language' => 'varchar(50)',
            'timezone' => 'varchar(100)',
            'connection_type' => 'varchar(50)',
            'battery_level' => 'decimal(5,2)',
            'battery_charging' => 'tinyint(1)',
            'memory_info' => 'decimal(5,2)',
            'cpu_cores' => 'int(11)',
            'touchscreen_detected' => 'tinyint(1)'
        );
        
        foreach ($columns_to_check as $column => $type) {
            $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE '$column'");
            if (empty($column_exists)) {
                $wpdb->query("ALTER TABLE $table_name ADD COLUMN $column $type");
                echo "  ✓ Added missing column: $column\n";
            }
        }
    } else {
        echo "✗ Failed to create device tracking table\n";
    }
}

// Function to create/update device events table
function fix_device_events_table() {
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
        KEY session_id (session_id),
        KEY event_type (event_type),
        KEY timestamp (timestamp)
    ) $charset_collate;";
    
    dbDelta($sql);
    
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if ($table_exists) {
        echo "✓ Device events table created/updated successfully\n";
    } else {
        echo "✗ Failed to create device events table\n";
    }
}

// Function to create/update server events table
function fix_server_events_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'server_events';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
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
    
    dbDelta($sql);
    
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if ($table_exists) {
        echo "✓ Server events table created/updated successfully\n";
    } else {
        echo "✗ Failed to create server events table\n";
    }
}

// Function to clear WordPress cron issues
function fix_cron_issues() {
    // Delete problematic cron option
    delete_option('cron');
    
    // Clear any stuck cron jobs
    wp_clear_scheduled_hook('action_scheduler_run_queue');
    wp_clear_scheduled_hook('alhadiya_batch_process_events');
    
    // Re-schedule our batch processing
    if (!wp_next_scheduled('alhadiya_batch_process_events')) {
        wp_schedule_event(time(), 'every_5_minutes', 'alhadiya_batch_process_events');
    }
    
    echo "✓ Cron issues fixed\n";
}

// Function to check and fix session handling
function fix_session_handling() {
    // Ensure session is started if needed
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Clear any problematic session data
    if (isset($_SESSION['alhadiya_session_id'])) {
        unset($_SESSION['alhadiya_session_id']);
    }
    
    echo "✓ Session handling fixed\n";
}

// Main execution
echo "Starting database tables fix...\n\n";

try {
    // Fix all tables
    fix_device_tracking_table();
    echo "\n";
    
    fix_device_events_table();
    echo "\n";
    
    fix_server_events_table();
    echo "\n";
    
    // Fix cron issues
    fix_cron_issues();
    echo "\n";
    
    // Fix session handling
    fix_session_handling();
    echo "\n";
    
    echo "✓ All fixes completed successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Clear your browser cache\n";
    echo "2. Test the tracking functionality\n";
    echo "3. Check the admin dashboard for tracking data\n";
    
} catch (Exception $e) {
    echo "✗ Error during fix: " . $e->getMessage() . "\n";
}

// If running from command line, add some spacing
if (php_sapi_name() === 'cli') {
    echo "\n";
}
?>