<?php 

/**
 * UtileVidNavWP - Backend Class
 *
 * This class handles the backend functionality of the UtileVidNavWP plugin,
 * including the admin menu, settings, and button data management.
 *
 * @package UtileVidNavWP
 * @author Utilewebsites.nl/Pascal Schardijn
 * @version 1.0
 */


class UtileVidNavWP_Backend {
    public function __construct() {
        add_action('admin_menu', array($this, 'create_menu'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts')); // Hook for enqueuing admin-specific scripts
    }

    public function create_menu() {
        // Create a menu page in the WordPress admin
        add_menu_page('UtileVidNavWP Settings', 'UtileVidNavWP', 'administrator', 'utilevidnavwp-settings', array($this, 'create_admin_page'), 'dashicons-video-alt3');
    }

    public function create_admin_page() {
        // HTML for the admin page
        ?>
        <div class="wrap">
            <h1>UtileVidNavWP Settings</h1>
            <p>Insert the shortcode on the page with: [utilevidnavwp]</p>
            <form method="post" id="utilevidnavwp-settings-form" action="options.php">
                <?php
                settings_fields('utilevidnavwp_config');
                do_settings_sections('utilevidnavwp-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {

      // Check if the form is submitted and the correct POST variable is sent
    if (isset($_POST['utilevidnavwp-buttons-json'])) {
        $buttons_json = sanitize_text_field($_POST['utilevidnavwp-buttons-json']);
        update_option('utilevidnavwp-buttons', $buttons_json);


    }
        // Register settings and add settings fields
        register_setting("utilevidnavwp_config", "utilevidnavwp-video-url");
        add_settings_section("utilevidnavwp_video", "Video Settings", null, "utilevidnavwp-settings");
        add_settings_field("utilevidnavwp-video-url", "YouTube Video URL", array($this, "video_url_callback"), "utilevidnavwp-settings", "utilevidnavwp_video");

        // Register setting for buttons
        
        // Register setting for buttons met aangepaste validatiefunctie
        if (!get_option('utilevidnavwp-buttons')) {
            // If the option does not exist yet, register it
            register_setting('utilevidnavwp_config', 'utilevidnavwp-buttons');
        }
        
        add_settings_field('utilevidnavwp-buttons', 'Buttons', array($this, 'buttons_callback'), 'utilevidnavwp-settings', 'utilevidnavwp_video');


        
    }

    function validate_utilevidnavwp_buttons($input) {
        // If the input is empty, keep the existing value
        if (empty($input)) {
            $current_value = get_option('utilevidnavwp-buttons');
            return $current_value;
        }
       
        return $input;
    }

    public function video_url_callback() {
        // Callback for the video URL field
        ?>
        <input type="url" name="utilevidnavwp-video-url" value="<?php echo esc_attr(get_option('utilevidnavwp-video-url')); ?>" />
        <?php
    }

    public function buttons_callback() {
        // Callback for the buttons field
        $buttons_json = get_option('utilevidnavwp-buttons');
        $buttons_json = stripslashes($buttons_json);
        $buttons = json_decode($buttons_json, true) ?: [];

        // Hidden input to store serialized button data
        echo '<input type="hidden" id="utilevidnavwp-buttons-json" name="utilevidnavwp-buttons-json" value="' . esc_attr($buttons_json) . '" />';
    
        // Print a container for the buttons
        echo '<div id="utilevidnavwp-buttons-container">';
        
        // Loop through the buttons and display them
        foreach ($buttons as $button) {
            echo '<div class="utilevidnavwp-button-field">';
            echo '<input type="text" placeholder="Button Name" value="' . esc_attr($button['name']) . '" />';
            echo '<input type="number" placeholder="Time (seconds)" value="' . esc_attr($button['time']) . '" />';
            echo '<button type="button" class="utilevidnavwp-remove-button">Remove</button>';
            echo '</div>';
        }
    
        echo '</div>';
        echo '<button id="utilevidnavwp-add-button" type="button">+</button>';

    }
    

    public function enqueue_admin_scripts() {
        // Ensure that scripts are only loaded on the admin page of the plugin
        $screen = get_current_screen();
        if (isset($screen->id) && $screen->id === 'toplevel_page_utilevidnavwp-settings') {
    
            // Enqueue admin-specific scripts and styles
            wp_enqueue_script('utilevidnavwp-admin-script', UTILEVIDNAVWP_PLUGIN_URL. 'assets/js/admin.js', array('jquery'), null, true);
            wp_enqueue_style('utilevidnavwp-admin-style', UTILEVIDNAVWP_PLUGIN_URL . 'assets/css/admin.css');
    
            // Retrieve the saved buttons data and decode it into an array
            $buttons_json = get_option('utilevidnavwp-buttons');
            $buttons = json_decode($buttons_json, true) ?: [];
    
            // Localize the script with your data.
            wp_localize_script('utilevidnavwp-admin-script', 'utilevidnavwpData', array(
                'buttons' => $buttons
            ));
        }
    }
    

}
