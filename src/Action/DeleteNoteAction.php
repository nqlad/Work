<?php

namespace App\Action;

use App\Database\PersisterInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Serialization\DeserializerInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DeleteNoteAction implements RequestHandlerInterface
{
    private const RESPONSE_STATUS_CODE = 200;

    /** @var DeserializerInterface */
    private $deserialize;

    /** @var PersisterInterface */
    private $persister;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        DeserializerInterface $deserialize,
        PersisterInterface $persister,
        ResponseFactoryInterface $responseFactory,
        ValidatorInterface $validator
    ) {
        $this->deserialize      = $deserialize;
        $this->persister        = $persister;
        $this->responseFactory  = $responseFactory;
        $this->validator        = $validator;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteId         = $request->getUri()->getPath();
        $note           = $this->persister->deleteNote($noteId);

        $violationList  = $this->validator->validateForNullNoteInDB($note);

        if (count($violationList) > 0) {
            $response   = $this->responseFactory->createViolationListResponse($request, $violationList);
        } else {
            $response   = $this->responseFactory->createNoteResponse($request, $note, self::RESPONSE_STATUS_CODE);
        }

        return $response;
    }
}
