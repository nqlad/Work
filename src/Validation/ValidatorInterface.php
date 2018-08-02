<?php

namespace App\Validation;

use App\Entity\Note;

interface ValidatorInterface
{
    /**
     * @return Violation[]
     */
    public function validate(Note $note): array;

    public function validateForNullNoteInDB(Note $note): array;
}
