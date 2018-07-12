<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    private $uri;

    private $method;

    //private $requestTarget;

    public function __construct(
        UriInterface $uri,
        $method
    ) {
        $this->uri     = $uri;
        $this->method   = $method;
    }

    public function getRequestTarget()
    {
//        if ($this->requestTarget !== null) {
//            return $this->requestTarget;
//        }
//        $target = $this->uri->getPath();
//        if ($target == '') {
//            $target = '/';
//        }
//        if ($this->uri->getQuery() != '') {
//            $target .= '?' . $this->uri->getQuery();
//        }
//        var_dump($target);
//        return $target;
    }

    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $request = clone $this;
        $request->setMethod($method);

        return $request;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request = clone $this;
        //todo

        return $request;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @param UriInterface $uri
     */
    public function setUri(UriInterface $uri): void
    {
        $this->uri = $uri;
    }
}
