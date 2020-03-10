<?php
/**
* Plugin Name: My Youtube Recomendation
* Description: Your very first plugin!
* Version: 1.0
* Author: Gabriel Froes
* Author URI: https://twitter.com/gabrielfroes
* License: GPLv2 or later
**/

// Sai se for acessado diretamente
if (!defined('ABSPATH')){
	exit;
}

// Pasta do Plugin
if ( ! defined( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR' ) ) {
	define( 'MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if (!function_exists('my_youtube_recomendation_init')){

    require_once MY_YOUTUBE_RECOMENDATION_PLUGIN_DIR . 'functions.php';

    function my_youtube_recomendation_init($content){
        if (is_single()) {  
            $list_videos = my_youtube_recomendation_fetch_videos();

            $content .=  "<p><a href='{$list_videos[0]['link']}' target='_blank'><img src='{$list_videos[0]['thumbnail']}'><br>{$list_videos[0]['title']}</a></p>";
        }
        return $content;
    }

} // !function_exists

add_filter( 'the_content', 'my_youtube_recomendation_init' );







// TODO: Chamada ajax - https://stackoverflow.com/questions/17855846/using-ajax-in-a-wordpress-plugin
add_action( 'wp_ajax_my_action', 'my_action' );
function my_action() {
	global $wpdb;
	$whatever = intval( $_POST['whatever'] );
	$whatever += 10;
        echo $whatever;
	wp_die();
}