<?php

namespace App\Serialization;

interface SerializerInterface
{
    public function serialize($data): string;
}
