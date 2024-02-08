<?php
namespace Gugusd999\Web;

class Session{

    public static function put($name = "", $data_arr = [])
    {
        $_SESSION[$name.SESSION] = $data_arr;
    }

    public static function delete($name = '')
    {
        unset($_SESSION[$name.SESSION]);
    }

    public static function get($name = "", $defaultnull = "")
    {
        if(isset($_SESSION[$name.SESSION])){
            if ($_SESSION[$name.SESSION] != "") {
                return $_SESSION[$name.SESSION];
            }else{
                return $defaultnull;
            }
        }else{
            if ($defaultnull != "") {
                return $defaultnull;
            }else{
                return "";
            }
        }
    }
}