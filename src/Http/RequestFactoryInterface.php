<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    public function createRequest(array $serverConfig): RequestInterface;
}
