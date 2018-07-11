<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

//header("Content-Type: application/json; charset=UTF-8");

require(__DIR__ . '/../vendor/autoload.php');

$uri = new \App\Http\Uri("https://ru.stackoverflow.com/questions/584438/");
$t=new \App\Http\Request($uri,"GET");
var_dump($t->getRequestTarget());
//$kernel = new \App\Kernel();
//$kernel->run();

