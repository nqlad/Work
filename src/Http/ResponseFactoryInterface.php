<?php

namespace App\Http;


use App\Entity\Note;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseFactoryInterface
{
    public function createPostNoteResponse(Note $note): ResponseInterface;

    public function createFindAllNoteResponse(array $notes): ResponseInterface;

    public function createFindNoteResponse(Note $note): ResponseInterface;

    public function createViolationListResponse(array $violationList): ResponseInterface;

    public function setRequest(RequestInterface $request): void;

    public function createDeleteNoteResponse(Note $note): ResponseInterface;

    public function createUpdateNoteResponse(Note $note): ResponseInterface;
}
