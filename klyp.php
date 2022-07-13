<?php
/*
Plugin Name: Klyp - Developer Test
Plugin URI: https://klyp.co/
Description: Developer test for Klyp. 
Version: 1.0.0
Author: Klyp
Author URI: https://klyp.co/
License: GPLv2 or later
Text Domain: klyp
*/

class KlypTest {
    const PLUGIN_VERSION = '1.0.0';

    function __construct() {
        add_shortcode( 'klyptest', [ $this, 'shortcode' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );
    }

    // Main shortcode.
    public function shortcode() {
        wp_enqueue_script( 'front-end-scripts' );
        
        ob_start();
        ?>
        
        <div id="app">{{ message }}</div>

        <?php
        $shortcode_html = ob_get_clean();

        return $shortcode_html;
    }

    // Plugin scripts and styles.
    public function scripts_styles() {
        wp_register_script( 'vue', 'https://unpkg.com/vue@3', [], self::PLUGIN_VERSION, true );

        wp_register_script( 'front-end-scripts', plugin_dir_url( __FILE__ ) . '/dist/js/front-end-scripts.js', [ 'vue' ], self::PLUGIN_VERSION, true );
    }

    // Process ajax requests.
    // Store data in transients.
}

new KlypTest();