<?php
if( !function_exists('imgDefault') ){
    function imgDefault(){
        return asset('images/1-dota.jpg');
    }
}

if( !function_exists('getImg') ){
    function getImg($img_url){
        return asset('storage'.$img_url);
    }
}

if( !function_exists('contentPostFormat') ){
    function contentPostFormat($post_content, $post_limit){
        return strip_tags(htmlspecialchars_decode(\Str::limit($post_content, $post_limit, '...')));
    }
}

if( !function_exists('killNoDie') ){
    function killNoDie($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
