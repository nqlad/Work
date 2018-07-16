<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements RequestHandlerInterface
{
    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $status             = $this->getStatus($request);
        $protocolVersion    = $this->getProtocolVersion($request);
        $headers            = $this->getHeaders($request);
        $body               = $this->getBody($request);
        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }

    private function getStatus(RequestInterface $request)
    {
        return 200;
    }

    private function getProtocolVersion(RequestInterface $request)
    {
        return $request->getProtocolVersion();
    }

    private function getHeaders(RequestInterface $request)
    {
        return $request->getHeaders();
    }

    private function getBody(RequestInterface $request)
    {
        return $request->getBody();
    }
}
