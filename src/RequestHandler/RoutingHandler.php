<?php

namespace App\RequestHandler;


use App\Action\DeleteNoteAction;
use App\Action\GetAllNoteAction;
use App\Action\GetNoteAction;
use App\Action\PostNoteAction;
use App\Action\UpdateNoteAction;
use App\Http\RequestFactoryInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseSenderInterface;
use App\Kernel;
use Psr\Http\Message\RequestInterface;

class RoutingHandler
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

    /** @var RequestFactoryInterface*/
    private $requestFactory;

    /** @var RequestHandlerInterface */
    private $requestHandler;

    /** @var ResponseSenderInterface */
    private $responseSender;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        ResponseSenderInterface $responseSender,
        GetAllNoteAction $getAllNoteAction,
        GetNoteAction $getNoteAction,
        DeleteNoteAction $deleteNoteAction,
        PostNoteAction $postNoteAction,
        UpdateNoteAction $putNoteAction
    ){
        $this->requestFactory   = $requestFactory;
        $this->responseSender   = $responseSender;
        $this->getAllNoteAction = $getAllNoteAction;
        $this->getNoteAction    = $getNoteAction;
        $this->deleteNoteAction = $deleteNoteAction;
        $this->postNoteAction   = $postNoteAction;
        $this->putNoteAction    = $putNoteAction;
    }


    public function handleRequest(RequestInterface $request): void
    {
        if ($request->getMethod() === 'POST') {

            $this->requestHandler = $this->postNoteAction;

        } elseif ($request->getMethod() === 'PUT') {

            $this->requestHandler = $this->putNoteAction;

        } elseif ($request->getMethod() === 'DELETE') {

            $this->requestHandler = $this->deleteNoteAction;

        } elseif ($request->getMethod() === 'GET') {

            $requestTargets = explode('/',$request->getRequestTarget());
            $noteId         = end($requestTargets);

            if ($noteId !== 'notes') {
                $this->requestHandler = $this->getNoteAction;
            } else {
                $this->requestHandler = $this->getAllNoteAction;
            }
        }

        $kernel = new Kernel($this->requestFactory,$this->requestHandler,$this->responseSender);
        $kernel->run();
    }
}