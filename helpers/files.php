<?php

use lib\fpdf\FPDF as FPDF;

use lib\Config as Config;

define("MAP", [
    "nome" => "Nome:",
    "sobrenome" => "Sobrenome:",
    "cpf" => "CPF:",
    "rg" => "RG:",
    "genero" => "Gênero:",
    "estado_civil" => "Estado Civil:",
    "data_nascimento" => "Data de Nascimento:",
    "data_admissao" => "Data de Admissão:",
    "telefone" => "Telefone:",
    "email" => "E-mail:",
    "cargo" => "Cargo:"
]);

function fichas($slug) {
    
    $ch      = curl_init( Config::api()."user/socios/data/$slug" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    $result  = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode != "200"){
        return;
    }

    $socio = json_decode($result, true)['message'];
    
    _generate_pdf($socio);

}

function _read_pdf($file){
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $file . '"');
    readfile($file);
}

function _generate_pdf($socio){
    $pdf = new FPDF();
    $pdf -> AddPage('P', 'A4');
    $pdf -> SetFont('Helvetica', 'B', 18);
    $pdf -> Image('images/logo_no_bg.png');
    $pdf -> ln(10);
    $pdf -> Cell(0,0, utf8_decode("Ficha de Filiação - Sindcelma"));
    $pdf -> ln(5);
    foreach ($socio as $key => $value) {
        _add_line($pdf, $key, $value);
    }
    $pdf -> Output();
}

function _add_line($pdf, $chave, $valor){
    
    if(!isset(MAP[$chave])) return;
    $pdf -> ln(5);
    $pdf -> SetFont('Helvetica', 'B', 11);
    $pdf -> Cell(0,0, utf8_decode(MAP[$chave]));
    $pdf -> ln(5);
    $pdf -> SetFont('Helvetica', 'I', 14);
    $pdf -> Cell(0,0, utf8_decode($valor));
    $pdf -> ln(5);
}