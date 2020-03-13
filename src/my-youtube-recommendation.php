<?php
/**
 * @link              http://www.github.com/gabrielfroes/my_youtube_recommendation
 * @since             1.0.0
 * @package           My_Youtube_Recommendation
 *
 * @wordpress-plugin
 * Plugin Name:       My Youtube Recommendation
 * Plugin URI:        http://www.github.com/gabrielfroes/my_youtube_recommendation
 * Description:       Display the last videos from a Youtube channel using Youtube feed and keep always updated even for cached posts.
 * Version:           1.0.0
 * Author:            Gabriel Froes
 * Author URI:        https://www.youtube.com/codigofontetv
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       my-youtube-recommendation
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Plugin Version
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_VERSION' ) ) {
    define( 'MY_YOUTUBE_RECOMMENDATION_VERSION', '1.0.0' );
}

// Plugin Slug
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG' ) ) {
	define( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG', 'my-youtube-recommendation' );
}

// Plugin Folder
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR' ) ) {
	define( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}


if ( ! function_exists('my_youtube_recommendation_init') ){

   require_once MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR . 'includes/functions.php';

    function my_youtube_recommendation_init($content){
        if ( is_single() ) {  
            $list_videos = my_youtube_recommendation_fetch_videos();
            // my_youtube_recommendation_deactivate();
            $content .=  my_youtube_recommendation_build_list();
            return $content;
        }
        
    }

} // !function_exists

if ( ! function_exists('my_youtube_recommendation_scripts') ){

    function my_youtube_recommendation_scripts() {
        wp_enqueue_style( 'my-youtube-recommendation-style', plugin_dir_url( __FILE__ ) . 'public/css/style.css' );
        wp_enqueue_script( 'my-youtube-recommendation-scripts', plugin_dir_url( __FILE__ ) . 'public/js/scripts.js', array( 'jquery' ), '', true );
    }

} // !function_exists

if ( ! function_exists('my_youtube_recommendation_deactivate') ){

    function my_youtube_recommendation_deactivate() {
        // TODO: remove json file from uploads folder
        WP_Filesystem();
        global $wp_filesystem;
        $folder = my_youtube_recommendation_get_json_folder();
        $wp_filesystem->rmdir($folder, true);

        // delete_option('my_youtube_recommendation_options');
    }

} // !function_exists



// Filters
add_filter( 'the_content', 'my_youtube_recommendation_init' );

// Actions
add_action( 'wp_enqueue_scripts', 'my_youtube_recommendation_scripts' );

// Hook
register_deactivation_hook( __FILE__, 'my_youtube_recommendation_deactivate');