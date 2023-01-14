<?php 

use lib\Response as Response;

function _error($code = 404, $message = "Not Found", $session = ""){
    $resp = new Response($message, $code, $session);
    echo $resp->response();
    die();
}

function _response($data = "", $session = ""){
    $resp = new Response($data, 200, $session);
    echo $resp->response();
    die();
}

function _generateUniqueSlug($salt){
    return hash('sha256', $salt.date('dmyhis').mt_rand(1, 1000));
}