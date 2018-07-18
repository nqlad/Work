<?php

namespace App\Action;


use App\Database\PostgresDriver;
use App\Http\RequestHandlerInterface;
use App\Http\Response;
use App\Http\StringStream;
use App\Serialization\Deserialization;
use App\Validation\Validation;
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
        $requestBody = $this->getBody($request);

        $deserialazer   = new Deserialization();

        $note = $deserialazer->deserialize($requestBody);

        $validation = new Validation();
        $violations = $validation->validate($note);

        if (!sizeof($violations) == 0) {
            $status = $this->getStatus($violations);
        } else {
            $databaseDriver = new PostgresDriver('pgsql:host=localhost;port=5432;dbname=postgres','postgres','yfNL4W');

            $note       = $databaseDriver->persist($note);
            $status     = $this->getStatus($violations);
            $responseBody = new StringStream($note->__toString());
        }

        $protocolVersion    = $this->getProtocolVersion($request);
        $headers            = $this->getHeaders($request);
        $response           = new Response($status, $protocolVersion, $headers, $responseBody);
        var_dump($response);

        return $response;
    }

    private function getStatus(array $violations)
    {
        $violations === null ? $status = 400 : $status = 200;

        return $status;
    }

    private function getProtocolVersion(RequestInterface $request)
    {
        return $request->getProtocolVersion();
    }

    private function getHeaders(RequestInterface $request)
    {
        return $request->getHeaders();
    }

    private function getBody(RequestInterface $request)
    {
        return $request->getBody();
    }
}
