<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

//header("Content-Type: application/json; charset=UTF-8");

require(__DIR__ . '/../vendor/autoload.php');

$requestFactory = new \App\Http\RequestFactory();
$requestHandler = new \App\Action\PostNoteAction();
$request = $requestFactory->createRequest($_SERVER);
$response = $requestHandler->handleRequest($request);
//$this->responseSender->sendResponse($response);



//$kernel = new \App\Kernel();
//$kernel->run();
