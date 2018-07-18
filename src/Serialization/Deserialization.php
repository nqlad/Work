<?php

namespace App\Serialization;


use App\Entity\Note;

class Deserialization implements DeserializerInterface
{
    public function deserialize(string $data): Note
    {
        $note = new Note();

        $dataObj = json_decode($data);
        $note->title = $dataObj->title;

        return $note;
    }
}
