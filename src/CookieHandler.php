<?php
namespace Gugusd999\Web;

class CookieHandler {
    // Fungsi untuk membuat cookie
    public static function setCookie($name, $value, $expire = 0, $path = '/') {
        if(!file_exists(SETUP_PATH.'cookie_files')){
            mkdir(SETUP_PATH.'cookie_files',0777, true);
        }
        if($name && $name != ''){
            files::write(SETUP_PATH.'cookie_files/'.$name.".txt", base64_encode( json_encode( $value ,true) ));
        }
    }

    // Fungsi untuk membaca nilai cookie
    public static function getCookie($name) {
        if(!file_exists(SETUP_PATH.'cookie_files')){
            mkdir(SETUP_PATH.'cookie_files',0777, true);
        }
        $file = SETUP_PATH.'cookie_files/'.$name.".txt";
        if($name && $name != '' && file_exists($file)){
            return json_decode( base64_decode( files::read($file) ) , true);
        }else{
            return null;
        }
    }
}