<?php

function my_youtube_recomendation_fetch_videos(){
    $channel_id = 'UCFuIUoyHB12qpYa8Jpxoxow'; // put the channel id here
    $feed_url = 'https://www.youtube.com/feeds/videos.xml?channel_id='.$channel_id;
    $youtube = file_get_contents($feed_url);
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
        $count++;
    }
    return $yt_vids;
}