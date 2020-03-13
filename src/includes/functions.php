<?php

function my_youtube_recommendation_fetch_videos_from_url() {

    $channel_id = 'UCFuIUoyHB12qpYa8Jpxoxow'; // put the channel id here
    $feed_url   = "https://www.youtube.com/feeds/videos.xml?channel_id={$channel_id}";
    $response   = wp_remote_get( $feed_url );
    $content    = wp_remote_retrieve_body( $response );

    $content = preg_replace( '/<(\/)?(yt|media)\:/i', '<$1$2_', $content );
    $xml = simplexml_load_string( $content, "SimpleXMLElement", LIBXML_NOCDATA );

    // Quick Convertion XML to Array (using json encoding)
    $json = json_encode( $xml );
    $content = json_decode( $json, true );  

    $videos = array();
    $count = 0;
    foreach ( $content['entry'] as $index => $item ) {

        // Channel info
        if ( $count == 0 ) {
            $videos['channel']             = $item['author'];
            $videos['channel']['avatar']   = my_youtube_recommendation_grab_channel_avatar($channel_id);
        }

        // Videos list
        $yt_video_id = $item['yt_videoId'];
        $videos['videos'][$count]['id']        = $yt_video_id;
        $videos['videos'][$count]['link']      = "https://youtu.be/{$yt_video_id}";
        $videos['videos'][$count]['thumbnail'] = "https://img.youtube.com/vi/{$yt_video_id}/mqdefault.jpg";
        $videos['videos'][$count]['title']     = $item['title'];
        $videos['videos'][$count]['published'] = $item['published'];
        $videos['videos'][$count]['rating']    = $item['media_group']['media_community']['media_starRating']['@attributes']['average'];
        $videos['videos'][$count]['likes']     = $item['media_group']['media_community']['media_starRating']['@attributes']['count'];
        $videos['videos'][$count]['views']     = $item['media_group']['media_community']['media_statistics']['@attributes']['views'];
       
        $count++;

    }
    return $videos;
}

function my_youtube_recommendation_grab_channel_avatar( $channel_id ) {

    $channel_url    = "https://m.youtube.com/channel/{$channel_id}";
    $response       = wp_remote_get( $channel_url );
    $content        = wp_remote_retrieve_body( $response );
    $http_code      = wp_remote_retrieve_response_code( $response );

    if ( $http_code != 200 ) {
        return;
    }

    $pattern = '/class="appbar-nav-avatar" src="([^"]*)"/i';
    preg_match($pattern, $content, $matches);

    if ( $matches[1] ) {
        $avatar = $matches[1];
    }
    return $avatar;

}

function my_youtube_recommendation_get_json_folder() {
    
    $upload_dir = wp_upload_dir();

    if ( !empty($upload_dir['basedir']) ) {
        $dirname = $upload_dir['basedir'].'/'.MY_YOUTUBE_RECOMMENDATION_PLUGIN_SLUG;
        if ( !file_exists($dirname) ) {
            wp_mkdir_p($dirname);
        }
        return $dirname;
    }
}

function my_youtube_recommendation_get_json_path() {

    $folder = my_youtube_recommendation_get_json_folder();
    return $folder . '/videos.json';

}

function my_youtube_recommendation_fetch_videos_from_json() {

    $json = file_get_contents( my_youtube_recommendation_get_json_path() );
    return json_decode($json, true);

}

function my_youtube_recommendation_save_json_file( $array ) {

    $fp = fopen( my_youtube_recommendation_get_json_path(), 'w' );
    fwrite( $fp, json_encode($array) );
    fclose( $fp );

}

function my_youtube_recommendation_fetch_videos() {

    $file_expiration_in_hours = 1;

    // Check if json file exist and not expired
    $json_file = my_youtube_recommendation_get_json_path();
    $json_file_expired = ( time()-filemtime($json_file) > ($file_expiration_in_hours * 3600) );

    if ( file_exists($json_file) && $json_file_expired == false ) {
         // echo ("FROM JSON");
         $videos = my_youtube_recommendation_fetch_videos_from_json();
     } else {
        // echo ("FROM YOUTUBE");
        $videos = my_youtube_recommendation_fetch_videos_from_url();
         my_youtube_recommendation_save_json_file($videos);
    }

    return $videos;

}

function my_youtube_recommendation_build_list() {
    $container_id   = 'my-yt-rec-container';
    $content        .= "<div id='$container_id'></div>";
    $script         = "<script>
                    (function ($) {
                        $(function () {
                            my_yt_rec_init('$container_id');
                        });
                    })(jQuery);
                </script>";
    return $content . $script;

}