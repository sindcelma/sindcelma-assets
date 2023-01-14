<?php 

use lib\Config as Config;

function _init(&$body){

    if(!$body['pair'])
        _error(400, 'bad request');

    if(Config::pair() != $body['pair'])
        _error(401, 'unauthorized');

}


function add_random_fav($body){
    
    if(!$body['email'])
        _error(400, 'bad request');

    $email = $body['email'];

    $arr = ['arara-azul', 'ariranha', 'mico-leao-dourado', 'onca-pintada', 'peixe-boi', 'tamandua'];
    $img = "../public/images/padroes/".$arr[mt_rand(0, 5)].".jpg";
    $to  = "../public/images/fav/$email.jpg";
    
    try {
        copy($img, $to);
        _response();
    } catch (\Throwable $th) {
        _error(500, 'erro ao tentar salvar arquivo');
    }

}