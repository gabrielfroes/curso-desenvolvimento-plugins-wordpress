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
	define( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_SLUG', 'my-youtube-recomendation' );
}

// Plugin Folder
if ( ! defined( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR' ) ) {
	define( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if (!function_exists('my_youtube_recomendation_init')){

   require_once MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR . 'includes/functions.php';

    function my_youtube_recomendation_init($content){
        if (is_single()) {  
            //$list_videos = my_youtube_recomendation_fetch_videos();
            $content .=  my_youtube_recomendation_build_list();
            return $content;
        }
        
    }

} // !function_exists

if (!function_exists('my_youtube_recomendation_scripts')){

    function my_youtube_recomendation_scripts() {
        wp_enqueue_style( 'my-youtube-recomendation-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
        wp_enqueue_script( 'my-youtube-recomendation-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array( 'jquery' ), '', true);
    }

} // !function_exists

if (!function_exists('my_youtube_recomendation_deactivate')){

    function my_youtube_recomendation_deactivate() {
        // TODO: remove json file from uploads folder
        delete_option('my_youtube_recomendation_options');
    }

} // !function_exists



// Filters
add_filter( 'the_content', 'my_youtube_recomendation_init' );

// Actions
add_action( 'wp_enqueue_scripts', 'my_youtube_recomendation_scripts' );

// Hook
register_deactivation_hook( __FILE__, 'my_youtube_recomendation_deactivate');