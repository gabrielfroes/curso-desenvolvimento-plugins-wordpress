<?php

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function my_youtube_recommendation_uninstall() {
	delete_option('my_yt_rec');
	
	// TODO: Refazer e testar a remoção da pasta e também do arquivo JSON que o plugin gera na pasta uploads do Wordpress.
	$json_file_path = $upload_dir['basedir'].'/'.MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG.'/'.MY_YOUTUBE_RECOMMENDATION_JSON_FILENAME;
	wp_delete_file($json_file_path);
}

register_deactivation_hook( __FILE__, 'my_youtube_recommendation_uninstall' );