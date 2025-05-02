<?php
/**
 * Public functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize public functionality
 */
function ipm_init_public() {
    $settings = get_option('ipm_settings');
    
    if (isset($settings['message_enabled']) && $settings['message_enabled']) {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', 'ipm_public_enqueue_scripts');
        
        // Display the message
        add_action('wp_footer', 'ipm_display_message');
    }
}

/**
 * Enqueue public scripts and styles
 */
function ipm_public_enqueue_scripts() {
    wp_enqueue_style(
        'ipm-public-css',
        IPM_PLUGIN_URL . 'assets/css/public.css',
        array(),
        IPM_VERSION
    );
    
    wp_enqueue_script(
        'ipm-public-js',
        IPM_PLUGIN_URL . 'assets/js/public.js',
        array('jquery'),
        IPM_VERSION,
        true
    );
    
    // Localize script with settings
    $settings = get_option('ipm_settings');
    wp_localize_script('ipm-public-js', 'ipm_settings', array(
        'popup_delay' => isset($settings['popup_delay']) ? $settings['popup_delay'] * 1000 : 120000,
        'min_display_time' => isset($settings['min_display_time']) ? $settings['min_display_time'] * 1000 : 20000
    ));
}

/**
 * Display the message
 */
function ipm_display_message() {
    $settings = get_option('ipm_settings');
    $message_content = isset($settings['message_content']) ? $settings['message_content'] : '';
    
    ?>
    <div id="ipm-message-overlay" class="ipm-hidden">
        <div id="ipm-message-container">
            <div id="ipm-message-content">
                <?php echo wp_kses_post(wpautop($message_content)); ?>
            </div>
            <div id="ipm-message-footer">
                <div id="ipm-countdown"></div>
                <button id="ipm-close-btn" disabled>Close (20s)</button>
            </div>
        </div>
    </div>
    <?php
}