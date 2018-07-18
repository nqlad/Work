<?php

namespace App\Validation;


use App\Entity\Note;

interface ValidatorInterface
{
    /**
     * @param Note $note
     * @return Violation[]
     */
    public function validate(Note $note): array;
}
