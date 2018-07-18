<?php

namespace App\Database;


use App\Entity\Note;

interface PersisterInterface
{
    public function persist(Note $note): Note;
}
