<?php 

namespace lib;

class Request {

    public $vars;
    private static $url_parts;
    private static $raw;

    public function __construct(){
   
        if(!self::$url_parts){
            $request = isset($_GET['request']) ? $_GET['request'] : 'home';
            self::$url_parts = explode('/', trim($request));
            $this->vars = self::$url_parts;
        }

    }

    public static function raw(){
        if(!self::$raw) {
            self::$raw = json_decode(file_get_contents('php://input'), true);
            if(!self::$raw) self::$raw = [];
        }
        return self::$raw;
    }

    public static function clean_value($value, $type = 'mixed'){
        
        if($type == 'array'){
            if(!is_array($value)) return [self::clean_value($value)];
            $valueFinal = [];
            foreach ($value as $k => $v) {
                $valueFinal[$k] = is_array($v) ? self::clean_value($v, 'array') : self::clean_value($v);
            }
            return $valueFinal;
        }
    
        if($type == 'mixed' || $type == 'string')  $value = addslashes($value);
        if($type == 'int') $value = (int)$value;
        if($type == 'float') $value = (float)$value;
    
        return $value;
    }


}