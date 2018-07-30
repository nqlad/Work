<?php

namespace App\Database;

use App\Entity\Note;

interface FinderInterface
{
    public function findNoteCollection(): ?array;

    public function findOneNote(string $id): Note;
}
