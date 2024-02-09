<?php

require_once '../vendor/autoload.php';
require_once '../src/HtmlPrint.php';
require_once '../src/ErrorHandler.php';
require_once '../src/Files.php';
require_once '../src/Text.php';
require_once '../src/View.php';
require_once '../src/Route.php';

use Gugusd999\Web\Route;
use Gugusd999\Web\ErrorHandler;
use Gugusd999\Web\HtmlPrint;
use Gugusd999\Web\Files;

$route = new Route("./", "/tests");

$route->add('/', function($file=""){
    $print = new HtmlPrint();

    $print->section(json_decode(Files::read(__DIR__."/print.json"), true));

    $print->render();
});

$route->call();