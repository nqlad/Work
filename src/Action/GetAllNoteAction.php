<?php

namespace App\Action;


use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Serialization\DeserializerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAllNoteAction implements RequestHandlerInterface
{
    /** @var DeserializerInterface */
    private $deserialize;

    /** @var FinderInterface */
    private $finder;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        DeserializerInterface $deserialize,
        FinderInterface $finder,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->deserialize      = $deserialize;
        $this->finder           = $finder;
        $this->responseFactory  = $responseFactory;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteCollection = $this->finder->findAllNote();

        if (count($noteCollection) > 0) {
            $response = $this->responseFactory->createNoteCollection($request, $noteCollection,200);
        } else {
            $response = $this->responseFactory->createNoteCollection($request, $noteCollection,204);
        }

        return $response;
    }
}
