<?php

namespace App\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(array $serverConfig): RequestInterface
    {
        $uri                = $this->getUri($serverConfig);
        $method             = $this->getMethod($serverConfig);
        $protocolVersion    = $this->getProtocolVersion($serverConfig);
        $headers            = $this->getHeaders($serverConfig);
        $body               = $this->getBody();

        return new Request($uri, $method, $protocolVersion, $headers, $body);
    }

    private function getHeaders(array $serverConfig): array
    {
        $headers = [''=>['']];

        foreach ($serverConfig as $name => $values) {
            if (0 === strncmp($name, 'HTTP_', 5)) {
                $name       = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $value      = explode(',', $values);
                $headers += [$name => $value];
            }
        }

        unset($headers['']);

        return $headers;
    }

    private function getUri(array $serverConfig): UriInterface
    {
        return new Uri($serverConfig['REQUEST_URI']);
    }

    private function getMethod(array $serverConfig): string
    {
        return $serverConfig['REQUEST_METHOD'];
    }

    private function getProtocolVersion(array $serverConfig): string
    {
        $serverProtocol = explode('/', $serverConfig['SERVER_PROTOCOL']);

        return $serverProtocol[1];
    }

    private function getBody(): StringStream
    {
        return new StringStream(file_get_contents('php://input'));
    }
}
