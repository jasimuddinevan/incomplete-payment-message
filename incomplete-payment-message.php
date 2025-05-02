<?php
/**
 * Plugin Name: Incomplete Payment Message
 * Plugin URI: https://facebook.com/jasimuddinevan
 * Description: Displays a full-screen message when payments are incomplete
 * Version: 1.0.0
 * Author: Jasim Uddin
 * Author URI:  https://facebook.com/jasimuddinevan
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: incomplete-payment-message
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('IPM_VERSION', '1.0.0');
define('IPM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IPM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('IPM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include the main plugin class
require_once IPM_PLUGIN_DIR . 'includes/functions.php';

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'ipm_activate_plugin');
register_deactivation_hook(__FILE__, 'ipm_deactivate_plugin');

// Initialize the plugin
ipm_init_plugin();