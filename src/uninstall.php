<?php

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! function_exists( 'my_youtube_recommendation_uninstall' ) ) {

	function my_youtube_recommendation_uninstall2() {
        delete_option('my_yt_rec');
        
        $upload_dir     = wp_upload_dir();
		$json_folder 	= $upload_dir['basedir'] . '/my-youtube-recommendation' ;
		$json_file 		= $json_folder . '/my-yt-rec.json';
		unlink($json_file);
        rmdir($json_folder);
	}
	
}

register_uninstall_hook( __FILE__, 'my_youtube_recommendation_uninstall' );