<?php

namespace App\Http;


use App\Entity\Note;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createNoteResponse(Note $note): ResponseInterface;

    public function createViolationListResponse(array $violationList): ResponseInterface;

    public function setRequest(RequestInterface $request): void;
}
