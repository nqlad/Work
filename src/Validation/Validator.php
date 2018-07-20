<?php

namespace App\Validation;


use App\Entity\Note;

class Validator implements ValidatorInterface
{
    /** @var Violation[] */
    private $violations = [];

    public function validate(Note $note): array
    {
        $this->checkForNullAndAddViolation($note);
        $this->checkLengthAndAddViolation($note);

        return $this->violations;
    }

    private function checkLengthAndAddViolation(Note $note): void
    {
        if (strlen($note->title) <= 1){
            $this->violations[] = new Violation("title", "Length must be more than one symbol");
        }
    }

    private function checkForNullAndAddViolation(Note $note): void
    {
        if ($note->title === null) {
            $this->violations[] = new Violation("title", "The request is missing title");
        }
    }
}
