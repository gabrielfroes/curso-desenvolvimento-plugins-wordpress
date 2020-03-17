<?php

if ( ! class_exists( 'My_Youtube_Recommendation_Admin' ) ) {

    class My_Youtube_Recommendation_Admin
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
            add_action( 'admin_footer_text', array( $this, 'page_footer' ) );
        }

        /**
         * Add options page
         */
        public function add_plugin_page() {
            // This page will be under "Settings"
            add_options_page(
                __('Settings'), 
                __('My Youtube Recommendation'), 
                'manage_options', 
                'my-youtube-recommendation', 
                array( $this, 'create_admin_page' )
            );
        }

        /**
         * Options page callback
         */
        public function create_admin_page() {
            // Set class property
            $this->options = get_option( 'my_yt_rec' );
            ?>
            <div class="wrap">
                <h1><?php echo MY_YOUTUBE_RECOMMENDATION_NAME ?></h1>
                <form method="post" action="options.php">
                <?php
                    // This prints out all hidden setting fields
                    settings_fields( 'my_yt_rec_options' );
                    do_settings_sections( 'my-yt-rec-admin' );
                    submit_button();
                ?>
                </form>
            </div>
            <?php
        }

    
        /**
         * Register and add settings
         */
        public function page_init() {   
            
            register_setting(
                'my_yt_rec_options', // Option group
                'my_yt_rec', // Option name
                array( $this, 'sanitize' ) // Sanitize
            );

            add_settings_section(
                'setting_section_id_1', // ID
                'Youtube Channel Settings', // Title
                null, // Callback
                'my-yt-rec-admin' // Page
            );  

            add_settings_field(
                'channel_id', // ID
                'Channel Id', // Title 
                array( $this, 'channel_id_callback' ), // Callback
                'my-yt-rec-admin', // Page
                'setting_section_id_1'// Section           
            );   

            add_settings_section(
                'setting_section_id_2',
                'Video List Settings',
                null,
                'my-yt-rec-admin'
            );    

            add_settings_field(
                'show_position', 
                __('Show in Posts'), 
                array( $this, 'show_position_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_2'
            );  

            add_settings_field(
                'title', 
                'Title', 
                array( $this, 'title_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_2'
            );   
            
            add_settings_field(
                'limit',
                'Videos on List',
                array( $this, 'limit_callback' ),
                'my-yt-rec-admin',
                'setting_section_id_2'
            );  
            add_settings_field(
                'cache_expiration',
                'Cache Expiration', 
                array( $this, 'cache_expiration_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_2' 
            );  

            add_settings_section(
                'setting_section_id_3',
                'Customize Style', 
                null, 
                'my-yt-rec-admin'
            );  

            add_settings_field(
                'custom_css', 
                'Your CSS', 
                array( $this, 'custom_css_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_3'
            );  
        }

        public function page_footer(){
            return __("Plugin Version") . MY_YOUTUBE_RECOMMENDATION_VERSION;
        }

        /**
         * Sanitize each setting field as needed
         *
         * @param array $input Contains all settings fields as array keys
         */
        public function sanitize( $input ) {

            var_dump($input);

            $new_input = array();
            

            if( isset( $input['channel_id'] ) )
                $new_input['channel_id'] = sanitize_text_field( $input['channel_id'] );

            if( isset( $input['show_position'] ) )
                $new_input['show_position'] = sanitize_text_field( $input['show_position'] );

            if( isset( $input['title'] ) )
                $new_input['title'] = sanitize_text_field( $input['title'] );

            if( isset( $input['limit'] ) )
                $new_input['limit'] = absint( $input['limit'] );

            if( isset( $input['cache_expiration'] ) )
                $new_input['cache_expiration'] = absint( $input['cache_expiration'] );

            if( isset( $input['custom_css'] ) )
                $new_input['custom_css'] = sanitize_text_field( $input['custom_css'] );

            return $new_input;
        }

        /** 
         * Get the settings option array and print one of its values
         */
        public function channel_id_callback() {
            printf(
                '<input type="text" id="channel_id" name="my_yt_rec[channel_id]" value="%s" class="regular-text" />
                <p class="description">'.__('sample').': UCFuIUoyHB12qpYa8Jpxoxow</p>
                <p class="description"><a href="https://support.google.com/youtube/answer/3250431" target="_blank">'.__('Find here your channel Id').'</a></p>',
                isset( $this->options['channel_id'] ) ? esc_attr( $this->options['channel_id'] ) : ''
            );
        }

        public function show_position_callback() {
            $value = isset( $this->options['show_position'] ) ? esc_attr( $this->options['show_position'] ) : '';
            ?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php echo __('On posts show videos in position:') ?></span></legend>
                <label><input type="radio" name="show_position" value=""<?php echo ( $value == '' ) ? 'checked="checked"' : '' ?>> <?php echo __('Disable') ?></label><br>
                <label><input type="radio" name="show_position" value="after"<?php echo ( $value == 'after' ) ? 'checked="checked"' : '' ?>> <?php echo __('After') ?></label><br>
                <label><input type="radio" name="show_position" value="before"<?php echo ( $value == 'before' ) ? 'checked="checked"' : '' ?>> <?php echo __('Before') ?></label>
            </fieldset>
            <?php
        }

        public function title_callback() {
            printf(
                '<input type="text" id="title" name="my_yt_rec[title]" value="%s" class="regular-text" /><p class="description">'.__('Optional').'</p>',
                isset( $this->options['title'] ) ? esc_attr( $this->options['title'] ) : ''
            );
        }

        public function limit_callback() {
            printf(
                '<input type="number" id="limit" min="1" max="15" name="my_yt_rec[limit]" value="%s" class="small-text" /><p class="description">'.__('Max').': 15</p>',
                isset( $this->options['limit'] ) ? esc_attr( $this->options['limit'] ) : '3'
            );
        }

        public function cache_expiration_callback() {
            $upload_dir = wp_upload_dir();
            $json_url = $upload_dir['baseurl'] . '/' . MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG . '/' . MY_YOUTUBE_RECOMMENDATION_JSON_FILENAME;
            printf(
                'All data will be stored in a JSON file for <input type="number" id="cache_expiration" min="1" name="my_yt_rec[cache_expiration]" value="%s" class="small-text" /> hours.
                <p class="description"><a href="'.$json_url.'" target="_blank">'.__('Test here').'</a>.',
                isset( $this->options['cache_expiration'] ) ? esc_attr( $this->options['cache_expiration'] ) : '1'
            );
        }

        public function custom_css_callback() {
            printf(
                '<textarea id="custom_css" name="my_yt_rec[custom_css]" rows="10" cols="50" class="large-text code">%s</textarea>',
                isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css'] ) : ''
            );
        }
    }
}

if( is_admin() )
    $my_yt_rec_admin_page = new My_Youtube_Recommendation_Admin();
