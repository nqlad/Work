<?php

namespace App\Action;


use App\Http\RequestHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostNoteAction implements RequestHandlerInterface
{
    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        // deserialization request body to Note
        // Note validation
        // if violations then create and return violation response
        // persist note to database
        // create and return note response
    }
}
