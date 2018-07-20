<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

//header("Content-Type: application/json; charset=UTF-8");

require(__DIR__ . '/../vendor/autoload.php');

$deserializer       = new \App\Serialization\JsonSerializer();
$validator          = new \App\Validation\Validator();
$persister          = new \App\Database\PostgresDriver('pgsql:host=localhost;port=5432;dbname=postgres','postgres','yfNL4W');
$requestFactory     = new \App\Http\RequestFactory();
$responseFactory    = new \App\Http\ResponseFactory($deserializer);
$requestHandler     = new \App\Action\GetNoteAction($deserializer,$persister,$responseFactory,$validator);
$responseSender     = new \App\Http\ResponseSender();

$request = $requestFactory->createRequest($_SERVER);

$response = $requestHandler->handleRequest($request);
$responseSender->sendResponse($response);



//$kernel = new \App\Kernel();
//$kernel->run();