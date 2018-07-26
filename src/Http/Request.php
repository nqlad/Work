<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /** @var Uri */
    private $uri;

    /** @var string  */
    private $method;

    private $httpMethodsMap = [
        'GET',
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'OPTIONS',
        'PATCH'
    ];

    /** @var string  */
    private $requestTarget;

    /**
     * Request constructor.
     * @param UriInterface $uri
     * @param string $method
     * @param string $protocolVersion
     * @param array $headers
     * @param StringStream $body
     */
    public function __construct(
        UriInterface $uri,
        string $method,
        string $protocolVersion,
        array $headers,
        StringStream $body
    ){
        parent::__construct($protocolVersion,$headers,$body);

        $this->uri = $uri;

        if (!in_array($method,$this->httpMethodsMap)) {
            throw new \InvalidArgumentException('Error! Incorrect Http Method!');
        }

        $this->method = $method;

        $this->requestTarget = $this->uri->getPath() ? $this->uri->getPath() : '/';
        $this->requestTarget .= $this->uri->getQuery() ? '?' . $this->uri->getQuery() : '';
    }

    public function getRequestTarget(): ?string
    {
        return $this->requestTarget;
    }

    private function setRequestTarget($requestTarget): void
    {
        $this->requestTarget = $requestTarget;
    }

    /**
     * @param mixed $requestTarget
     * @return RequestInterface
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
     * @return RequestInterface
     */
    public function withMethod($method): RequestInterface
    {
        $request = clone $this;

        if (!in_array($method,$this->httpMethodsMap)) {
            throw new \InvalidArgumentException('Invalid Http Method!');
        }

        $request->setMethod($method);

        return $request;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }


    public function setUri(Uri $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $request    = clone $this;


        if ($preserveHost == false) {
            if ($uri->getHost() !== '') {
                $request->setUri($this->uri->withHost(($uri->getHost())));
            }
        } elseif ($preserveHost == true) {
            if (($this->uri->getHost() == null) and ($uri->getHost() !== null)) {
                $request->setUri($this->uri->withHost(($uri->getHost())));
            }
        }

        return $request;
    }
}
