<?php

namespace App\Http;

use Psr\Http\Message\StreamInterface;

class StringStream implements StreamInterface
{
    /** @var string */
    private $contents;

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }
    
    public function __toString(): string
    {
        return $this->contents;
    }

    public function close(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function detach(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function getSize(): int
    {
        return strlen($this->contents);
    }

    public function tell(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function eof(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function isSeekable(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        throw new \DomainException('Not implemented');
    }

    public function rewind(): void
    {
        throw new \DomainException('Not implemented');
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function write($string): int
    {
        $this->contents .= $string;

        return strlen($string);
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return substr($this->contents, 0, $length);
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getMetadata($key = null): void
    {
        throw new \DomainException('Not implemented');
    }
}
