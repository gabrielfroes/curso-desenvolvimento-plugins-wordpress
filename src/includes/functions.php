<?php

function my_youtube_recomendation_fetch_videos_from_url(){
    $channel_id = 'UCFuIUoyHB12qpYa8Jpxoxow'; // put the channel id here
    $feed_url = "https://www.youtube.com/feeds/videos.xml?channel_id={$channel_id}";
    $youtube = file_get_contents($feed_url);
    $youtube = str_replace('<media:', '<media_', $youtube);
    $youtube = str_replace('</media:', '</media_', $youtube);
    
    $xml = simplexml_load_string($youtube, "SimpleXMLElement", LIBXML_NOCDATA);

    $json = json_encode($xml);
    $youtube = json_decode($json, true);
    $yt_vids = array();
    $count = 0;
    foreach ($youtube['entry'] as $k => $v) {

        // Channel info
        if ($count == 0){
            $yt_vids['channel'] = $v['author'];
            $yt_vids['channel']['avatar'] = my_youtube_recomendation_grab_channel_avatar($channel_id);
        }

        // Videos list
        $yt_video_id = str_replace('yt:video:', '', $v['id']);
        $yt_vids['videos'][$count]['id'] = $yt_video_id;
        $yt_vids['videos'][$count]['link'] = "https://youtu.be/{$yt_video_id}";
        $yt_vids['videos'][$count]['thumbnail'] = "https://img.youtube.com/vi/{$yt_video_id}/mqdefault.jpg";
        $yt_vids['videos'][$count]['title'] = $v['title'];
        $yt_vids['videos'][$count]['published'] = $v['published'];
        $yt_vids['videos'][$count]['views'] = $v['media_group']['media_community']['media_statistics']['@attributes']['views'];
       
        $count++;
    }

    return $yt_vids;
}

function my_youtube_recomendation_grab_channel_avatar($channel_id){
    $channel_url = "https://m.youtube.com/channel/{$channel_id}";
    $html = file_get_contents($channel_url);

    $pattern = '/class="appbar-nav-avatar" src="([^"]*)"/i';
    preg_match($pattern, $html, $matches);

    if ( $matches[1] ){
        $yt_img = $matches[1];
    }
    return $yt_img;
}

function my_youtube_recomendation_get_json_path(){
    $upload_dir   = wp_upload_dir();
    if ( !empty($upload_dir['basedir']) ) {
        $dirname = $upload_dir['basedir'].'/'.MY_YOUTUBE_RECOMENDATION_PLUGIN_SLUG;
        if ( !file_exists($dirname) ) {
            wp_mkdir_p($dirname);
        }
        return $dirname . '/videos.json';
    }
}

function my_youtube_recomendation_fetch_videos_from_json(){
    $json = file_get_contents(my_youtube_recomendation_get_json_path());
    return json_decode($json, true);
}

function my_youtube_recomendation_save_json_file($array){
    $fp = fopen(my_youtube_recomendation_get_json_path(), 'w');
    fwrite( $fp, json_encode($array) );
    fclose($fp);
}

function my_youtube_recomendation_fetch_videos(){

    $file_expiration_in_hours = 1;

    // Check if json file exist and not expired
    $json_file = my_youtube_recomendation_get_json_path();
    $json_file_expired = (time()-filemtime($json_file) > ($file_expiration_in_hours * 3600));
    if ( file_exists($json_file) && $json_file_expired == false ){
        // echo ("FROM JSON");
        $videos = my_youtube_recomendation_fetch_videos_from_json();
    } else {
        // echo ("FROM YOUTUBE");
        $videos = my_youtube_recomendation_fetch_videos_from_url();
        
        my_youtube_recomendation_save_json_file($videos);
    }
    return $videos;
}

function my_youtube_recomendation_build_list(){
    $content .= "<div class='my-yt-rec-container'></div>";
    return $content;
}