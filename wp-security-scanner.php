<?php
/**
 * WordPress Silent Security Scanner
 * 
 * Silently scans key WordPress files for unauthorized code injections
 * without affecting UI or performance. Uses transient locking for
 * high-traffic optimization.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Silent WordPress Security Scanner
 * 
 * Scans critical WordPress files for suspicious code patterns
 * Uses transient locking to prevent excessive execution
 * Logs findings safely without UI interference
 */
function wp_silent_security_scan() {
    // Transient lock - run only once every 10 minutes
    if ( get_transient( 'wp_security_scan_lock' ) ) {
        return;
    }
    
    // Set lock for 10 minutes (600 seconds)
    set_transient( 'wp_security_scan_lock', 1, 600 );
    
    // Define critical files to scan
    $critical_files = array(
        ABSPATH . 'wp-config.php',
        ABSPATH . 'wp-load.php',
        ABSPATH . 'index.php',
        get_template_directory() . '/functions.php',
        get_template_directory() . '/index.php',
    );
    
    // Add child theme functions.php if exists
    if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/functions.php' ) ) {
        $critical_files[] = get_stylesheet_directory() . '/functions.php';
    }
    
    // Suspicious code patterns
    $suspicious_patterns = array(
        '/eval\s*\(/i',
        '/base64_decode\s*\(/i',
        '/gzinflate\s*\(/i',
        '/gzuncompress\s*\(/i',
        '/str_rot13\s*\(/i',
        '/shell_exec\s*\(/i',
        '/exec\s*\(/i',
        '/system\s*\(/i',
        '/passthru\s*\(/i',
        '/file_get_contents\s*\(\s*["\']https?:\/\//i',
        '/curl_exec\s*\(/i',
        '/\$_(?:GET|POST|REQUEST|COOKIE)\s*\[\s*["\'][^"\']*["\']\s*\]\s*\(/i',
        '/preg_replace\s*\(\s*["\'].*?e["\']/i',
        '/assert\s*\(/i',
        '/create_function\s*\(/i',
        '/<\?php\s+\/\*.*?\*\/\s*\$[a-zA-Z_]/s',
        '/\$[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*["\'][A-Za-z0-9+\/]{50,}={0,2}["\'];/i',
    );
    
    $scan_results = array();
    $suspicious_found = false;
    
    foreach ( $critical_files as $file_path ) {
        $file_result = wp_scan_single_file( $file_path, $suspicious_patterns );
        if ( ! empty( $file_result ) ) {
            $scan_results[ $file_path ] = $file_result;
            $suspicious_found = true;
        }
    }
    
    // Log results if suspicious content found
    if ( $suspicious_found ) {
        wp_log_security_findings( $scan_results );
    }
    
    // Update last scan timestamp
    update_option( 'wp_security_last_scan', current_time( 'timestamp' ), false );
}

/**
 * Scan a single file for suspicious patterns
 * 
 * @param string $file_path Path to file to scan
 * @param array  $patterns Array of regex patterns to check
 * @return array Array of findings or empty array
 */
function wp_scan_single_file( $file_path, $patterns ) {
    // Null-safe file existence check
    if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
        return array();
    }
    
    // Get file content with error handling
    $content = @file_get_contents( $file_path, false, null, 0, 1048576 ); // Limit to 1MB
    if ( false === $content ) {
        return array();
    }
    
    $findings = array();
    
    // Check each suspicious pattern
    foreach ( $patterns as $pattern ) {
        if ( @preg_match( $pattern, $content, $matches ) ) {
            $findings[] = array(
                'pattern' => $pattern,
                'match' => isset( $matches[0] ) ? substr( $matches[0], 0, 100 ) : 'Pattern matched',
                'found_at' => current_time( 'mysql' ),
            );
        }
    }
    
    return $findings;
}

/**
 * Safely log security findings
 * 
 * @param array $findings Array of security findings
 */
function wp_log_security_findings( $findings ) {
    if ( empty( $findings ) ) {
        return;
    }
    
    // Prepare log entry
    $log_entry = array(
        'timestamp' => current_time( 'mysql' ),
        'scan_time' => current_time( 'timestamp' ),
        'findings' => $findings,
        'server_info' => array(
            'php_version' => PHP_VERSION,
            'wp_version' => get_bloginfo( 'version' ),
            'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
        ),
    );
    
    // Get existing logs (limit to last 50 entries)
    $existing_logs = get_option( 'wp_security_scan_logs', array() );
    if ( ! is_array( $existing_logs ) ) {
        $existing_logs = array();
    }
    
    // Add new entry and limit array size
    array_unshift( $existing_logs, $log_entry );
    $existing_logs = array_slice( $existing_logs, 0, 50 );
    
    // Save logs with autoload disabled for performance
    update_option( 'wp_security_scan_logs', $existing_logs, false );
    
    // Update alert flag
    update_option( 'wp_security_alert_active', 1, false );
}

/**
 * Get security scan status (for admin use only)
 * 
 * @return array Status information
 */
function wp_get_security_scan_status() {
    return array(
        'last_scan' => get_option( 'wp_security_last_scan', 0 ),
        'alert_active' => get_option( 'wp_security_alert_active', 0 ),
        'scan_locked' => get_transient( 'wp_security_scan_lock' ) ? true : false,
        'logs_count' => count( get_option( 'wp_security_scan_logs', array() ) ),
    );
}

/**
 * Clear security alerts (admin function)
 */
function wp_clear_security_alerts() {
    delete_option( 'wp_security_alert_active' );
    delete_option( 'wp_security_scan_logs' );
    delete_transient( 'wp_security_scan_lock' );
}

/**
 * Initialize security scanner hooks
 */
function wp_init_security_scanner() {
    // Run scan on various WordPress hooks for coverage
    add_action( 'wp_loaded', 'wp_silent_security_scan', 999 );
    add_action( 'admin_init', 'wp_silent_security_scan', 999 );
    add_action( 'wp_footer', 'wp_silent_security_scan', 999 );
    
    // Run on plugin/theme activation
    add_action( 'activated_plugin', 'wp_silent_security_scan', 999 );
    add_action( 'switch_theme', 'wp_silent_security_scan', 999 );
    
    // Scheduled event fallback
    if ( ! wp_next_scheduled( 'wp_security_scan_cron' ) ) {
        wp_schedule_event( time(), 'hourly', 'wp_security_scan_cron' );
    }
    add_action( 'wp_security_scan_cron', 'wp_silent_security_scan' );
}

// Initialize the scanner
add_action( 'plugins_loaded', 'wp_init_security_scanner', 1 );

// Cleanup on deactivation
register_deactivation_hook( __FILE__, function() {
    wp_clear_scheduled_hook( 'wp_security_scan_cron' );
    delete_transient( 'wp_security_scan_lock' );
});