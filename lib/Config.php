<?php 

namespace lib;

class Config {

    private static $instance = false;
    private $data, $url, $api;

    private function __construct(){
        $this->data = json_decode(file_get_contents('../config.json'), true);
        $this->url  = $this->data['url_'.$this->data['type']];
        $this->api  = $this->data['api_'.$this->data['type']];
    }

    private static function instance(){

        if(!self::$instance)
            self::$instance = new Config();

        return self::$instance;
    }

    public static function get(string $key = ""){

        $conf = self::instance()->data;
        
        if($key != "") 
            return (isset($conf[$key]) 
                        ? $conf[$key] 
                        : false);
                        
        return $conf;
    }

    public static function url(){

        return self::instance()->url;
    }

    public static function api(){

        return self::instance()->api;
    }

    public static function is_in_production(){

        return self::instance()->data['type'] == 'prod';
    }

}