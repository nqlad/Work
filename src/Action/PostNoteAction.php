<?php

namespace App\Action;


use App\Database\PersisterInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Http\StringStream;
use App\Serialization\DeserializerInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PostNoteAction implements RequestHandlerInterface
{
    /** @var DeserializerInterface */
    private $deserializer;

    /** @var ValidatorInterface */
    private $validator;

    /** @var PersisterInterface */
    private $persister;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        DeserializerInterface $deserializer,
        PersisterInterface $persister,
        ResponseFactoryInterface $responseFactory,
        ValidatorInterface $validator
    ) {
        $this->deserializer     = $deserializer;
        $this->persister        = $persister;
        $this->responseFactory  = $responseFactory;
        $this->validator        = $validator;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $this->responseFactory->setRequest($request);

        $requestBody    = $this->getBody($request);
        $note           = $this->deserializer->deserialize($requestBody);

        $validationList = $this->validator->validate($note);

        if (count($validationList) > 0) {
            $response = $this->responseFactory->createViolationListResponse($validationList);
        } else {
            $response = $this->responseFactory->createPostNoteResponse($this->persister->persist($note));
        }

        return $response;
    }

    private function getBody(RequestInterface $request): StreamInterface
    {
        return $request->getBody();
    }
}
