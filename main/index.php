<?php 

require "../autoload.php";
require "../helpers/request.php";
require "../helpers/response.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

use lib\Config as Config;

if(Config::is_in_production()){
    ini_set('display_errors', 0);
}

$vars = _request()->vars;

if($vars[0] == 'file'){
    // forçar download do arquivo se ele existir
    $body    = _request()->raw();
    if(!isset($body['session'])) exit("Este arquivo não existe!");
        
    $session = new lib\Session($body['session']);
    $file    = '../files/'.$vars[1];

    if(!$session->isAdmin()) exit("Você não tem permissão para acessar este arquivo!");
    if(!file_exists($file))  exit("Este arquivo não existe!");

    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . basename($file) . "\""); 
    
    return readfile($file);

}   

if($vars[0] == 'api'){
    
    header('Content-Type: application/json; charset=utf-8');

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

$req  = isset($_GET['request']) ? $_GET['request'] : "home.html";
$file = $req;

if(!file_exists($file)){
    _error();
}



