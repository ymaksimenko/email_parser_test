<?php
spl_autoload_register(function ($class) {
    $path = BASE_DIR . '/';
    $full_name = explode('\\',$class);
    $class_name = array_pop($full_name);
    $path .= mb_strtolower(implode('/',$full_name));
    include  $path . '/' . $class_name . '.php';
});
