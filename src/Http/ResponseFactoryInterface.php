<?php

namespace App\Http;


use App\Entity\Note;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createNoteResponse(RequestInterface $request,Note $note,int $statusCode):ResponseInterface;

    public function createNoteCollection(RequestInterface $request,array $notes,int $statusCode): ResponseInterface;

    public function createViolationListResponse(RequestInterface $request, array $violationList): ResponseInterface;

    public function createNotFoundResponse(RequestInterface $request):ResponseInterface;
}
