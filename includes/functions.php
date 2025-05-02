<?php
/**
 * Main plugin functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize the plugin
 */
function ipm_init_plugin() {
    // Load admin functionality
    if (is_admin()) {
        require_once IPM_PLUGIN_DIR . 'includes/admin/admin-page.php';
        ipm_init_admin();
    }

    // Load public functionality
    require_once IPM_PLUGIN_DIR . 'includes/public/public-display.php';
    ipm_init_public();
}

/**
 * Plugin activation
 */
function ipm_activate_plugin() {
    // Set default options
    $default_options = array(
        'message_enabled' => true,
        'message_content' => 'Website functions has been disabled due to incomplete payment',
        'popup_delay' => 120, // 2 minutes in seconds
        'min_display_time' => 20, // 20 seconds
        'password_hash' => password_hash('123', PASSWORD_BCRYPT)
    );

    add_option('ipm_settings', $default_options);
}

/**
 * Plugin deactivation
 */
function ipm_deactivate_plugin() {
    $settings = get_option('ipm_settings');
    
    // Only allow deactivation if message is disabled
    if (isset($settings['message_enabled']) && $settings['message_enabled']) {
        wp_die('You cannot deactivate this plugin while the message is enabled. Please disable the message first.');
    }
}