<?php

class My_Youtube_Recommendation {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		$this->version = MY_YOUTUBE_RECOMMENDATION_VERSION;
		$this->plugin_name = 'my-youtube-recommendation';

		$this->load_dependencies();
		//$this->set_locale();
		//$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my_youtube_recommendation-loader.php';
		
		if ( is_admin() ) {
			// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-name-admin.php';
		}
		
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-name-public.php';
		$this->loader = new Plugin_Name_Loader();

	}

	// private function set_locale() {

	// 	$plugin_i18n = new Plugin_Name_i18n();

	// 	$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	// }

	// private function define_admin_hooks() {

	// 	$plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_version() );

	// 	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
	// 	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	// }

	private function define_public_hooks() {

		$plugin_public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}