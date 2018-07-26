<?php

namespace App\Action;


use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetNoteAction implements RequestHandlerInterface
{
    /** @var FinderInterface */
    private $finder;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        FinderInterface $finder,
        ResponseFactoryInterface $responseFactory,
        ValidatorInterface $validator
    ) {
        $this->finder           = $finder;
        $this->responseFactory  = $responseFactory;
        $this->validator        = $validator;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteId         = $request->getRequestTarget();
        $note           = $this->finder->findOneNote($noteId);

        if ($note === null) {
            $response   = $this->responseFactory->createNotFoundResponse($request);
        } else {
            $response   = $this->responseFactory->createNoteResponse($request, $note,200);
        }

        return $response;
    }
}
