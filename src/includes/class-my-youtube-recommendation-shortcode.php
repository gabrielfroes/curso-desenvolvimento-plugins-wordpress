<?php 

if ( ! class_exists( 'My_Youtube_Recommendation_Shortcode' ) ) {

    class My_Youtube_Recommendation_Shortcode {

        public function __construct() {
            add_shortcode('my_youtube', array( $this, 'shortcode' ) );
        }

        public function shortcode( $args, $content=null ) {
            extract( $args );           

            $shortcode_unique_id = 'my_yt_rec_shortcode_' . wp_rand( 1, 1000 );

            // Check the widget options
            $limit      = isset( $limit ) ? $limit : 1;
            $layout     = (isset( $layout ) && $layout == 'list')  ? $layout : 'grid';

            ?>
            <p id='<?php echo $shortcode_unique_id ?>'><?php echo __('Loading...') ?></p>
            <script>
                MyYoutubeRecommendation.listsCallBacks.push({
                container: '<?php echo $shortcode_unique_id ?>',
                layout: '<?php echo $layout ?>',
                limit: <?php echo $limit ?>,
                callback: MyYoutubeRecommendation.buildList
                });
            </script>
            <?php

        }

    }

} // !class_exists