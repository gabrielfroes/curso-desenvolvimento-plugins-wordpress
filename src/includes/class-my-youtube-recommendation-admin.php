<?php

if ( ! class_exists( 'My_Youtube_Recommendation_Admin' ) ) {

    class My_Youtube_Recommendation_Admin
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;
        private $plugin_basename;
        private $plugin_slug;
        private $json_filename;

        /**
         * Start up
         */
        public function __construct( $basename, $slug, $json_filename ) {

            $this->options = get_option( 'my_yt_rec' );

            $this->plugin_basename = $basename;
            $this->plugin_slug = $slug;
            $this->json_filename = $json_filename;

            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
            add_action( 'admin_footer_text', array( $this, 'page_footer' ) );
            add_action( 'admin_notices', array( $this, 'show_notices' ) );

            add_filter( "plugin_action_links_" . $this->plugin_basename, array( $this, 'add_settings_link' ) );


        }

        /**
         * Add options page
         */
        public function add_plugin_page() {
            // This page will be under "Settings"
            add_options_page(
                __('Settings' , 'my-youtube-recommendation'), 
                __('My Youtube Recommendation' , 'my-youtube-recommendation'), 
                'manage_options', 
                $this->plugin_slug, 
                array( $this, 'create_admin_page' )
            );
        }

        /**
         * Add settings link on plugins page
         */
        public function add_settings_link( $links ) {
            $settings_link = '<a href="options-general.php?page='. $this->plugin_slug .'">' . __( 'Settings' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }

        /**
         * Show notices on admin dashboard
         */
        public function show_notices() {
            $value = isset( $this->options['channel_id'] ) ? esc_attr( $this->options['channel_id'] ) : '';
            if ($value == ''){
                ?>
                <div class="error notice">
                <?php echo $channel_id ?>
                    <p><strong><?php echo __( 'My Youtube Recommendation', 'my-youtube-recommendation' ); ?></strong></p>
                    <p><?php echo __( 'Fill with your Youtube channel ID', 'my-youtube-recommendation' ); ?></p>
                </div>
                <?php
            }
        }

        /**
         * Options page callback
         */
        public function create_admin_page() {
            // Set class property
            // $this->options = get_option( 'my_yt_rec' );
            ?>
            <div class="wrap">
                <h1><?php echo __('My Youtube Recommendation' , 'my-youtube-recommendation'); ?></h1>
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
                __('General Settings' , 'my-youtube-recommendation'), // Title
                null, // Callback
                'my-yt-rec-admin' // Page
            );  

            add_settings_field(
                'channel_id', // ID
                __('Channel Id', 'my-youtube-recommendation'), // Title 
                array( $this, 'channel_id_callback' ), // Callback
                'my-yt-rec-admin', // Page
                'setting_section_id_1'// Section           
            );   

            add_settings_field(
                'cache_expiration',
                __('Cache Expiration' , 'my-youtube-recommendation'), 
                array( $this, 'cache_expiration_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_1' 
            );  

            add_settings_section(
                'setting_section_id_2',
                __('Post Settings' , 'my-youtube-recommendation'),
                null,
                'my-yt-rec-admin'
            );    

            add_settings_field(
                'show_position', 
                __('Show in Posts' , 'my-youtube-recommendation'), 
                array( $this, 'show_position_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_2'
            );  
            
            add_settings_field(
                'layout', 
                __('Layout' , 'my-youtube-recommendation'), 
                array( $this, 'show_layout_callback' ), 
                'my-yt-rec-admin', 
                'setting_section_id_2'
            );  

            add_settings_field(
                'limit',
                __('Videos in List' , 'my-youtube-recommendation'),
                array( $this, 'limit_callback' ),
                'my-yt-rec-admin',
                'setting_section_id_2'
            );  

            add_settings_section(
                'setting_section_id_3',
                __('Customize Style' , 'my-youtube-recommendation'), 
                null, 
                'my-yt-rec-admin'
            );  

            add_settings_field(
                'custom_css', 
                __('Your CSS' , 'my-youtube-recommendation'), 
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

            $new_input = array();          

            if( isset( $input['channel_id'] ) )
                $new_input['channel_id'] = sanitize_text_field( $input['channel_id'] );
                
                if( isset( $input['cache_expiration'] ) )
                    $new_input['cache_expiration'] = absint( $input['cache_expiration'] );

            if( isset( $input['show_position'] ) )
                $new_input['show_position'] = sanitize_text_field( $input['show_position'] );

            if( isset( $input['layout'] ) )
                $new_input['layout'] = sanitize_text_field( $input['layout'] );

            if( isset( $input['limit'] ) )
                $new_input['limit'] = absint( $input['limit'] );

            if( isset( $input['custom_css'] ) )
                $new_input['custom_css'] = sanitize_text_field( $input['custom_css'] );

            return $new_input;
        }

        /** 
         * Get the settings option array and print one of its values
         */
        public function channel_id_callback() {
            $value = isset( $this->options['channel_id'] ) ? esc_attr( $this->options['channel_id'] ) : '';
            ?>
            <input type="text" id="channel_id" name="my_yt_rec[channel_id]" value="<?php echo $value ?>" class="regular-text" />
                <p class="description"><?php echo __('sample' , 'my-youtube-recommendation') ?>: UCFuIUoyHB12qpYa8Jpxoxow</p>
                <p class="description"><a href="https://support.google.com/youtube/answer/3250431" target="_blank"><?php echo __('Find here your channel Id' , 'my-youtube-recommendation') ?></a></p>
            <?php
        }

        public function cache_expiration_callback() {
            $upload_dir = wp_upload_dir();
            $json_url = $upload_dir['baseurl'] . '/' . $this->plugin_slug . '/' . $this->json_filename;

            $value = isset( $this->options['cache_expiration'] ) ? esc_attr( $this->options['cache_expiration'] ) : '1';
            ?>
                <input type="number" id="cache_expiration" min="1" name="my_yt_rec[cache_expiration]" value="<?php echo $value ?>" class="small-text" />
                <?php echo __('hours is the expiration time for cached data' , 'my-youtube-recommendation') ?>.
                <p class="description"><a href="<?php echo $json_url?>" target="_blank"><?php echo __('Test here' , 'my-youtube-recommendation') ?></a>.
            <?php
        }

        public function show_position_callback() {
            $value = isset( $this->options['show_position'] ) ? esc_attr( $this->options['show_position'] ) : '';
            ?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php echo __('On posts show videos in position:' , 'my-youtube-recommendation') ?></span></legend>
                <label><input type="radio" name="my_yt_rec[show_position]" value=""<?php echo ( $value == '' ) ? 'checked="checked"' : '' ?>> <?php echo __('Disable' , 'my-youtube-recommendation') ?></label><br>
                <label><input type="radio" name="my_yt_rec[show_position]" value="after"<?php echo ( $value == 'after' ) ? 'checked="checked"' : '' ?>> <?php echo __('After' , 'my-youtube-recommendation') ?></label><br>
                <label><input type="radio" name="my_yt_rec[show_position]" value="before"<?php echo ( $value == 'before' ) ? 'checked="checked"' : '' ?>> <?php echo __('Before' , 'my-youtube-recommendation') ?></label>
            </fieldset>
            <?php
        }

        public function show_layout_callback() {
            $value = isset( $this->options['layout'] ) ? esc_attr( $this->options['layout'] ) : 'grid';
            ?>
            <select name="my_yt_rec[layout]">
                <option value="grid"<?php echo ( $value == 'grid' ) ? 'selected="selected"' : '' ?>><?php echo __('Grid' , 'my-youtube-recommendation') ?></option>
                <option value="list"<?php echo ( $value == 'list' ) ? 'selected="selected"' : '' ?>><?php echo __('List' , 'my-youtube-recommendation') ?></option>
            </select>
            <?php
        }

        public function limit_callback() {
            $value = isset( $this->options['limit'] ) ? esc_attr( $this->options['limit'] ) : '3';
            ?>
            <input type="number" id="limit" min="1" max="15" name="my_yt_rec[limit]" value="<?php echo $value ?>" class="small-text" />
            <p class="description"><?php echo __('Max' , 'my-youtube-recommendation') ?> 15</p>
            <?php
        }

        public function custom_css_callback() {
            $value = isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css'] ) : '';
            ?>
            <textarea id="custom_css" name="my_yt_rec[custom_css]" rows="10" cols="50" class="large-text code"><?php echo $value ?></textarea>
            <?php
        }
    }
}