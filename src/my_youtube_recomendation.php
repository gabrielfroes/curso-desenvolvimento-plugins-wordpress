<?php
/**
* Plugin Name: My Youtube Recomendation
* Description: Your very first plugin!
* Version: 1.0
* Author: Gabriel Froes
* Author URI: https://twitter.com/gabrielfroes
* License: GPLv2 or later
**/

// Sai se for acessado diretamente
if (!defined('ABSPATH')){
	exit;
}

if (!function_exists('my_youtube_recomendation_init')){

    function my_youtube_recomendation_init($content){
        if (is_single()) {  
            $content .= '<p><strong>LISTA COM OS VÍDEOS DO YOUTUBE</strong></p>';
        }
        return $content;
    }

} // !function_exists

add_filter( 'the_content', 'my_youtube_recomendation_init' );