<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 11.07.18
 * Time: 15:11
 */

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


    public function __toString()
    {
        return $this->contents;
    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function detach()
    {
        // TODO: Implement detach() method.
    }

    public function getSize(): int
    {
        return strlen($this->contents);
    }

    public function tell()
    {
        // TODO: Implement tell() method.
    }

    public function eof()
    {
        // TODO: Implement eof() method.
    }

    public function isSeekable()
    {
        // TODO: Implement isSeekable() method.
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        // TODO: Implement seek() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
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

    public function read($length)
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
