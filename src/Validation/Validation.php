<?php

namespace App\Validation;


use App\Entity\Note;

class Validation implements ValidatorInterface
{
    public function validate(Note $note): array
    {
        $violations = $this->checkForLength($note);

        return $violations;
    }

    private function checkForLength(Note $note):array
    {
        $violation = [];

        if (strlen($note->title) <= 1){
            $violation += ["Length of title must be more than one symbol"];
        }

        return $violation;
    }
}
