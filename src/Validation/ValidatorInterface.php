<?php

namespace App\Validation;


use App\Entity\Note;
use Psr\Http\Message\RequestInterface;

interface ValidatorInterface
{
    /**
     * @param Note $note
     * @return Violation[]
     */
    public function validate(Note $note): array;

    public function validateForNullNoteInDB(Note $note): array;

    public function validateForNullIdInUri(RequestInterface $request): array;
}
