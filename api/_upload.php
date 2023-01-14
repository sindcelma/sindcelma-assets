<?php 

use lib\Config as Config;

function _create_ghost($ext, $dir, $id){

    $slug = _generateUniqueSlug($id);
    $locl = "../public/$dir/$slug.$ext.ghost";

    try {
        file_put_contents($locl, "");
        return $slug;
    } catch (\Throwable $th) {
        return false;
    }

}


function _append($ext, $dir, $slug, $data){

    sleep(1);

    $locl = "../public/$dir/$slug.$ext.ghost";

    try {
        $data = base64_decode($data);
        file_put_contents($locl, $data, FILE_APPEND);
        return true;
    } catch (\Throwable $th) {
        return false;
    }

}


function _commit($ext, $dir, $slug, $copyName = ""){
    
    $locl = "../public/$dir/$slug.$ext.ghost";
    $finl = "../public/$dir/$slug.$ext";
    $file = Config::url()."$dir/$slug.$ext";

    try {
        rename($locl, $finl);
        if($copyName != "")
            copy($finl, "$copyName.$ext");
        return $file;
    } catch (\Throwable $th) {
        return false;
    }

}