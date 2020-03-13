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
                'widget_title'  => 'Last Videos',
                'channel_id'    => '',
            );
            
            // Parse current settings with defaults
            extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

            <?php // Title ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'widget_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_title' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>" />
            </p>
            <?php ?>

            <?php // Channel Id ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'channel_id' ) ); ?>"><?php _e( 'Youtube Channel ID', 'text_domain' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'channel_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'channel_id' ) ); ?>" type="text" value="<?php echo esc_attr( $channel_id ); ?>" />
            </p>
            <?php
        }

        // Update widget settings
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
            return $instance;
        }

        // Display the widget
        public function widget( $args, $instance ) {
            extract( $args );

            $widget_unique_id = 'my_yt_rec_widget_' . wp_rand( 1, 1000 );

            // Check the widget options
            $title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

            // WordPress core before_widget hook (always include )
            echo $before_widget;

            // Display the widget
            echo '<div class="widget-text wp_widget_plugin_box">';

            // Display widget title if defined
            if ( $title ) {
                echo $before_title . $title . $after_title;
            }

            echo "<div id='$widget_unique_id'></div>";

             echo '</div>';

            // WordPress core after_widget hook (always include )
            echo $after_widget;

            $script = "<script>
                        (function ($) {
                            $(function () {
                                my_yt_rec_init('$widget_unique_id');
                            });
                        })(jQuery);
                    </script>";
            echo $script;
        }

    }

} // !class_exists

// Register the widget
if ( ! function_exists( 'my_youtube_recommendation_widget' ) ){

    function my_youtube_recommendation_widget() {
        register_widget( 'My_Youtube_Recommendation_Widget' );
    }
    
} // !function_exists

add_action( 'widgets_init', 'my_youtube_recommendation_widget' );