<?php
namespace Gugusd999\Web;
use Jenssegers\Blade\Blade;

class View {

    public static function render($name = '', $arg = []){
        $pathView = defined("VIEW_PATH")?VIEW_PATH:"view";
        $chache = SETUP_PATH.$pathView.'/chache';
        if(!file_exists($chache)){
            mkdir($chache, 777,true);
        }
        $blade = new Blade(SETUP_PATH.$pathView.'', 'cache');
        $bld = $blade->make($name, $arg)->render();
        echo $bld;
    }
    
    public static function get($path="",$name = '', $arg = []){
        $pathView = $path;
        $chache =$path.'/chache';
        if(!file_exists($chache)){
            mkdir($chache, 777,true);
        }
        $blade = new Blade($pathView.'', 'cache');
        $bld = $blade->make($name, $arg)->render();
        return $bld;
    }
    
}
