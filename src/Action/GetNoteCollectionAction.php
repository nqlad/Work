<?php

namespace App\Action;

use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetNoteCollectionAction implements RequestHandlerInterface
{
    private const RESPONSE_STATUS_CODE = 200;

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
        $noteCollection = $this->finder->findNoteCollection();

        return $this->responseFactory->createNoteCollection($request, $noteCollection, self::RESPONSE_STATUS_CODE);
    }
}
