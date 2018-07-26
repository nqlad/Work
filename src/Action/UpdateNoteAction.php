<?php

namespace App\Action;


use App\Database\PersisterInterface;
use App\Entity\Note;
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
        $requestBody    = $request->getBody();
        $note           = $this->deserialize->deserialize($requestBody);

        $note->id       = $request->getRequestTarget() === null ? null : (int) $request->getRequestTarget();

        $violationList  = $this->validator->validate($note);
        $violationList  += $this->validator->validateForNullNoteInDB($note);

        $successfulUpdateNote = $this->persister->updateNote($note);

        if (count($violationList) > 0 or !$successfulUpdateNote) {
            $response   = $this->responseFactory->createViolationListResponse($request, $violationList);
        } else {
            $response   = $this->responseFactory->createNoteResponse($request, $note,200);
        }

        return $response;
    }
}
