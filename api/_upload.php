<?php 

use lib\Config as Config;

use lib\images\EditImage as image;


function _create_ghost($ext, $dir, $salt){

    $slug = _generateUniqueSlug($salt);
    $locl = "$dir/$slug.$ext.ghost";

    try {
        file_put_contents($locl, "");
        return $slug;
    } catch (\Throwable $th) {
        return false;
    }

}


function _append($ext, $dir, $slug, $data){

    sleep(1);

    $locl = "$dir/$slug.$ext.ghost";

    try {
        $data = base64_decode($data);
        file_put_contents($locl, $data, FILE_APPEND);
        return true;
    } catch (\Throwable $th) {
        return false;
    }

}


function _commit($ext, $dir, $slug, $copyName = "", $local = ""){
    
    $locl = "$dir/$slug.$ext.ghost";
    $finl = "$dir/$slug.$ext";
    $file = Config::url()."$dir/$slug.$ext";

    try {
        
        rename($locl, $finl);
        
        if($copyName != ""){
            
            $rootCopy = "$local$copyName.$ext";
            copy($finl, $rootCopy);
            
        }

        return $file;
    
    } catch (\Throwable $th) {
        return false;
    }

}