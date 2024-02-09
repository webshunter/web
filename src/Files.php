<?php
namespace Gugusd999\Web;

class Files
{
    public static function exist(...$arg)
    {
        if(isset($arg[0]))
        {
            if(file_exists($arg[0]))
            {
                return true;
            }else{
                return false;
            }
        }
    }

    public static function slug(...$arg){
        if(isset($arg[0])){
            $name = $arg[0];
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
            return strtolower($name);
        }
    }

    public static function remove(...$arg){
        if(isset($arg[0])){
            if(file_exists($arg[0])
                && $arg[0] != ''
                && $arg != '/'
                && $arg != 'web'
                && $arg != 'views'
                && $arg != 'vendor'
                && $arg != 'module'
            ){
                unlink($arg[0]);
            }
        }
    }

    public static function write(...$arg)
    {
        if(isset($arg[0]) && isset($arg[1]))
        {
            $myfile = fopen($arg[0], "w") or die("Unable to open file!");
            $txt = $arg[1];
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }

    public static function read(...$arg)
    {
        $path = $arg[0];
        if(!file_exists($path)){
            return null;
        }
        $myfile = fopen($path, "r") or die("Unable to open file!");
        $er = fread($myfile,filesize($path));
        fclose($myfile);
        return $er;
    }

}