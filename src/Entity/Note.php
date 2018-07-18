<?php

namespace App\Entity;


class Note
{
    public $id;

    public $title;

    public function __toString()
    {
        return "id = " . $this->id . "; title = " .$this->title . ";";
    }
}
