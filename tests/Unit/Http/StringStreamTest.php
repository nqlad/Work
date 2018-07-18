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
    public function testSeek_seek_DomainException()
    {
        $stream = new StringStream('test');
        $stream->seek('');
    }

    /**
     * @expectedException \DomainException
     */
    public function testTell_tell_DomainException()
    {
        $stream = new StringStream('test');
        $stream->tell();
    }

    public function testIsReadable_isReadableTrueReturned()
    {
        $stream = new StringStream('test');

        $this->assertTrue($stream->isReadable());
    }

    /**
     * @expectedException \DomainException
     */
    public function testEof_eof_DomainException()
    {
        $stream = new StringStream('test');
        $stream->eof();
    }

    /**
     * @expectedException \DomainException
     */
    public function testDetach_detach_DomainException()
    {
        $stream = new StringStream('test');
        $stream->detach();
    }

    /**
     * @expectedException \DomainException
     */
    public function testIsSeekable_isSeekable_DomainException()
    {
        $stream = new StringStream('test');
        $stream->isSeekable();
    }

    public function testRead_readInt_readString()
    {
        $stream = new StringStream('test');

        $this->assertEquals('t',$stream->read(1));
    }

    public function testWrite_writeString_writeInt()
    {
        $stream = new StringStream('test');

        $this->assertEquals(4,$stream->write('test'));
    }

    /**
     * @expectedException \DomainException
     */
    public function testRewind_rewind_DomainException()
    {
        $stream = new StringStream('test');
        $stream->rewind();
    }

    /**
     * @expectedException \DomainException
     */
    public function testGetMetadata_getMetadata_DomainException()
    {
        $stream = new StringStream('test');
        $stream->getMetadata();
    }

    /**
     * @expectedException \DomainException
     */
    public function testClose_close_DomainException()
    {
        $stream = new StringStream('test');
        $stream->close();
    }

    public function testIsWritable_isWritableTrueReturned()
    {
        $stream = new StringStream('test');

        $this->assertTrue($stream->isWritable());
    }

    public function testGetSize_getSizeStringStream_getSizeIntReturned()
    {
        $stream = new StringStream('test');

        $this->assertEquals(4,$stream->getSize());
    }

    public function testGetContents_getContentsStreamString_getContentStringReturned()
    {
        $stream = new StringStream('test');

        $this->assertEquals('test',$stream->getContents());
    }
}
