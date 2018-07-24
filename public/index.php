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

$getNote            = new \App\Action\GetNoteAction($deserializer,$persister,$responseFactory,$validator);
$getNoteCollection  = new \App\Action\GetAllNoteAction($deserializer,$persister,$responseFactory);
$deleteNote         = new \App\Action\DeleteNoteAction($deserializer,$persister,$responseFactory,$validator);
$postNote           = new \App\Action\PostNoteAction($deserializer,$persister,$responseFactory,$validator);
$putNote            = new \App\Action\UpdateNoteAction($deserializer,$validator,$persister,$responseFactory);

$router = new \App\RequestHandler\RoutingHandler($responseFactory, $getNoteCollection,$getNote,$deleteNote,$postNote,$putNote);

$kernel = new \App\Kernel(
    $requestFactory,
    $router,
    $responseSender
);
$kernel->run();
