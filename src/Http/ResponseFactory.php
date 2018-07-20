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

    public function createPostNoteResponse(Note $note): ResponseInterface
    {
        $status             = 200;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForNote($note);

        $response           = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }

    private function getBodyForNote(Note $note): StreamInterface
    {
        return new StringStream($this->deserializer->serialize($note));
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

    private function getBodyForViolationResponse(array $violationList): StreamInterface
    {
        $body = [];

        foreach ($violationList as $violation) {
            array_push($body,$this->deserializer->serialize($violation));
        }

        return new StringStream(implode($body));
    }

    public function createFindAllNoteResponse(array $notes): ResponseInterface
    {
        $status             = 200;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForFindAllResponse($notes);

        $response = new Response($status, $protocolVersion, $headers, $body);

        if (count($notes) === 0) {
            $response = $response->withStatus(204);
        }

        return $response;
    }

    private function getBodyForFindAllResponse(array $notes): StreamInterface
    {
        $body = $this->deserializer->serialize($notes);

        return new StringStream($body);
    }

    public function createFindNoteResponse(Note $note): ResponseInterface
    {
        $status             = 200;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForNote($note);

        $response = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }

    public function createDeleteNoteResponse(Note $note): ResponseInterface
    {
        $status             = 200;
        $protocolVersion    = $this->request->getProtocolVersion();
        $headers            = $this->request->getHeaders();
        $body               = $this->getBodyForNote($note);

        $response = new Response($status, $protocolVersion, $headers, $body);

        return $response;
    }
}
