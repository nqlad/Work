<?php

namespace App;

use App\Http\RequestFactoryInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseSenderInterface;

class Kernel
{
    /** @var RequestFactoryInterface*/
    private $requestFactory;
    /** @var RequestHandlerInterface */
    private $requestHandler;
    /** @var ResponseSenderInterface */
    private $responseSender;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        RequestHandlerInterface $requestHandler,
        ResponseSenderInterface $responseSender
    ) {
        $this->requestFactory = $requestFactory;
        $this->requestHandler = $requestHandler;
        $this->responseSender = $responseSender;
    }
    public function run(): void
    {
        $request = $this->requestFactory->createRequest($_SERVER);
        $response = $this->requestHandler->handleRequest($request);
        $this->responseSender->sendResponse($response);
    }
}
