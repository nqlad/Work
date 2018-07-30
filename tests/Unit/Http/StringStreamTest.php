<?php

namespace App\Tests\Unit\Http;

use App\Http\StringStream;
use PHPUnit\Framework\TestCase;

class StringStreamTest extends TestCase
{
    /**
     * @test
     * @expectedException \DomainException
     */
    public function testSeek_seek_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->seek('');
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testTell_tell_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->tell();
    }

    /** @test */
    public function testIsReadable_isReadableTrueReturned(): void
    {
        $stream = new StringStream('test');

        $this->assertTrue($stream->isReadable());
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testEof_eof_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->eof();
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testDetach_detach_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->detach();
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testIsSeekable_isSeekable_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->isSeekable();
    }

    /** @test */
    public function testRead_readInt_readString(): void
    {
        $stream = new StringStream('test');

        $this->assertEquals('t',$stream->read(1));
    }

    /** @test */
    public function testWrite_writeString_writeInt(): void
    {
        $stream = new StringStream('test');

        $this->assertEquals(4,$stream->write('test'));
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testRewind_rewind_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->rewind();
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testGetMetadata_getMetadata_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->getMetadata();
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function testClose_close_DomainException(): void
    {
        $stream = new StringStream('test');
        $stream->close();
    }

    /** @test */
    public function testIsWritable_isWritableTrueReturned(): void
    {
        $stream = new StringStream('test');

        $this->assertTrue($stream->isWritable());
    }

    /** @test */
    public function testGetSize_getSizeStringStream_getSizeIntReturned(): void
    {
        $stream = new StringStream('test');

        $this->assertEquals(4,$stream->getSize());
    }

    /** @test */
    public function testGetContents_getContentsStreamString_getContentStringReturned(): void
    {
        $stream = new StringStream('test');

        $this->assertEquals('test',$stream->getContents());
    }
}
