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
    const PLUGIN_VERSION = '1.0.1';

    function __construct() {
        // Shortcode.
        add_shortcode( 'klyptest', [ $this, 'shortcode' ] );

        // Scripts.
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts_styles' ] );

        // Ajax functions.
        add_action( 'wp_ajax_fetch_movies', [ $this, 'fetch_movies' ] );
        add_action( 'wp_ajax_nopriv_fetch_movies' , [ $this, 'fetch_movies' ]);
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

                <div class="klyp-developer-test__results red" data-colour="red">
                    <h2>
                        Red movies
                    </h2>
                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="50" height="50"> 
                </div>

                <div class="klyp-developer-test__results green" data-colour="green">
                    <h2>
                        Green movies
                    </h2>
                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="50" height="50">    
                </div>

                <div class="klyp-developer-test__results blue" data-colour="blue">
                    <h2>
                        Blue movies
                    </h2>
                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="50" height="50">
                </div>

                <div class="klyp-developer-test__results yellow" data-colour="yellow">
                    <h2>
                        Yellow movies
                    </h2>
                    <img class="loading-image" src="<?php echo plugin_dir_url( __FILE__ ); ?>/dist/img/loading.gif" alt="Loading" width="50" height="50">
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
    public function fetch_movies(){
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

        if ( get_transient( "klyp_movies_data_{$colour}" ) === false ) {
            // 3rd paramater (expiration date) is not required but we will set it to 12 hours so we can store the data for 12 hours and then refresh to get the latest data.
            echo json_encode($response);
            set_transient( "klyp_movies_data_{$colour}", json_encode($response), 12 * HOUR_IN_SECONDS );
        } else {
            echo get_transient( "klyp_movies_data_{$colour}" );
        }

        wp_die();
    }
    // Add Gulp. 
}

new KlypTest();