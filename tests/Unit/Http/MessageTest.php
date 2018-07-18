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
    public function testCreateMessageObject_headerNameNotString_InvalidArgumentExceptionThrown()
    {
        new Message('1', [1 => ['testHeaderValue']], new StringStream(''));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValuesNotArray_InvalidArgumentExceptionThrown()
    {
        new Message('1', ['testHeaderName' => 'testHeaderValue'], new StringStream(''));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateMessageObject_headerValueNotString_InvalidArgumentExceptionThrown()
    {
        new Message('1', ['testHeaderName' => [1]], new StringStream(''));
    }

    public function testGetProtocolVersion_protocolVersionString_protocolVersionStringReturned()
    {
        $message = new Message('1.0', [], new StringStream(''));

        $this->assertEquals('1.0', $message->getProtocolVersion());
    }

    public function testGetProtocolVersion_protocolVersionInt_protocolVersionStringReturned()
    {
        $message = new Message(1.0, [], new StringStream(''));

        $this->assertNotEquals('1.0', $message->getProtocolVersion());
    }

    /**
     * @dataProvider providerWithProtocolVersion
     */
    public function testWithProtocolVersion_withNewProtocolVersionStringOrInt_MessageObjectWithNewProtocolVersionStringReturned($testData)
    {
        $message        = new Message($testData, [], new StringStream(''));
        $testMessage    = $message->withProtocolVersion($testData);

        $this->assertEquals('1.1', $testMessage->getProtocolVersion());
    }

    public function providerWithProtocolVersion()
    {
        return [[1.1], ['1.1']];
    }

    public function testWithBody_withNewBodyString_MessageObjectWithNewBodyStringReturned()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withBody(new StringStream('Test'));

        $this->assertEquals('Test', $testMessage->getBody());
    }

    public function testWithHeader_withNewHeaderStringString_MessageObjectWithNewHeaderArrayReturned()
    {
        $message        = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testMessage    = $message->withHeader('testHeaderName', 'testHeaderValue');

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    public function testWithHeader_withNewHeaderStringArray_MessageObjectWithNewHeaderArrayReturned()
    {
        $message            = new Message('', ['testHeaderName' => ['example']], new StringStream(''));
        $testHeaderValues   = ['testHeaderValue_1', 'testHeaderValue_2', 'testHeaderValue_3'];
        $testMessage            = $message->withHeader('testHeaderName', $testHeaderValues);

        $this->assertEquals(['testHeaderName' => $testHeaderValues], $testMessage->getHeaders());
    }

    public function testWithAddedHeader_withAddedHeaderStringString_MessageObjectWithAddedHeaderArrayReturned()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', 'testHeaderValue');

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $testMessage->getHeaders());
    }

    public function testWithAddedHeader_withAddedHeaderStringArray_MessageObjectWithAddedHeaderArrayReturned()
    {
        $message        = new Message('', [], new StringStream(''));
        $testMessage    = $message->withAddedHeader('testHeaderName', ['testHeaderValue_1', 'testHeaderValue_1']);

        $this->assertEquals(['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_1']], $testMessage->getHeaders());
    }

    public function testGetHeaders_getHeadersArray_getHeadersArrayReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals(['testHeaderName' => ['testHeaderValue']], $message->getHeaders());
    }

    public function testGetBody_getBodyString_getBodyStringReturned()
    {
        $message = new Message('', [], new StringStream('testStringStream'));

        $this->assertEquals('testStringStream', $message->getBody());
    }


    public function testHasHeader_hasHeaderString_hasHeaderTrueReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertTrue($message->hasHeader('testHeaderName'));
    }

    public function testHasHeader_hasHeaderString_hasHeaderFalseReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertFalse($message->hasHeader('testHeaderName_1'));
    }

    public function testGetHeaderLine_getHeaderLineAnotherHeaderNameString_getHeaderLineEmptyReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals('', $message->getHeaderLine('testHeaderName_1'));
    }

    public function testGetHeaderLine_getHeaderLineExistHeaderNameString_getHeaderLineHeaderValueStringReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals('testHeaderValue', $message->getHeaderLine('testHeaderName'));
    }

    public function testGetHeaderLine_getHeaderLineExistHeaderNameString_getHeaderLineHeaderValueArrayReturned()
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        $this->assertEquals('testHeaderValue_1, testHeaderValue_2', $message->getHeaderLine('testHeaderName'));
    }

    public function testWithoutHeader_withoutHeaderExistHeaderName_MessageObjectWithoutHeaderEmptyReturned()
    {
        $message        = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));
        $testMessage    = $message->withoutHeader('testHeaderName');

        $this->assertEquals([], $testMessage->getHeaders());
    }

    public function testGetHeader_getHeaderString_getHeaderStringReturned()
    {
        $message = new Message('', ['testHeaderName' => ['testHeaderValue']], new StringStream(''));

        $this->assertEquals(['testHeaderValue'], $message->getHeader('testHeaderName'));
    }

    public function testGetHeader_getHeaderString_getHeaderArrayReturned()
    {
        $testHeader     = ['testHeaderName' => ['testHeaderValue_1', 'testHeaderValue_2']];
        $message        = new Message('', $testHeader, new StringStream(''));

        $this->assertEquals(['testHeaderValue_1', 'testHeaderValue_2'], $message->getHeader('testHeaderName'));
    }
}
