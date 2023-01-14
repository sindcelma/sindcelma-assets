<?php 

namespace lib;

use lib\Config as Config;

class Session {

    private $user = false;
    private $sess = "";

    public function __construct($sess){
        
        if(!$sess || trim($sess) == "") return;
        
        try {

            $ch      = curl_init( Config::api()."user/get_user" );
            $payload = json_encode(['session' => $sess]);

            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            
            $result     = curl_exec($ch);
            
            curl_close($ch);
           
            $res        = json_decode($result, true);
            $this->user = isset($res['message']['id']) ? $res['message'] : false;
            $this->sess = isset($res['session'])       ? $res['session'] : $sess;

        } catch (\Throwable $th) {
            $this->user = false;
        }
        
    }

    public function getUser(){
        return $this->user;
    }

    public function getSession(){
        return $this->sess;
    }

    public function isAdmin(){
        return $this->user && $this->user['type'] == 'Admin';
    }

    public function isSocio(){
        return $this->user && $this->user['type'] == 'Socio';
    }

}