<?php

namespace App\RequestHandler;

use App\Action\DeleteNoteAction;
use App\Action\GetNoteCollectionAction;
use App\Action\GetNoteAction;
use App\Action\PostNoteAction;
use App\Action\UpdateNoteAction;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Http\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RoutingHandler implements RequestHandlerInterface
{
    /** @var GetNoteCollectionAction */
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
        GetNoteCollectionAction $getAllNoteAction,
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
        $route              = $this->routeParser->parseRouteFromUri($request);

        $resourceRequest    = $request->withUri(new Uri($route->getResourceId()));

        if ($route->getMethod() === 'POST' && $route->getResourceId() === null) {
            $response       = $this->postNoteAction->handleRequest($resourceRequest);
        } elseif ($route->getMethod() === 'PUT' && $route->getResourceId() !== null) {
            $response       = $this->putNoteAction->handleRequest($resourceRequest);
        } elseif ($route->getMethod() === 'DELETE' && $route->getResourceId() !== null) {
            $response       = $this->deleteNoteAction->handleRequest($resourceRequest);
        } elseif ($route->getMethod() === 'GET') {
            $resourceId     = $route->getResourceId();

            if ($resourceId === null) {
                $response   = $this->getAllNoteAction->handleRequest($resourceRequest);
            } else {
                $response   = $this->getNoteAction->handleRequest($resourceRequest);
            }

        } else {
            $response       = $this->responseFactory->createNotFoundResponse($resourceRequest);
        }

        return $response;
    }
}
