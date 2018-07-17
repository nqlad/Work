<?php

namespace App\Http;


use Psr\Http\Message\StreamInterface;

class StringStream implements StreamInterface
{
    /** @var string */
    private $contents;

    // read php://input to string
    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->contents;
    }

    public function close()
    {
        throw new \DomainException('Not implemented');
    }

    public function detach()
    {
        throw new \DomainException('Not implemented');
    }

    public function getSize(): int
    {
        return strlen($this->contents);
    }

    public function tell()
    {
        throw new \DomainException('Not implemented');
    }

    public function eof()
    {
        throw new \DomainException('Not implemented');
    }

    public function isSeekable()
    {
        throw new \DomainException('Not implemented');
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \DomainException('Not implemented');
    }

    public function rewind()
    {
        throw new \DomainException('Not implemented');
    }

    public function isWritable()
    {
        return true;
    }

    public function write($string): int
    {
        $this->contents .= $string;
        return strlen($string);
    }

    public function isReadable()
    {
        return true;
    }

    public function read($length): string
    {
        return substr($this->contents,0,$length);
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getMetadata($key = null)
    {
        throw new \DomainException('Not implemented');
    }
}
