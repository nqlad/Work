<?php

namespace App\Serialization;

use App\Entity\Note;

interface DeserializerInterface
{
    public function deserialize(string $data): Note;
}
