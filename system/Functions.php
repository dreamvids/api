<?php

if(!function_exists('debug')){
    function debug($var){
        Response::get()->addDebug($var);
    }
}