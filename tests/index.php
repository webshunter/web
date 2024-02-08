<?php

require_once '../src/ErrorHandler.php';
require_once '../src/View.php';
require_once '../src/Route.php';

use Gugusd999\Web\Route;
use Gugusd999\Web\ErrorHandler;

$route = new Route("./", "/tests");

$route->add('/', function($file=""){
    echo "ok";
    // echo $rop;
});

$route->call();