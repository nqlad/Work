<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /** @var Uri */
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

        $this->requestTarget = $this->uri->getPath() ? $this->uri->getPath() : '/';
        $this->requestTarget .= $this->uri->getQuery() ? '?' . $this->uri->getQuery() : '';
    }

    /**
     * @return string
     */
    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    /**
     * @param mixed $requestTarget
     */
    private function setRequestTarget($requestTarget): void
    {
        $this->requestTarget = $requestTarget;
    }

    /**
     * @param mixed $requestTarget
     * @return Request|RequestInterface
     */
    public function withRequestTarget($requestTarget)
    {
        $request = clone $this;
        $request->setRequestTarget($requestTarget);

        return $request;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    private function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @param string $method
     * @return Request|RequestInterface
     */
    public function withMethod($method)
    {
        $request = clone $this;

        if(!in_array($method,$this->httpMethodsMap)){
            throw new \InvalidArgumentException('Invalid Http Method!');
        }

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
     * @param Uri $uri
     */
    public function setUri(Uri $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @return Request|RequestInterface
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request    = clone $this;


        if($preserveHost == false) {
            if ($uri->getHost() !== '') {
                $request->setUri($this->uri->withHost(($uri->getHost())));
            }
        } elseif($preserveHost == true){
            if(($this->uri->getHost() == null) and ($uri->getHost() !== null)){
                $request->setUri($this->uri->withHost(($uri->getHost())));
            }
        }

        return $request;
    }
}
