<?php
namespace Gugusd999\Web;

class Link{
    public static function redirect(...$arg){
        if(isset($arg[0])){
            $url = PATH.$arg[0];
            echo "<script>";
            echo "location.href = '$url'";
            echo "</script>";
            die();
        }
    }
}