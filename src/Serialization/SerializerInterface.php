<?php

namespace App\Serialization;


use App\Entity\Note;

interface SerializerInterface
{
    public function serialize(Note $note): string;
}
