<?php

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( !function_exists( 'WP_Filesystem' ) ) { 
    require_once ABSPATH . '/wp-admin/includes/file.php'; 
} 

if ( ! function_exists( 'my_youtube_recommendation_uninstall' ) ) {

	function my_youtube_recommendation_uninstall() {
		delete_option('my_yt_rec');

		$json_folder = $upload_dir['basedir'] . '/my-youtube-recommendation';
		$wp_filesystem = WP_Filesystem_Direct();
		$wp_filesystem->delete($json_folder, true);

	}
}

register_deactivation_hook( __FILE__, 'my_youtube_recommendation_uninstall' );