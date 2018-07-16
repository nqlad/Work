<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{

    private $uri;

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

        if(!in_array($method,$this->httpMethodsMap)){
            throw new \InvalidArgumentException('Error! Incorrect Http Method!');
        }

        $this->method = $method;
    }

    public function getRequestTarget()
    {
        $requestTarget = $this->uri->getPath() ? $this->uri->getPath() : '/';
        $requestTarget .= $this->uri->getQuery() ? '?' . $this->uri->getQuery() : '';

        $this->setRequestTarget($requestTarget);

        return $requestTarget;
    }

    /**
     * @param mixed $requestTarget
     */
    public function setRequestTarget($requestTarget): void
    {
        $this->requestTarget = $requestTarget;
    }

    public function withRequestTarget($requestTarget)
    {
        $request = clone $this;
        $request->setRequestTarget($requestTarget);

        return $request;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    public function withMethod($method)
    {
        $request = clone $this;
        $request->setMethod($method);

        return $request;
    }

    /**
     * @return Uri|UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param Uri|UriInterface $uri
     */
    public function setUri(UriInterface $uri): void
    {
        $this->uri = $uri;
    }

    //todo
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request    = clone $this;
        $requestUri = $request->getUri();

        if($preserveHost == false) {
            if ($uri->getHost() !== null) {
                $requestUri->setHost($uri->getHost());
            }
        } elseif($preserveHost == true){
            if(($this->uri->getHost() == null) and ($uri->getHost() !== null)){
                $requestUri->setHost($uri->getHost());
            }
        }

        $request->setUri($requestUri);

        return $request;
    }
}
