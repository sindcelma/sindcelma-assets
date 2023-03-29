<?php 

use lib\Config as Config;

include "_upload.php";

function _init(&$body){

    if(!$body['pair'])
        _error(400, 'bad request');

    if(Config::pair() != $body['pair'])
        _error(401, 'unauthorized');

}

function change_name($body){

    if(!_isset_in(['ext', 'dir', 'old', 'new'], array_keys($body)))
        _error(400, 'bad request');

    $ext = $body['ext'];
    $dir = $body['dir'];
    $new = $body['new'];
    $old = $body['old'];

    $file_old = "$dir/$old.$ext";
    $file_new = "$dir/$new.$ext";

    try {
        rename($file_old, $file_new);
        _response($file_new);
    } catch (\Throwable $th) {
        _error(500, "Erro ao tentar renomear arquivo");
    }

}

function create($body){

    if(!_isset_in(['ext', 'dir', 'salt'], array_keys($body)))
        _error(400, 'bad request');
        
    $ext  = $body['ext'];
    $dir  = $body['dir'];
    
    if($slug = _create_ghost($ext, $dir, $body['salt'])){
        _response(['slug' => $slug]);
    }

    _error(500, "Erro ao tentar criar o arquivo");

}


function append($body){

    if(!_isset_in(['ext', 'dir', 'data', 'slug'], array_keys($body)))
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = $body['dir'];
    $slug = $body['slug'];
    
    if(_append($ext, $dir, $slug, $body['data'])){
        _response($slug);
    }

    _error(500, "Erro ao tentar inserir conteudo no arquivo");

}


function commit($body){

    if(!_isset_in(['ext', 'dir', 'slug'], array_keys($body)))
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = $body['dir'];
    $slug = $body['slug'];
    
    if($file = _commit($ext, $dir, $slug)){
        _response($file);
    }

    _error(500, "Erro ao fechar o arquivo");

}