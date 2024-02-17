<?php
namespace Gugusd999\Web;

class Environtment{
   public function __construct($path = "./.env"){
       if(file_exists($path)){
          $getenv = parse_ini_file($path);
          foreach ($getenv as $key => $value) {
              $this->setEnv($key, $value);
          }
        }
   }
    
  public function setEnv($name="", $val = ""){
      if(!defined($name)){
          $_ENV[$name] = $val;
          define($name, $val);
      }
  }

  public static function get($name=""){
      return $_ENV[$name]?$_ENV[$name]:null;
  }
  
}
