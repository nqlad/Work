<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 12.07.18
 * Time: 17:50
 */

namespace App\Tests\Unit\Http;

use App\Http\Message;
use App\Http\StringStream;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function setProtocolVersion_protocolVersion1_protocolVersion1Returned()
    {
        $message = new Message('', [], new StringStream(''));

        $message->setProtocolVersion('1.0');

        $this->assertEquals('1.0', $message->getProtocolVersion());
    }

    public function testGetProtocolVersion()
    {

    }

    public function testWithProtocolVersion()
    {

    }

    public function testWithBody()
    {

    }

    public function testWithHeader()
    {

    }

    public function testWithAddedHeader()
    {

    }

    public function testGetHeaders()
    {

    }

    public function testGetBody()
    {

    }

    public function testSetBody()
    {

    }

    public function testHasHeader()
    {

    }

    public function testGetHeaderLine()
    {

    }

    public function testWithoutHeader()
    {

    }

    public function testGetHeader()
    {

    }
}
