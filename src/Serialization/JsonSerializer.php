<?php

namespace App\Serialization;

use App\Entity\Note;

class JsonSerializer implements SerializerInterface, DeserializerInterface
{
    public function serialize($data): string
    {
        return json_encode($data);
    }

    public function deserialize(string $data): Note
    {
        $note = new Note();

        $parsedJson     = json_decode($data, true);
        $note->title    = $parsedJson['title'] ?? null;

        return $note;
    }
}
