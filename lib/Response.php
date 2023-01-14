<?php 

namespace lib;

class Response {

    private $data, $code, $session;

    function __construct($data, int $code = 200, $session = ""){
        
        http_response_code($code);

        $this->session = $session;
        $this->code    = $code;
        $this->data    = $data;
        
    }

    function getData(){
        return $this->data;
    }

    function setSession($session){
        $this->session = $session;
    }

    function response(){
        
        $resp = [
            "code"    => $this->code,
            "message" => $this->data,
        ];

        if($this->session != "") $resp["session"] = $this->session;

        return \json_encode($resp,  
            JSON_PRESERVE_ZERO_FRACTION  | 
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_UNESCAPED_UNICODE); 
    }

}