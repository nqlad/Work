<?php

namespace App\Tests\Unit\Http;

use App\Http\Message;
use App\Http\StringStream;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerNameNotString_InvalidArgumentExceptionThrown(): void
    {
        new Message('1', [1 => ['testHeaderValue']], new StringStream(''));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValuesNotArray_InvalidArgumentExceptionThrown(): void
    {
        new Message('1', ['testHeaderName' => 'testHeaderValue'], new StringStream(''));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValueNotString_InvalidArgumentExceptionThrown(): void
    {
        new Message('1', ['testHeaderName' => [1]], new StringStream(''));
    }

    /** @test */
    public function testGetProtocolVersion_protocolVersionString_protocolVersionStringReturned(): void
    {
        $message = new Message('1.0', [], new StringStream(''));

        $this->assertEquals('1.0', $message->getProtocolVersion());
    }

    /**
     * @test
     * @param $testData
     * @dataProvider providerWithProtocolVersion
     */
    public function testWithProtocolVersion_withNewProtocolVersionStringOrInt_MessageObjectWithNewProtocolVersionStringReturned($testData): void
    {
        $message        = new Message($testData, [], new StringStream(''));
        $testMessage    = $message->withProtocolVersion($testData);

        $this->assertEquals('1.1', $testMessage->getProtocolVersion());
    }

    public function providerWithProtocolVersion(): array
    {
        return [[1.1], ['1.1']];
    }

    /** @test */
    public function testWithBody_withNewBodyString_MessageObjectWithNewBodyStringReturned(): void
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withBody(new StringStream('Test'));

        $this->assertEquals('Test', $testMessage->getBody());
    }

    /** @test */
    public function testWithHeader_withNewHeaderStringString_MessageObjectWithNewHeaderArrayReturned(): void
    {
        $message        = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testMessage    = $message->withHeader('testHeaderName', 'testHeaderValue');

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    /** @test */
    public function testWithHeader_withNewHeaderStringArray_MessageObjectWithNewHeaderArrayReturned(): void
    {
        $message            = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testHeaderValues   = ['testHeaderValue_1', 'testHeaderValue_2', 'testHeaderValue_3'];
        $testMessage        = $message->withHeader('testHeaderName', $testHeaderValues);

        $this->assertEquals(['testHeaderName' => $testHeaderValues], $testMessage->getHeaders());
    }

    /** @test */
    public function testWithAddedHeader_withAddedHeaderStringString_MessageObjectWithAddedHeaderArrayReturned(): void
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', 'testHeaderValue');

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    /** @test */
    public function testWithAddedHeader_withAddedHeaderStringArray_MessageObjectWithAddedHeaderArrayReturned(): void
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', ['testHeaderValue_1', 'testHeaderValue_1']);

        $this->assertEquals(['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_1']], $testMessage->getHeaders());
    }

    /** @test */
    public function testGetHeaders_getHeadersArray_getHeadersArrayReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $message->getHeaders());
    }

    /** @test */
    public function testGetBody_getBodyString_getBodyStringReturned(): void
    {
        $message = new Message('', [], new StringStream('testStringStream'));

        $this->assertEquals('testStringStream', $message->getBody());
    }

    /** @test */
    public function testHasHeader_hasHeaderString_hasHeaderTrueReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertTrue($message->hasHeader('testHeaderName'));
    }

    /** @test */
    public function testHasHeader_hasHeaderString_hasHeaderFalseReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertFalse($message->hasHeader('testHeaderName_1'));
    }

    /** @test */
    public function testGetHeaderLine_getHeaderLineAnotherHeaderNameString_getHeaderLineEmptyReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals('', $message->getHeaderLine('testHeaderName_1'));
    }

    /** @test */
    public function testGetHeaderLine_getHeaderLineExistHeaderNameString_getHeaderLineHeaderValueStringReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals('testHeaderValue', $message->getHeaderLine('testHeaderName'));
    }

    /** @test */
    public function testGetHeaderLine_getHeaderLineExistHeaderNameString_getHeaderLineHeaderValueArrayReturned(): void
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        $this->assertEquals('testHeaderValue_1, testHeaderValue_2', $message->getHeaderLine('testHeaderName'));
    }

    /** @test */
    public function testWithoutHeader_withoutHeaderExistHeaderName_MessageObjectWithoutHeaderEmptyReturned(): void
    {
        $message        = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));
        $testMessage    = $message->withoutHeader('testHeaderName');

        $this->assertEquals([], $testMessage->getHeaders());
    }

    /** @test */
    public function testGetHeader_getHeaderString_getHeaderStringReturned(): void
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals(['testHeaderValue'], $message->getHeader('testHeaderName'));
    }

    /** @test */
    public function testGetHeader_getHeaderString_getHeaderArrayReturned(): void
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        $this->assertEquals(['testHeaderValue_1', 'testHeaderValue_2'], $message->getHeader('testHeaderName'));
    }
}
