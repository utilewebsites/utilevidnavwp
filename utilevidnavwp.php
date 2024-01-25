<?php
/**
 * Plugin Name: UtileVidNavWP
 * Plugin URI: http://utilewebsites.nl
 * Description: A plugin to create interactive videos with navigation buttons.
 * Version: 1.0
 * Author: utilwebsites.nl/Pascal Schardijn
 * Author URI: http://utilewebsites.nl
 */

 define('UTILEVIDNAVWP_PLUGIN_URL', plugin_dir_url(__FILE__));

 include_once(plugin_dir_path(__FILE__) . 'includes/classes/utilevidnavwp_backend.php');
 include_once(plugin_dir_path(__FILE__) . 'includes/classes/utilevidnavwp_frontend.php');

function run_utilevidnavwp() {
    if (class_exists('UtileVidNavWP_Backend')) {
        $utilevidnavwp_backend = new UtileVidNavWP_Backend();
    }
    if (class_exists('UtileVidNavWP_Frontend')) {
        $utilevidnavwp_frontend = new UtileVidNavWP_Frontend();
    }
}

add_action('plugins_loaded', 'run_utilevidnavwp');


