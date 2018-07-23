<?php

namespace App\Http;


use App\Entity\Note;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    // createNoteResponse($request, Note $note, int $statusCode);
    // createNoteCollectionResponse(array $notes);
    // createNotFoundResponse();

    public function createNoteResponse(RequestInterface $request,Note $note,int $statusCode):ResponseInterface;

    public function createNoteCollection(RequestInterface $request,array $notes,int $statusCode): ResponseInterface;

    public function createViolationListResponse(RequestInterface $request, array $violationList): ResponseInterface;

    public function createNotFoundResponse(RequestInterface $request):ResponseInterface;





/*
    public function createPostNoteResponse(Note $note): ResponseInterface;

    public function createFindAllNoteResponse(array $notes): ResponseInterface;

    public function createFindNoteResponse(Note $note): ResponseInterface;

    public function setRequest(RequestInterface $request): void;

    public function createDeleteNoteResponse(Note $note): ResponseInterface;

    public function createUpdateNoteResponse(Note $note): ResponseInterface;
    */
}
