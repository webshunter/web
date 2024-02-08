<?php
namespace Gugusd999\Web;

class Load{
    public function __construct(...$arg){
        foreach ($arg as $argument){
            include_once SETUP_PATH.$argument.'.php';
        }
    }
}