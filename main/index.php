<?php 

require "../autoload.php";
require "../helpers/request.php";
require "../helpers/response.php";

$vars = _request()->vars;

if($vars[0] == 'api'){
    
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    if(count($vars) < 3) {
        _error(400, "Bad Request");
    }

    return (function($serv, $func){

        $file = "../api/$serv.php";
        if(!file_exists($file)){
            _error(400, "Bad Request");
        }

        include $file;

        $func = $func[0] == '_' ? false : $func;

        if(!$func || !function_exists($func)){
            _error(400, "Bad Request");
        }

        $body = _request()->raw();

        if(function_exists('_init')){
            _init($body);
        }

        $func($body);

    })($vars[1], $vars[2]);
}


$req  = $_GET['request'];
$file = "../public/$req";

if(!file_exists($file)){
    _error();
}

$mime = mime_content_type($file);
header("Content-Type: $mime ");

if(in_array($mime, ['image/jpg', 'image/jpeg', 'image/png'])){
    if($mime == 'image/png'){
        $srcimg = imagecreatefrompng($file);
        imagesavealpha($srcimg, true);
        imagepng($srcimg);
    } else {
        $srcimg = imagecreatefromjpeg($file);
        imagejpeg($srcimg);
    }
    imagedestroy($srcimg);
    
} else {
    include $file;
}



