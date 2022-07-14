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
        // Shortcode.
        add_shortcode( 'klyptest', [ $this, 'shortcode' ] );

        // Scripts.
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );

        // Ajax functions for fetching each colour.
        add_action( 'wp_ajax_fetch_movies_per_colour', [ $this, 'fetch_movies_per_colour' ] );
        add_action( 'wp_ajax_nopriv_fetch_movies_per_colour' , [ $this, 'fetch_movies_per_colour' ]);
    }

    // Main shortcode.
    public function shortcode() {
        wp_enqueue_script( 'klyp-scripts' );
        wp_enqueue_style( 'klyp-styles' );
        ob_start();
        ?>
    
        <div class="klyp-developer-test">
            <div class="klyp-developer-test__container">
                <h1>
                    Klyp - Developer Test
                </h2>

                <p>
                    The premise is simple, create a simple website that will be able to perform a search using the Search API for movies containing any of the following words: red, green, blue or yellow.
                </p>

                <div class="klyp-developer-test__results red" data-colour="red">
                    <h2 class="klyp-developer-test__results__title">
                        Red movies
                    </h2>

                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/src/assets/images/loading.gif" alt="Loading" width="50" height="50"> 
                </div>

                <div class="klyp-developer-test__results green" data-colour="green">
                    <h2 class="klyp-developer-test__results__title">
                        Green movies
                    </h2>

                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/src/assets/images/loading.gif" alt="Loading" width="50" height="50">    
                </div>

                <div class="klyp-developer-test__results blue" data-colour="blue">
                    <h2 class="klyp-developer-test__results__title">
                        Blue movies
                    </h2>

                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/src/assets/images/loading.gif" alt="Loading" width="50" height="50">
                </div>

                <div class="klyp-developer-test__results yellow" data-colour="yellow">
                    <h2 class="klyp-developer-test__results__title">
                        Yellow movies
                    </h2>

                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/src/assets/images/loading.gif" alt="Loading" width="50" height="50">
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
        wp_register_style( 'klyp-styles',  plugin_dir_url( __FILE__ ) . '/dist/assets/css/style.css', [], self::PLUGIN_VERSION );

        // Scripts
        wp_register_script( 'klyp-scripts', plugin_dir_url( __FILE__ ) . '/dist/assets/js/scripts.js', [ 'jquery' ], self::PLUGIN_VERSION, true );
        wp_localize_script( 'klyp-scripts', 'klyp_ajax', [ 'admin_url' => admin_url( 'admin-ajax.php' )]);        
    }

    // Fetch movies per each colour.
    public function fetch_movies_per_colour(){
        $curl = curl_init();

        $api_key = '6604a562';
        $colour = $_POST['colour'];

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://www.omdbapi.com/?s={$colour}&apikey={$api_key}",
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($curl);
        
        curl_close($curl);

        /* 
        ** We will store the movies in the database to cache them and load them faster but only for 12 hours so we can always fetch the latest movies after 12 hours. 
        ** We could also store them in a custom table or the options table but it would mean we wouldn't have access to the latest data which is why 
        ** I chose to go with transients.
        */
        if ( get_transient( "klyp_movies_data_{$colour}" ) === false ) { 
            echo json_encode($response);
            set_transient( "klyp_movies_data_{$colour}", json_encode($response), 12 * HOUR_IN_SECONDS );
        } else {
            echo get_transient( "klyp_movies_data_{$colour}" );
        }

        wp_die();
    }
}

new KlypTest();