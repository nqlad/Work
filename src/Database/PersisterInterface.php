<?php

namespace App\Database;


use App\Entity\Note;

interface PersisterInterface
{
    public function persist(Note $note): Note;

    public function deleteNote(string $id): Note;

    public function updateNote(Note $note): bool;
}
