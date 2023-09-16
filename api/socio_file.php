<?php

include "_upload.php";

function _init(&$body){

    if(!isset($body['session']) && !isset($body['user']))
        _error(400, 'bad request 1');

    if(isset($body['session'])){
        $session = new lib\Session($body['session']);

        if(!$session->isSocio())
            _error(401, 'unauthorized');

        $body['session'] = $session->getSession();
        $body['user']    = $session->getUser();
    }
    
}


function _get_dir($type){
    switch ($type) {
        case 'nodoc': return 'images/nodoc';
        case 'doc': return 'images/doc';
        case 'fav':
        default:
            return 'images/fav';
    }
}

function create($body){

    if(!_isset_in(['ext', 'dir', 'user'], array_keys($body)))
        _error(400, 'bad request 2');

    $ext  = $body['ext'];
    $dir  = _get_dir($body['dir']);
    
    if($slug = _create_ghost($ext, $dir, isset($body['user']['id']) ? $body['user']['id'] : $body['user'])){
        _response(['slug' => $slug], isset($body['session']) ? $body['session'] : "");
    }

    _error(500, "Erro ao tentar criar o arquivo");

}

function append($body){

    if(!_isset_in(['ext', 'dir', 'data', 'slug', 'user'], array_keys($body)))
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = _get_dir($body['dir']);
    $slug = $body['slug'];
    
    if(_append($ext, $dir, $slug, $body['data'])){
        _response($slug, isset($body['session']) ? $body['session'] : "");
    }

    _error(500, "Erro ao tentar inserir conteudo no arquivo");

}


function commit($body){

    if(!_isset_in(['ext', 'dir', 'slug', 'user'], array_keys($body)))
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = _get_dir($body['dir']);
    $slug = $body['slug'];
    $copy = "";
    $loc  = "";

    if($body['dir'] != 'doc'){
        $copy = isset($body['user']['email']) ? $body['user']['email'] : "";
        $copy = isset($body["copy"]) ? $body["copy"] : "";
        $loc  = "images/fav/";
    }
    
    if($file = _commit($ext, $dir, $slug, $copy, $loc)){
        _response($file, isset($body['session']) ? $body['session'] : "");
    }
        
    _error(500, "Erro ao fechar o arquivo");

}