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
    
        <div class="klyp-developer-test">
            <div class="klyp-developer-test__container">
                <h2>
                    Klyp - Developer Test
                </h2>

                <p>
                    The premise is simple, create a simple website that will be able to perform a search using the Search API for movies containing any of the following words: red, green, blue or yellow.
                </p>

                <div class="klyp-developer-test__results red">
                    <h2>
                        Red movies
                    </h2>
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="75" height="75">    
                </div>

                <div class="klyp-developer-test__results green">
                    <h2>
                        Green movies
                    </h2>
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="75" height="75">    
                </div>

                <div class="klyp-developer-test__results blue">
                    <h2>
                        Blue movies
                    </h2>
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="75" height="75">
                </div>

                <div class="klyp-developer-test__results yellow">
                    <h2>
                        Yellow movies
                    </h2>
                    <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="75" height="75">
                </div>
            </div>
        </div>
    
        <?php
        $shortcode_html = ob_get_clean();

        return $shortcode_html;
    }

    // Plugin scripts and styles.
    public function scripts_styles() {
        // Styles

        // Scripts
        wp_register_script( 'front-end-scripts', plugin_dir_url( __FILE__ ) . '/dist/js/front-end-scripts.js', [ 'jquery' ], self::PLUGIN_VERSION, true );
        wp_localize_script( 'front-end-scripts', 'klyp_ajax', [ 'admin_url' => admin_url( 'admin-ajax.php' )]);        
    }

    // Process ajax requests.
    // Store data in transients.
    // Add Gulp. 
}

new KlypTest();