<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 17.07.18
 * Time: 18:59
 */

namespace App\Tests\Unit\Http;

use App\Http\StringStream;
use PHPUnit\Framework\TestCase;

class StringStreamTest extends TestCase
{

    /**
     * @expectedException \DomainException
     */
    public function testSeek()
    {
        $stream = new StringStream('test');
        $stream->seek('');
    }

    /**
     * @expectedException \DomainException
     */
    public function testTell()
    {
        $stream = new StringStream('test');
        $stream->tell();
    }

    public function testIsReadable()
    {
        $stream = new StringStream('test');

        self::assertTrue($stream->isReadable());
    }

    /**
     * @expectedException \DomainException
     */
    public function testEof()
    {
        $stream = new StringStream('test');
        $stream->eof();
    }

    /**
     * @expectedException \DomainException
     */
    public function testDetach()
    {
        $stream = new StringStream('test');
        $stream->detach();
    }

    /**
     * @expectedException \DomainException
     */
    public function testIsSeekable()
    {
        $stream = new StringStream('test');
        $stream->isSeekable();
    }

    public function testRead()
    {
        $stream = new StringStream('test');

        self::assertEquals('t',$stream->read(1));
    }

    public function testWrite()
    {
        $stream = new StringStream('test');

        self::assertEquals(4,$stream->write('test'));
    }

    /**
     * @expectedException \DomainException
     */
    public function testRewind()
    {
        $stream = new StringStream('test');
        $stream->rewind();
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetMetadata()
    {
        $stream = new StringStream('test');
        $stream->getMetadata();
    }

    /**
     * @expectedException \DomainException
     */
    public function testClose()
    {
        $stream = new StringStream('test');
        $stream->close();
    }

    public function testIsWritable()
    {
        $stream = new StringStream('test');

        self::assertTrue($stream->isWritable());
    }

    public function testGetSize()
    {
        $stream = new StringStream('test');

        self::assertEquals(4,$stream->getSize());
    }

    public function testGetContents()
    {
        $stream = new StringStream('test');

        self::assertEquals('test',$stream->getContents());
    }
}
