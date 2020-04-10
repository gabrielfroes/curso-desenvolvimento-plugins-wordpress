<?php 

if ( ! class_exists( 'My_Youtube_Recommendation_Widget' ) ) {

    class My_Youtube_Recommendation_Widget extends WP_Widget {

        // Main constructor
        public function __construct() {
            parent::__construct(
                'my_youtube_recommendation_widget',
                __('My Youtube Recommendation' , 'my-youtube-recommendation'),
                array(
                    'customize_selective_refresh' => true,
                )
            );

            add_action( 'widgets_init', array( $this, 'init' ) );
        }

        public function init() {
            register_widget( 'My_Youtube_Recommendation_Widget' );
        }

        // The widget form (for the backend )
        public function form( $instance ) {	
            // Set widget defaults
            $defaults = array(
                'title'         => __('Last Videos' , 'my-youtube-recommendation'),
                'layout'        => 'grid',
                'limit'         => '3'
            );
            
            // Parse current settings with defaults
            extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

            <?php // Title ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo __('Title' , 'my-youtube-recommendation'); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <?php ?>

            <?php // Layout ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php echo __('Layout' , 'my-youtube-recommendation'); ?>:</label>
                <select class="postform" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
                <option class="level-0" value="grid" <?php echo (esc_attr( $layout ) == 'grid') ? 'selected="selected"': '' ?>><?php echo __('Grid' , 'my-youtube-recommendation') ?></option>
                <option class="level-0" value="list" <?php echo (esc_attr( $layout ) == 'list') ? 'selected="selected"': '' ?>><?php echo __('List' , 'my-youtube-recommendation') ?></option>
                </select>
            </p>

            <?php // Limit ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php echo __('Videos to show' , 'my-youtube-recommendation'); ?>:</label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="1" max="15" value="<?php echo esc_attr( $limit ); ?>" size="3" /> (15 max)
            </p>

            <?php
        }

        // Update widget settings
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title']      = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
            $instance['layout']      = isset( $new_instance['layout'] ) ? wp_strip_all_tags( $new_instance['layout'] ) : 'grid';
            $instance['limit']      = isset( $new_instance['limit'] ) ? wp_strip_all_tags( $new_instance['limit'] ) : '';
            return $instance;
        }

        // Display the widget
        public function widget( $args, $instance ) {
            extract( $args );

            $widget_unique_id = 'my_yt_rec_widget_' . wp_rand( 1, 1000 );

            // Check the widget options
            $title      = isset( $instance['title'] ) ? apply_filters( 'title', $instance['title'] ) : '';
            $layout     = isset( $instance['layout'] ) ? apply_filters( 'layout', $instance['layout'] ) : 'grid';
            $limit      = isset( $instance['limit'] ) ? apply_filters( 'limit', $instance['limit'] ) : '';
            $language 	= get_locale();

            // WordPress core before_widget hook (always include )
            echo $before_widget;

            ?>
            <div class="widget-text wp_widget_plugin_box">
                <?php echo ( $title ) ? $before_title . $title . $after_title : ''; ?>
                <div id='<?php echo $widget_unique_id ?>'><?php echo __('Loading...' , 'my-youtube-recommendation') ?></div>
            </div>
            <script>
                MyYoutubeRecommendation.listCallbacks.push({
                container: '<?php echo $widget_unique_id ?>',
                layout: '<?php echo $layout ?>',
                limit: <?php echo $limit ?>,
                lang: '<?php echo $language ?>',
                callback: MyYoutubeRecommendation.buildList
                });
            </script>

            <?php
            // WordPress core after_widget hook (always include )
            echo $after_widget;
        }

    }

} // !class_exists