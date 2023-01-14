<?php

function _init(&$body){

    if(!$body['session'])
        _error(400, 'bad request');

    $session = new lib\Session($body['session']);

    if(!$session->isSocio())
        _error(401, 'unauthorized');

    $body['session'] = $session->getSession();

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
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = _get_dir($body['dir']);
    
    if($slug = _create_ghost($ext, $dir, $body['user']['id'])){
        _response($slug, $body['session']);
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
        _response($slug, $body['session']);
    }

    _error(500, "Erro ao tentar inserir conteudo no arquivo");

}


function commit($body){

    if(!_isset_in(['ext', 'dir', 'slug', 'user'], array_keys($body)))
        _error(400, 'bad request');

    $ext  = $body['ext'];
    $dir  = _get_dir($body['dir']);
    $slug = $body['slug'];
    
    if($file = _commit($ext, $dir, $slug))
        _response($file, $body['session']);

    _error(500, "Erro ao fechar o arquivo");

}