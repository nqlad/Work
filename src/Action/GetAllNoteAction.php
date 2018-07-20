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
        $this->deserialize = $deserialize;
        $this->finder = $finder;
        $this->responseFactory = $responseFactory;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $this->responseFactory->setRequest($request);

        $response = $this->responseFactory->createFindAllNoteResponse($this->finder->findAllNote());

        return $response;
    }
}
