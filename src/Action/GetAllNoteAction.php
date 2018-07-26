<?php

namespace App\Action;


use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAllNoteAction implements RequestHandlerInterface
{
    /** @var FinderInterface */
    private $finder;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        FinderInterface $finder,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->finder           = $finder;
        $this->responseFactory  = $responseFactory;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteCollection = $this->finder->findAllNote();

        $response       = $this->responseFactory->createNoteCollection($request, $noteCollection,200);

        return $response;
    }
}
