<?php

namespace App\Tests\Unit;

use App\Http\RequestFactoryInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseSenderInterface;
use App\Kernel;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class KernelTest extends TestCase
{
    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var RequestHandlerInterface */
    private $requestHandler;

    /** @var ResponseSenderInterface */
    private $responseSender;

    protected function setUp(): void
    {
        $this->requestFactory = \Phake::mock(RequestFactoryInterface::class);
        $this->requestHandler = \Phake::mock(RequestHandlerInterface::class);
        $this->responseSender = \Phake::mock(ResponseSenderInterface::class);
    }

    /** @test */
    public function run_noParameters_requestCreatedAndHandledAndResponseSent(): void
    {
        $kernel     = $this->createKernel();

        $request    = $this->givenRequestFactory_createRequest_returnsRequest();
        $response   = $this->givenRequestHandler_handleRequest_returnsResponse();

        $kernel->run();

        $this->assertRequestFactory_createRequest_isCalledOnceWithAnyParameters();
        $this->assertRequestHandler_handleRequest_isCalledOnceWithRequest($request);
        $this->assertResponseSender_sendResponse_isCalledOnceWithResponse($response);
    }

    private function createKernel(): Kernel
    {
        return new Kernel($this->requestFactory, $this->requestHandler, $this->responseSender);
    }

    private function assertRequestFactory_createRequest_isCalledOnceWithAnyParameters(): void
    {
        \Phake::verify($this->requestFactory, \Phake::times(1))
            ->createRequest(\Phake::anyParameters());
    }

    private function assertRequestHandler_handleRequest_isCalledOnceWithRequest(RequestInterface $request): void
    {
        \Phake::verify($this->requestHandler, \Phake::times(1))
            ->handleRequest($request);
    }

    private function assertResponseSender_sendResponse_isCalledOnceWithResponse(ResponseInterface $response): void
    {
        \Phake::verify($this->responseSender, \Phake::times(1))
            ->sendResponse($response);
    }

    private function givenRequestFactory_createRequest_returnsRequest(): RequestInterface
    {
        $request = \Phake::mock(RequestInterface::class);

        \Phake::when($this->requestFactory)
            ->createRequest(\Phake::anyParameters())
            ->thenReturn($request);

        return $request;
    }

    private function givenRequestHandler_handleRequest_returnsResponse(): ResponseInterface
    {
        $response = \Phake::mock(ResponseInterface::class);

        \Phake::when($this->requestHandler)
            ->handleRequest(\Phake::anyParameters())
            ->thenReturn($response);

        return $response;
    }
}
