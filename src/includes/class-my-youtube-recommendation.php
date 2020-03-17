<?php

class My_Youtube_Recommendation {

		public $options;
		
        public function __construct() {
			$this->options = get_option( 'my_yt_rec' );

			// Filters
			add_filter( 'the_content', array( $this, 'add_videos_list_in_single_content' ) );

			// Actions
			add_action( 'wp_enqueue_scripts', array ( $this, 'enqueue_assets' ) );
		}
	
		public function add_videos_list_in_single_content($content) {

			if ( is_single() ) {  

				$position = $this->options['show_position'];
				$position = "after";

				if ($position == 'before') {
					$content =  $this->build_html_videos_list() . $content;
				} elseif ($position == 'after') {
					$content .=  $this->build_html_videos_list();
				}
				return $content;
			}

		}

		private function build_html_videos_list() {
			$container_id   = 'my-yt-rec-container';
			$content        .= "<div id='$container_id'>".__('Loading...')."</div>";
			$script         = "<script>
							(function ($) {
								$(function () {
									MyYoutubeRecommendation.containerId = '$container_id';
									MyYoutubeRecommendation.loadVideos();
								});
							})(jQuery);
						</script>";
			return $content . $script;
		}

		public function enqueue_assets() {

	        wp_enqueue_style( 'my-youtube-recommendation-style', plugin_dir_url( __DIR__ ) . 'public/css/style.css' );
        	wp_enqueue_script( 'my-youtube-recommendation-scripts', plugin_dir_url( __DIR__ ) . 'public/js/scripts.js', array( 'jquery' ), '', true );
			wp_localize_script( 'my-youtube-recommendation-scripts', 'my_yt_rec_ajax', array( 'url' => network_admin_url( 'admin-ajax.php' ) ) );
			
		}


}