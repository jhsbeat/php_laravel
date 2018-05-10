<?php

if(!function_exists('markdown')){
    function markdown($text = null){
        return app(ParsedownExtra::class)->text($text);
    }
}

function gravatar_url($email, $size = 48){
    return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
}

function gravatar_profile_url($email){
    return sprintf("//www.gravatar.com/%s", md5($email));
}