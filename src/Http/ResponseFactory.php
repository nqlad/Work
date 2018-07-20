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

    /** @var RequestInterface */
    private $request;

    public function __construct(SerializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function createNoteResponse(Note $note): ResponseInterface
    {
        $status             = 200;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForNote($note);

        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }

    public function createViolationListResponse(array $violationList): ResponseInterface
    {

        $status             = 400;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForViolationResponse($violationList);

        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }


    private function getBodyForNote(Note $note): StreamInterface
    {
        return new StringStream($this->deserializer->serialize($note));
    }

    private function getBodyForViolationResponse(array $violationList): StreamInterface
    {
        $body = [];

        foreach ($violationList as $violation) {
            array_push($body,$this->deserializer->serialize($violation));
        }

        return new StringStream(implode($body));
    }
}
