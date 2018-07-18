<?php

namespace App\Serialization;


use App\Entity\Note;

class Serialization implements SerializerInterface
{

    public function serialize(Note $note): string
    {
        $data = json_encode($note);

        return $data;
    }
}
