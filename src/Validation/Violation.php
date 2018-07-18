<?php

namespace App\Validation;


class Violation
{
    /** @var string */
    private $path;

    /** @var string */
    private $message;

    public function __construct(string $path, string $message)
    {
        $this->path = $path;
        $this->message = $message;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
