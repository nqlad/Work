<?php

namespace App\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /** @var Uri */
    private $uri;

    /** @var string */
    private $method;

    private $httpMethodsMap = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'OPTIONS',
        'PATCH',
    ];

    /** @var string */
    private $requestTarget;

    /**
     * Request constructor.
     */
    public function __construct(
        UriInterface $uri,
        string $method,
        string $protocolVersion,
        array $headers,
        StringStream $body
    ) {
        parent::__construct($protocolVersion, $headers, $body);

        $this->uri = $uri;

        if (!in_array($method, $this->httpMethodsMap, true)) {
            throw new \InvalidArgumentException('Error! Incorrect Http Method!');
        }

        $this->method = $method;

        $this->requestTarget = $this->uri->getPath() ? $this->uri->getPath() : '/';
        $this->requestTarget .= $this->uri->getQuery() ? '?'.$this->uri->getQuery() : '';
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    private function setRequestTarget($requestTarget): void
    {
        $this->requestTarget = $requestTarget;
    }

    /**
     * @param mixed $requestTarget
     */
    public function withRequestTarget($requestTarget): RequestInterface
    {
        $request = clone $this;
        $request->setRequestTarget($requestTarget);

        return $request;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    private function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @param string $method
     */
    public function withMethod($method): RequestInterface
    {
        $request = clone $this;

        if (!in_array($method, $this->httpMethodsMap, true)) {
            throw new \InvalidArgumentException('Invalid Http Method!');
        }

        $request->setMethod($method);

        return $request;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function setUri(UriInterface $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @param bool $preserveHost
     */
    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $request    = clone $this;

        if (false === $preserveHost) {
            if ('' === $uri->getHost()) {
                $request->setUri($uri->withHost($this->uri->getHost()));
            } else {
                $request->setUri($uri);
            }
        } elseif (true === $preserveHost) {
            if (('' !== $this->uri->getHost())) {
                $request->setUri($uri->withHost($this->uri->getHost()));
            } else {
                $request->setUri($uri);
            }
        }

        return $request;
    }
}
