<?php

if(!function_exists('uploadFile')) {
    function uploadFile($file, $path) {
        $filename = $file->hashName();
        return $file->move(public_path($path), $filename) ? $path . '/' .$filename : false;
    }
}
