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
$responseSender     = new \App\Http\ResponseSender();

$request = $requestFactory->createRequest($_SERVER);

if ($request->getMethod() === 'GET') {
    $requestTargets = explode('/',$request->getRequestTarget());
    $noteId         = end($requestTargets);

    if ($noteId !== 'notes') {
        $requestHandler  = new \App\Action\GetNoteAction($deserializer,$persister,$responseFactory,$validator);
    } else {
        $requestHandler = new \App\Action\GetAllNoteAction($deserializer,$persister,$responseFactory);
    }

} elseif ($request->getMethod() === 'POST') {
    $requestHandler = new \App\Action\PostNoteAction($deserializer,$persister,$responseFactory,$validator);
} elseif ($request->getMethod() === 'DELETE') {
    $requestHandler = new \App\Action\DeleteNoteAction($deserializer,$persister,$responseFactory,$validator);
} elseif ($request->getMethod() === 'PUT') {
    $requestHandler = new \App\Action\UpdateNoteAction($deserializer,$validator,$persister,$responseFactory);
}

$response = $requestHandler->handleRequest($request);
$responseSender->sendResponse($response);



//$kernel = new \App\Kernel();
//$kernel->run();