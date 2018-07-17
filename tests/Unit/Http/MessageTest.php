<?php

namespace App\Tests\Unit\Http;

use App\Http\Message;
use App\Http\StringStream;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerNameNotString()
    {
        new Message('1', [1 => ['testHeaderValue']], new StringStream(''));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValuesNotArray()
    {
        new Message('1', ['testHeaderName' => 'testHeaderValue'], new StringStream(''));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValueNotString()
    {
        new Message('1', ['testHeaderName' => [1]], new StringStream(''));
    }

    public function testGetProtocolVersion_protocolVersionStringReturned()
    {
        $message = new Message('1.0', [], new StringStream(''));

        self::assertEquals('1.0', $message->getProtocolVersion());
    }

    public function testGetProtocolVersion_protocolVersionNotReturnedInt()
    {
        $message = new Message(1.0, [], new StringStream(''));

        self::assertNotEquals('1.0', $message->getProtocolVersion());
    }

    /**
     * @dataProvider providerWithProtocolVersion
     */
    public function testWithProtocolVersion($testData)
    {
        $message        = new Message($testData, [], new StringStream(''));
        $testMessage    = $message->withProtocolVersion($testData);

        self::assertEquals('1.1', $testMessage->getProtocolVersion());
    }

    public function providerWithProtocolVersion()
    {
        return [[1.1], ['1.1']];
    }

    public function testWithBody()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withBody(new StringStream('Test'));

        self::assertEquals('Test', $testMessage->getBody());
    }

    public function testWithHeader_headerStringString_headerArrayReturned()
    {
        $message        = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testMessage    = $message->withHeader('testHeaderName', 'testHeaderValue');

        self::assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    public function testWithHeader_headerStringArray_headerArrayReturned()
    {
        $message            = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testHeaderValues   = ['testHeaderValue_1', 'testHeaderValue_2', 'testHeaderValue_3'];
        $testMessage            = $message->withHeader('testHeaderName', $testHeaderValues);

        self::assertEquals(['testHeaderName' => $testHeaderValues], $testMessage->getHeaders());
    }

    public function testWithAddedHeader_headerValueString()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', 'testHeaderValue');

        self::assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    public function testWithAddedHeader_headerValueArray()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', ['testHeaderValue_1', 'testHeaderValue_1']);

        self::assertEquals(['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_1']], $testMessage->getHeaders());
    }

    public function testGetHeaders()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertEquals(['testHeaderName' => ['testHeaderValue']], $message->getHeaders());
    }

    public function testGetBody()
    {
        $message = new Message('', [], new StringStream('testStringStream'));

        self::assertEquals('testStringStream', $message->getBody());
    }


    public function testHasHeader_hasHeaderReturnedTrue()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertTrue($message->hasHeader('testHeaderName'));
    }

    public function testHasHeader_hasHeaderReturnedFalse()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertFalse($message->hasHeader('testHeaderName_1'));
    }

    public function testGetHeaderLine_getHeaderLineReturnedEmptyLine()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertEquals('', $message->getHeaderLine('testHeaderName_1'));
    }

    public function testGetHeaderLine_headerValueString()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertEquals('testHeaderValue', $message->getHeaderLine('testHeaderName'));
    }

    public function testGetHeaderLine_headerValueArray()
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        self::assertEquals('testHeaderValue_1, testHeaderValue_2', $message->getHeaderLine('testHeaderName'));
    }

    public function testWithoutHeader()
    {
        $message        = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));
        $testMessage    = $message->withoutHeader('testHeaderName');

        self::assertEquals([], $testMessage->getHeaders());
    }

    public function testGetHeader_headerValueString()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        self::assertEquals(['testHeaderValue'], $message->getHeader('testHeaderName'));
    }

    public function testGetHeader_headerValueArray()
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        self::assertEquals(['testHeaderValue_1', 'testHeaderValue_2'], $message->getHeader('testHeaderName'));
    }
}
