<?php

namespace App\Http;


use App\Http\Request;
use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{

    public function createRequest(): RequestInterface
    {
        $method = $server;
        $request = new Request((met));
        return $request;
    }
}
