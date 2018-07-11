<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestHandlerInterface
{
    public function handleRequest(RequestInterface $request): ResponseInterface;
}