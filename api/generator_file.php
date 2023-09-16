<?php 

use lib\Config as Config;

function _init(&$body){

    if(!$body['pair'])
        _error(400, 'bad request');

    if(Config::pair() != $body['pair'])
        _error(401, 'unauthorized');

}

function csv($body){

    $fname = sha1($body['name'].mt_rand(0,1000).date('dmyhis')).".csv";
    
    $out   = fopen('../files/'.$fname, 'w');
    
    $body['vars'][] = [];
    $body['vars'][] = ['gerado dia '.date('d/m/Y').' as '.date('H:i:s')];
    
    foreach ($body['vars'] as $linha)
        fputcsv($out, $linha, ";");
    fclose($out);

    _response($fname);

}

function pdf($body){

    $fname = sha1($body['name'].mt_rand(0,1000).date('dmyhis')).".pdf";

}