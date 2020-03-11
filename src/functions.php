<?php

function my_youtube_recomendation_fetch_videos_from_url(){
    $channel_id = 'UCFuIUoyHB12qpYa8Jpxoxow'; // put the channel id here
    $feed_url = 'https://www.youtube.com/feeds/videos.xml?channel_id='.$channel_id;
    $youtube = file_get_contents($feed_url);
    $youtube = str_replace('<media:', '<media_', $youtube);
    $youtube = str_replace('</media:', '</media_', $youtube);
    
    $xml = simplexml_load_string($youtube, "SimpleXMLElement", LIBXML_NOCDATA);

    $json = json_encode($xml);
    $youtube = json_decode($json, true);
    $yt_vids = array();
    $count = 0;
    foreach ($youtube['entry'] as $k => $v) {
        $yt_vids[$count]['id'] = str_replace('yt:video:', '', $v['id']);
        $yt_vids[$count]['link'] = "https://youtu.be/{$yt_vids[$count]['id']}";
        $yt_vids[$count]['thumbnail'] = "https://img.youtube.com/vi/{$yt_vids[$count]['id']}/mqdefault.jpg";
        $yt_vids[$count]['title'] = $v['title'];
        $yt_vids[$count]['published'] = $v['published'];
        $yt_vids[$count]['author'] = $v['author'];
        $yt_vids[$count]['views'] = $v['media_group']['media_community']['media_statistics']['@attributes']['views'];
       
        $count++;
    }

    return $yt_vids;
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
        $videos = my_youtube_recomendation_fetch_videos_from_json();
    } else {
        $videos = my_youtube_recomendation_fetch_videos_from_url();
        my_youtube_recomendation_save_json_file($videos);
    }
    return $videos;
}

function my_youtube_recomendation_build_list($videos){

    // Limit
    $limit = 10;
    $videos = array_slice($videos, 0, $limit);

    // CSS (Default or Custom)
    $css   = '
    <style>

        #my-yt-rec{
            display: flex;
            flex-wrap: wrap;
            height: 550px;
            align-content: space-between;
            overflow: auto;
        }

        #my-yt-rec div{
            max-width:360px;
            margin:10px;
        }

        .my-yt-rec-thumbnail {
            transition: opacity 0.5s;
            width: 100%;
            max-width:320px;
            top: 0;
            right: 0;
            bottom: 10px;
            left: 0;
        }

        #my-yt-rec h3{
            color: #606060;
            font-size:10pt;
            font-weight: 500;
            margin:0;
            padding:0;
            text-decoration:none;
            cursor:pointer;
            line-height: 1.5em;
            height: 3em;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            width: 100%;

        }

    </style>';

    // Title (Custom or Blank)
    $title = '<h2 id="my-yt-rec-title">Meus Últimos Vídeos</h2>';

    $list  = '<!-- my-youtube-recomendation -->';
    $list .= $title ;
    $list .= $css;
    $list .= '<div id="my-yt-rec">';
    foreach ($videos as &$video) {
        $list .= "<div><a href='{$video['link']}' target='_blank' title='{$video['title']}'><img src='{$video['thumbnail']}' class='my-yt-rec-thumbnail'><h3>{$video['title']}</h3></a><br>{$video['views']} views</div>";
    }
    $list .= '<div>';
    $list .= '<div style="clear:both"></div>';
    $list .= '<!-- .my-youtube-recomendation -->';
    return $list;
}