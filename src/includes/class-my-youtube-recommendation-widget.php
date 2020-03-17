<?php 

if ( ! class_exists( 'My_Youtube_Recommendation_Widget' ) ) {

    class My_Youtube_Recommendation_Widget extends WP_Widget {

        // Main constructor
        public function __construct() {
            parent::__construct(
                    'my_youtube_recommendation_widget',
                    MY_YOUTUBE_RECOMMENDATION_NAME,
                    array(
                        'customize_selective_refresh' => true,
                    )
                );
        }

        // The widget form (for the backend )
        public function form( $instance ) {	
            // Set widget defaults
            $defaults = array(
                'title'         => 'Last Videos',
                'limit'         => '3',
                'max_height'    => '',
            );
            
            // Parse current settings with defaults
            extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

            <?php // Title ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <?php ?>

            <?php // Limit ?>
            <!-- TODO: Require Validation: Range 1 to 15 -->
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php _e( 'List limit', 'text_domain' ); ?> (15 max)</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" min="1" max="15" value="<?php echo esc_attr( $limit ); ?>" />
            </p>

            <?php // Container Max Height ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'max_height' ) ); ?>"><?php _e( 'Max Height', 'text_domain' ); ?> (in pixels)</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_height' ) ); ?>" type="number" value="<?php echo esc_attr( $max_height ); ?>" />
            </p>
            <?php
        }

        // Update widget settings
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title']      = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
            $instance['limit']      = isset( $new_instance['limit'] ) ? wp_strip_all_tags( $new_instance['limit'] ) : '';
            $instance['max_height'] = isset( $new_instance['max_height'] ) ? wp_strip_all_tags( $new_instance['max_height'] ) : '';
            return $instance;
        }

        // Display the widget
        public function widget( $args, $instance ) {
            extract( $args );

            $widget_unique_id = 'my_yt_rec_widget_' . wp_rand( 1, 1000 );

            // Check the widget options
            $title      = isset( $instance['title'] ) ? apply_filters( 'title', $instance['title'] ) : '';
            $limit      = isset( $instance['limit'] ) ? apply_filters( 'limit', $instance['limit'] ) : '';
            $max_height = isset( $instance['max_height'] ) ? apply_filters( 'max_height', $instance['max_height'] ) : '';

            // WordPress core before_widget hook (always include )
            echo $before_widget;

            ?>
            <div class="widget-text wp_widget_plugin_box">
                <?php echo ( $title ) ? $before_title . $title . $after_title : ''; ?>
                <div id='$widget_unique_id'></div>
            </div>
            <script>
                (function ($) {
                    $(function () {
                        MyYoutubeRecommendation.loadVideos();
                        MyYoutubeRecommendation.containerId = '<?php echo $widget_unique_id ?>';
                    });
                })(jQuery);
            </script>

            <?php
            // WordPress core after_widget hook (always include )
            echo $after_widget;
        }

    }

} // !class_exists

// Register the widget
if ( ! function_exists( 'my_youtube_recommendation_widget' ) ){

    function my_youtube_recommendation_widget() {
        register_widget( 'My_Youtube_Recommendation_Widget' );
    }
    
} // !function_exists

// TODO: Tentar usar o mesmo tipo de inicialização do página de administração utilizando o __constructor.

add_action( 'widgets_init', 'my_youtube_recommendation_widget' );