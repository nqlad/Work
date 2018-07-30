<?php

namespace App\Http;

use App\Entity\Note;
use App\Serialization\SerializerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    /** @var SerializerInterface */
    private $deserializer;

    public function __construct(SerializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function createNoteResponse(RequestInterface $request, Note $note, int $statusCode): ResponseInterface
    {
        $protocolVersion    = $request->getProtocolVersion();
        $headers            = $request->getHeaders();
        $body               = $this->getBodyForNote($note);

        $response = new Response($statusCode, $protocolVersion, $headers, $body);

        return $response;
    }

    private function getBodyForNote(Note $note): StreamInterface
    {
        return new StringStream($this->deserializer->serialize($note));
    }

    public function createNoteCollection(RequestInterface $request, array $notes, int $statusCode): ResponseInterface
    {
        $protocolVersion    = $request->getProtocolVersion();
        $headers            = $request->getHeaders();
        $body               = $this->getBodyForNoteCollection($notes);

        $response = new Response($statusCode, $protocolVersion, $headers, $body);

        return $response;
    }

    private function getBodyForNoteCollection(array $notes): StreamInterface
    {
        $body = $this->deserializer->serialize($notes);

        return new StringStream($body);
    }

    public function createViolationListResponse(RequestInterface $request, array $violationList): ResponseInterface
    {
        $status             = 400;
        $protocolVersion    = $request->getProtocolVersion();
        $headers            = $request->getHeaders();
        $body               = $this->getBodyForViolationResponse($violationList);

        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }

    private function getBodyForViolationResponse(array $violationList): StreamInterface
    {
        $body = [];

        foreach ($violationList as $violation) {
            array_push($body,$this->deserializer->serialize($violation));
        }

        return new StringStream(implode($body));
    }

    public function createNotFoundResponse(RequestInterface $request): ResponseInterface
    {
        $status             = 404;
        $protocolVersion    = $request->getProtocolVersion();
        $headers            = $request->getHeaders();
        $body               = new StringStream('');

        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }
}
