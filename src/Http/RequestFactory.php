<?php

namespace App\Http;


use App\Model\Request;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest()
    {
        $url        = $this->getUri();
        $method     = $this->getMethod();

        $body = new StringStream($contents);
        $request    = new Request($url, $method);
        return $request;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUri()
    {
        $url    = $_SERVER['REQUEST_URI'];
        $url    = ltrim($url, '/');
        $urls   = explode('/', $url);
        return $urls;
    }
}
