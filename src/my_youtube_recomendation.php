<?php
/**
* Plugin Name: My Youtube Recomendation
* Description: Display the last videos from a Youtube channel using Youtube Feed and keep always updated even for cached posts.
* Version: 1.0.0
* Author: Gabriel Froes
* Author URI: https://twitter.com/gabrielfroes
* License: GPLv2 or later
**/

// Sai se for acessado diretamente
if (!defined('ABSPATH')){
	exit;
}

// Plugin Slug
if ( ! defined( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_SLUG' ) ) {
	define( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_SLUG', 'my_youtube_recomendation' );
}

// Plugin Folder
if ( ! defined( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR' ) ) {
	define( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if (!function_exists('my_youtube_recomendation_init')){

   require_once MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR . 'includes/functions.php';

    function my_youtube_recomendation_init($content){
        if (is_single()) {  
            $list_videos = my_youtube_recomendation_fetch_videos();
            $content .=  my_youtube_recomendation_build_list($list_videos);
        }
        return $content;
    }

} // !function_exists


// Filters
add_filter( 'the_content', 'my_youtube_recomendation_init' );

// Actions





// // TODO: Chamada ajax - https://stackoverflow.com/questions/17855846/using-ajax-in-a-wordpress-plugin
// add_action( 'wp_ajax_my_action', 'my_action' );
// function my_action() {
// 	global $wpdb;
// 	$whatever = intval( $_POST['whatever'] );
// 	$whatever += 10;
//         echo $whatever;
// 	wp_die();
// }