<?php 

use lib\Config as Config;

function doit($raw){
    $data = base64_encode(file_get_contents('images/pixel.jpg'));
    file_put_contents('images/pixel.txt', $data);
}

function testPair($raw){
    echo (Config::pair() == $raw['pair'] ? "OK!" : "NO!" );
}