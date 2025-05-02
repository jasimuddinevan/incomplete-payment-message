<?php
/**
 * Admin functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize admin functionality
 */
function ipm_init_admin() {
    // Add admin menu
    add_action('admin_menu', 'ipm_add_admin_menu');
    
    // Add admin styles and scripts
    add_action('admin_enqueue_scripts', 'ipm_admin_enqueue_scripts');
    
    // Add plugin action links
    add_filter('plugin_action_links_' . IPM_PLUGIN_BASENAME, 'ipm_add_plugin_action_links');
    
    // Prevent deactivation when message is enabled
    add_filter('plugin_action_links', 'ipm_prevent_deactivation', 10, 4);
}

/**
 * Add admin menu
 */
function ipm_add_admin_menu() {
    add_menu_page(
        'Incomplete Payment Message',
        'Payment Message',
        'manage_options',
        'incomplete-payment-message',
        'ipm_render_admin_page',
        'dashicons-warning',
        80
    );
}

/**
 * Render admin page
 */
function ipm_render_admin_page() {
    $settings = get_option('ipm_settings');
    $message_enabled = isset($settings['message_enabled']) ? $settings['message_enabled'] : false;
    $message_content = isset($settings['message_content']) ? $settings['message_content'] : '';
    $popup_delay = isset($settings['popup_delay']) ? $settings['popup_delay'] : 120;
    $min_display_time = isset($settings['min_display_time']) ? $settings['min_display_time'] : 20;
    
    // Handle form submission
    if (isset($_POST['ipm_submit'])) {
        if (!isset($_POST['ipm_nonce']) || !wp_verify_nonce($_POST['ipm_nonce'], 'ipm_update_settings')) {
            wp_die('Security check failed');
        }
        
        // Verify password
        $password = isset($_POST['ipm_password']) ? sanitize_text_field($_POST['ipm_password']) : '';
        
        if (!password_verify($password, $settings['password_hash'])) {
            add_settings_error('ipm_messages', 'ipm_message', __('Incorrect password', 'incomplete-payment-message'), 'error');
        } else {
            $new_settings = array(
                'message_enabled' => isset($_POST['ipm_message_enabled']),
                'message_content' => isset($_POST['ipm_message_content']) ? wp_kses_post($_POST['ipm_message_content']) : '',
                'popup_delay' => isset($_POST['ipm_popup_delay']) ? absint($_POST['ipm_popup_delay']) : 120,
                'min_display_time' => isset($_POST['ipm_min_display_time']) ? absint($_POST['ipm_min_display_time']) : 20,
                'password_hash' => $settings['password_hash']
            );
            
            update_option('ipm_settings', $new_settings);
            $settings = $new_settings;
            $message_enabled = $settings['message_enabled'];
            $message_content = $settings['message_content'];
            $popup_delay = $settings['popup_delay'];
            $min_display_time = $settings['min_display_time'];
            
            add_settings_error('ipm_messages', 'ipm_message', __('Settings saved', 'incomplete-payment-message'), 'success');
        }
    }
    
    // Display the admin page
    ?>
    <div class="wrap ipm-admin-wrap">
        <h1>Incomplete Payment Message Settings</h1>
        
        <?php settings_errors('ipm_messages'); ?>
        
        <div class="ipm-admin-container">
            <form method="post" action="">
                <?php wp_nonce_field('ipm_update_settings', 'ipm_nonce'); ?>
                
                <div class="ipm-card">
                    <h2>Message Settings</h2>
                    
                    <div class="ipm-form-group">
                        <label for="ipm_message_enabled">
                            <input type="checkbox" id="ipm_message_enabled" name="ipm_message_enabled" value="1" <?php checked($message_enabled, true); ?>>
                            Enable Message
                        </label>
                    </div>
                    
                    <div class="ipm-form-group">
                        <label for="ipm_message_content">Message Content</label>
                        <?php
                        wp_editor(
                            $message_content,
                            'ipm_message_content',
                            array(
                                'textarea_name' => 'ipm_message_content',
                                'media_buttons' => false,
                                'textarea_rows' => 5,
                                'teeny' => true
                            )
                        );
                        ?>
                    </div>
                    
                    <div class="ipm-form-group">
                        <label for="ipm_popup_delay">Popup Delay (seconds)</label>
                        <input type="number" id="ipm_popup_delay" name="ipm_popup_delay" value="<?php echo esc_attr($popup_delay); ?>" min="10">
                    </div>
                    
                    <div class="ipm-form-group">
                        <label for="ipm_min_display_time">Minimum Display Time (seconds)</label>
                        <input type="number" id="ipm_min_display_time" name="ipm_min_display_time" value="<?php echo esc_attr($min_display_time); ?>" min="5">
                    </div>
                </div>
                
                <div class="ipm-card">
                    <h2>Security</h2>
                    <div class="ipm-form-group">
                        <label for="ipm_password">Password to change settings</label>
                        <input type="password" id="ipm_password" name="ipm_password" required>
                    </div>
                </div>
                
                <div class="ipm-submit-section">
                    <button type="submit" name="ipm_submit" class="button button-primary">Save Settings</button>
                </div>
            </form>
        </div>
        
        <div class="ipm-footer">
            <p>Developed by Your Name</p>
        </div>
    </div>
    <?php
}

/**
 * Enqueue admin scripts and styles
 */
function ipm_admin_enqueue_scripts($hook) {
    if ($hook != 'toplevel_page_incomplete-payment-message') {
        return;
    }
    
    wp_enqueue_style(
        'ipm-admin-css',
        IPM_PLUGIN_URL . 'assets/css/admin.css',
        array(),
        IPM_VERSION
    );
    
    wp_enqueue_script(
        'ipm-admin-js',
        IPM_PLUGIN_URL . 'assets/js/admin.js',
        array('jquery'),
        IPM_VERSION,
        true
    );
}

/**
 * Add plugin action links
 */
function ipm_add_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=incomplete-payment-message') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/**
 * Prevent deactivation when message is enabled
 */
function ipm_prevent_deactivation($actions, $plugin_file, $plugin_data, $context) {
    if ($plugin_file == IPM_PLUGIN_BASENAME) {
        $settings = get_option('ipm_settings');
        
        if (isset($settings['message_enabled']) && $settings['message_enabled']) {
            // Remove deactivate link
            if (isset($actions['deactivate'])) {
                unset($actions['deactivate']);
            }
            
            // Add disabled notice
            $actions['deactivate_disabled'] = '<span class="ipm-deactivate-disabled">Deactivation disabled while message is enabled</span>';
        }
    }
    
    return $actions;
}