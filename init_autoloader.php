<?php

function AppAutoload($class){
    if (stripos($class,'App') !== false) {
        $path = BASE_DIR . '/';
        $full_name = explode('\\',$class);
        $class_name = array_pop($full_name);
        $path .= mb_strtolower(implode('/',$full_name));
        include  $path . '/' . $class_name . '.php';
    }
}

spl_autoload_register("AppAutoload");