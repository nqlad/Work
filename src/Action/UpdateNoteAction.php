<?php

namespace App\Action;

use App\Database\PersisterInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Serialization\DeserializerInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UpdateNoteAction implements RequestHandlerInterface
{
    /** @var DeserializerInterface */
    private $deserialize;

    /** @var ValidatorInterface */
    private $validator;

    /** @var PersisterInterface */
    private $persister;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        DeserializerInterface $deserialize,
        ValidatorInterface $validator,
        PersisterInterface $persister,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->deserialize      = $deserialize;
        $this->validator        = $validator;
        $this->persister        = $persister;
        $this->responseFactory  = $responseFactory;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $requestBody            = $request->getBody();
        $note                   = $this->deserialize->deserialize($requestBody);

        $note->id               = (int) $request->getUri()->getPath();

        $violationList          = $this->validator->validate($note);

        $successfulUpdateNote   = $this->persister->updateNote($note);

        if (count($violationList) < 1 && $successfulUpdateNote) {
            $response           = $this->responseFactory->createNoteResponse($request, $note,200);
        } else {
            $violationList      += $this->validator->validateForNullNoteInDB($note);
            $response           = $this->responseFactory->createViolationListResponse($request, $violationList);
        }

        return $response;
    }
}
