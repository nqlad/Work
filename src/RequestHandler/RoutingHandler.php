<?php

namespace App\RequestHandler;


use App\Action\DeleteNoteAction;
use App\Action\GetAllNoteAction;
use App\Action\GetNoteAction;
use App\Action\PostNoteAction;
use App\Action\UpdateNoteAction;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RoutingHandler implements RequestHandlerInterface
{
    /** @var GetAllNoteAction */
    private $getAllNoteAction;

    /** @var GetNoteAction */
    private $getNoteAction;

    /** @var DeleteNoteAction */
    private $deleteNoteAction;

    /** @var PostNoteAction */
    private $postNoteAction;

    /** @var UpdateNoteAction */
    private $putNoteAction;

    /** @var ResponseFactoryInterface*/
    private $responseFactory;

    /** @var RouteParser */
    private $routeParser;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        GetAllNoteAction $getAllNoteAction,
        GetNoteAction $getNoteAction,
        DeleteNoteAction $deleteNoteAction,
        PostNoteAction $postNoteAction,
        UpdateNoteAction $putNoteAction,
        RouteParser $routeParser
    ){
        $this->responseFactory  = $responseFactory;
        $this->getAllNoteAction = $getAllNoteAction;
        $this->getNoteAction    = $getNoteAction;
        $this->deleteNoteAction = $deleteNoteAction;
        $this->postNoteAction   = $postNoteAction;
        $this->putNoteAction    = $putNoteAction;
        $this->routeParser      = $routeParser;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $route = $this->routeParser->parseRouteFromUri($request);

        if ($route->getMethod() === 'POST') {

            $response       = $this->postNoteAction->handleRequest($request);

        } elseif ($route->getMethod() === 'PUT') {

            $response       = $this->putNoteAction->handleRequest($request);

        } elseif ($route->getMethod() === 'DELETE') {

            $response       = $this->deleteNoteAction->handleRequest($request);

        } elseif ($route->getMethod() === 'GET') {

            $noteId         = $route->getResourceId();

            if ($noteId === null) {
                $response   = $this->getAllNoteAction->handleRequest($request);
            } else {
                $response   = $this->getNoteAction->handleRequest($request);
            }
        } else {

            $response       = $this->responseFactory->createNotFoundResponse($request);

        }

        return $response;
    }
}
