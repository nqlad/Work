<?php

namespace App\Action;

use App\Database\FinderInterface;
use App\Http\RequestHandlerInterface;
use App\Http\ResponseFactoryInterface;
use App\Validation\ValidatorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetNoteAction implements RequestHandlerInterface
{
    private const RESPONSE_STATUS_CODE = 200;

    /** @var FinderInterface */
    private $finder;

    /** @var ValidatorInterface */
    private $validator;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    public function __construct(
        FinderInterface $finder,
        ResponseFactoryInterface $responseFactory,
        ValidatorInterface $validator
    ) {
        $this->finder           = $finder;
        $this->responseFactory  = $responseFactory;
        $this->validator        = $validator;
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $noteId         = $request->getUri()->getPath();
        $note           = $this->finder->findOneNote($noteId);

        $violationList  = $this->validator->validateForNullNoteInDB($note);

        if (count($violationList) > 0) {
            $response   = $this->responseFactory->createViolationListResponse($request, $violationList);
        } else {
            $response   = $this->responseFactory->createNoteResponse($request, $note, self::RESPONSE_STATUS_CODE);
        }

        return $response;
    }
}
