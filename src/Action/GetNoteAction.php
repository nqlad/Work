<?php

namespace App\Action;


use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Serialization\DeserializerInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetNoteAction implements RequestHandlerInterface
{
    /** @var DeserializerInterface */
    private  $deserialize;

    /** @var FinderInterface */
    private $finder;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        DeserializerInterface $deserialize,
        FinderInterface $finder,
        ResponseFactoryInterface $responseFactory,
        ValidatorInterface $validator
    ) {
        $this->deserialize      = $deserialize;
        $this->finder           = $finder;
        $this->responseFactory  = $responseFactory;
        $this->validator        = $validator;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteId         = $this->getNoteIdFromUri($request);
        $note           = $this->finder->findOneNote($noteId);

        if ($note === null) {
            $response = $this->responseFactory->createNotFoundResponse($request);

            return $response;
        }

        $violationList  = $this->validator->validateForNullNoteInDB($note);

        if (count($violationList) > 0) {
            $response   = $this->responseFactory->createViolationListResponse($request, $violationList);
        } else {
            $response   = $this->responseFactory->createNoteResponse($request, $note,200);
        }

        return $response;
    }

    private function getNoteIdFromUri(RequestInterface $request): string
    {
        $requestTargets = explode('/',$request->getRequestTarget());

        return end($requestTargets);
    }
}
