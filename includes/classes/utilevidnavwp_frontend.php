<?php

/**
 * UtileVidNavWP - Backend Class
 *
 * This class handles the frontend functionality of the UtileVidNavWP plugin,
 *
 * @package UtileVidNavWP
 * @author Utilewebsites.nl/Pascal Schardijn
 * @version 1.0
 */


class UtileVidNavWP_Frontend {
    private $video_id;
    private $buttons;

    public function __construct() {
        add_shortcode('utilevidnavwp', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

 
    }

    public function enqueue_styles() {
        wp_enqueue_style('utilevidnavwp-main-style', UTILEVIDNAVWP_PLUGIN_URL . 'assets/css/main.css');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('utilevidnavwp-main-script', UTILEVIDNAVWP_PLUGIN_URL.'assets/js/main.js', array('jquery'), '', true);
    }

    public function render_shortcode($atts) {

        $this->video_id = $this->extract_video_id(get_option('utilevidnavwp-video-url')); // Make sure this option contains only the video ID, not the whole URL
        $this->buttons = $this->get_buttons(); // Retrieve the buttons
        $content = '<div id="utilevidnavwp-player"></div>'; // Container for the YouTube player
        $content .= '<div id="utilevidnavwp-buttons-container">' . $this->buttons . '</div>'; // Container for the buttons

        // Dynamically load the YouTube API script
        $content .= "<script>

        var videoId = '" . esc_js($this->video_id) . "';

        // Start het controleren van de YouTube API-lading wanneer de DOM klaar is
        document.addEventListener('DOMContentLoaded', function() {

     
            var youtubeScript = document.createElement('script');
            youtubeScript.src = 'https://www.youtube.com/iframe_api';
            document.head.appendChild(youtubeScript);
        
            checkYouTubeAPILoaded();

        });
         
        </script>";

        return $content;
    }



    private function extract_video_id($url) {
        // Regular expression pattern to match YouTube video URLs
        $pattern = '/[?&]v=([^&]+)/';

        // Try to match the pattern in the URL
        if (preg_match($pattern, $url, $matches)) {
            // The video ID will be in $matches[1]
            return $matches[1];
        } else {
            // If no match is found, return an empty string or handle the error as needed
            return '';
        }
    }

    private function get_buttons() {
        // Retrieve stored buttons and time points from the database
        $buttons_json = get_option('utilevidnavwp-buttons');
        $buttons_json = stripslashes($buttons_json);
        $buttons = json_decode($buttons_json, true);

        // Check if JSON decoding was successful
        if (is_array($buttons) && !empty($buttons)) {
            // Build the HTML for the buttons based on the retrieved data
            $content = '';
            foreach ($buttons as $button) {
                // Add a button with the correct time and name
                $content .= '<button onclick="jumpToTime(' . esc_js($button['time']) . ')">' . esc_html($button['name']) . '</button>';
            }
            return $content;
        } else {
            // No buttons found in the settings
            return 'No buttons found.';
        }
    }
}
