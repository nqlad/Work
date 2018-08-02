<?php

namespace App\Validation;

use App\Entity\Note;

class Validator implements ValidatorInterface
{
    /** @var Violation[] */
    private $violations = [];

    public function validate(Note $note): array
    {
        $this->checkForNullTitleAndAddViolation($note);
        $this->checkLengthAndAddViolation($note);

        return $this->violations;
    }

    private function checkLengthAndAddViolation(Note $note): void
    {
        if (strlen($note->title) <= 1) {
            $this->violations[] = new Violation('title', 'Length must be more than one symbol');
        }
    }

    private function checkForNullTitleAndAddViolation(Note $note): void
    {
        if (null === $note->title) {
            $this->violations[] = new Violation('title', 'The request is missing title');
        }
    }

    public function validateForNullNoteInDB(Note $note): array
    {
        if (null === $note->id) {
            $this->violations[] = new Violation('id', 'ID not found');
        }

        return $this->violations;
    }
}
