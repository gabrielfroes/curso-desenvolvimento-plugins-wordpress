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

// Plugin Name
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_NAME' ) ) {
    define( 'MY_YOUTUBE_RECOMMENDATION_NAME', 'My Youtube Recommendation' );
}

// Plugin Slug
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG' ) ) {
	define( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG', 'my-youtube-recommendation' );
}

// Plugin Folder
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR' ) ) {
	define( 'MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// JSON File Name
if ( ! defined( 'MY_YOUTUBE_RECOMMENDATION_JSON_FILENAME' ) ) {
	define( 'MY_YOUTUBE_RECOMMENDATION_JSON_FILENAME', 'my-yt-rec.json' );
}

// Dependencies
require_once MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR . 'includes/class-my-youtube-recommendation.php';
require_once MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR . 'includes/class-my-youtube-recommendation-json.php';
require_once MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR . 'includes/class-my-youtube-recommendation-widget.php';

if( is_admin() ) // Optional
    require_once MY_YOUTUBE_RECOMMENDATION_PLUGIN_DIR . 'includes/class-my-youtube-recommendation-admin.php';

// Plugin Instance
$my_yt_rec_plugin = new My_Youtube_Recommendation();

$channel_id = $my_yt_rec_plugin->options['channel_id'];
if ( $channel_id != "" ){
    $expiration = $my_yt_rec_plugin->options['cache_expiration'];
    $my_yt_rec_json = new My_Youtube_Recommendation_Json( $channel_id, $expiration );
  
}