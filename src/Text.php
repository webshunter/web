<?php
namespace Gugusd999\Web;

class Text{
    private $string;
    public function __construct(...$arg){
        if(isset($arg[0])){
            $this->string = $arg[0];
        }
        return $this;
    }

    public function replace(...$arg){
        if(isset($arg[0]) && isset($arg[1])){
            $arg[] = $this->string;
            $this->string = str_replace(...$arg);
        }
        return $this;
    }

    public function slug(){
          $name = $this->string;
          $name = str_replace('"','',$name);
          $name = str_replace("'",'',$name);
          $name = str_replace(" ",'-',$name);
          $name = str_replace("@",'',$name);
          $name = str_replace(",",'',$name);
          $name = str_replace(".",'',$name);
          $name = str_replace("/",'',$name);
          $name = str_replace("|",'',$name);
          $name = str_replace("\\",'',$name);
          $name = str_replace("=",'',$name);
          $name = str_replace("+",'',$name);
          $name = str_replace("(",'',$name);
          $name = str_replace(")",'',$name);
          $name = str_replace("[",'',$name);
          $name = str_replace("]",'',$name);
          $name = str_replace(";",'',$name);
          $name = str_replace(":",'',$name);
          $name = str_replace("`",'',$name);
          $name = str_replace("#",'',$name);
          $name = str_replace("\$",'',$name);
          $name = str_replace("%",'',$name);
          $name = str_replace("^",'',$name);
          $name = str_replace("&",'',$name);
          $name = str_replace("?",'',$name);
          $name = str_replace("~",'',$name);
          $this->string = $name;
    }

    public function get(){
        return $this->string;
    }
}