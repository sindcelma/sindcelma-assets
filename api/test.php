<?php 

function doit($raw){
    $data = base64_encode(file_get_contents('../public/images/pixel.jpg'));
    file_put_contents('../public/images/pixel.txt', $data);
}