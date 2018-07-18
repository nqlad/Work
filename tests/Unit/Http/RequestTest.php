<?php

namespace App\Tests\Unit\Http;

use App\Http\Request;
use App\Http\StringStream;
use App\Http\Uri;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateRequestObject_IncorrectHttpMethod_InvalidArgumentExceptionThrown()
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        new Request($uri, 'TEST', '1.0', [], new StringStream(''));
    }

    public function testGetRequestTarget()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/test/query.php?kingkong=toto', $request->getRequestTarget());
    }

    public function testGetRequestTarget_withoutRequestTarget()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/', $request->getRequestTarget());
    }

    public function testWithRequestTarget()
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withRequestTarget('/test/query.php?kingkong=toto');

        $this->assertEquals('/test/query.php?kingkong=toto', $testRequest->getRequestTarget());
    }

    public function testGetMethod()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithMethod_InvalidHttpMethod()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $request->withMethod('TEST');
    }

    public function testWithMethod()
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withMethod('POST');

        $this->assertEquals('POST', $testRequest->getMethod());
    }

    public function testGetUri()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('http://login:pass@secure.example.com:443', $request->getUri());
    }

    public function testWithUri_preserveHostFalse_oldUriWithHost_newUriWithoutHost()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('secure.example.com', $testRequestUri->getHost());
    }

    public function testWithUri_preserveHostFalse_oldUriWithHost_newUriWithNewHost()
    {
        $uri            = new Uri('http://secure.example.com:443/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('test.com', $testRequestUri->getHost());
    }

    public function testWithUri_preserveHostTrue_oldUriWithoutHost_newUriWithHost()
    {
        $uri            = new Uri('http:/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('test.com', $testRequestUri->getHost());
    }

    public function testWithUri_preserveHostTrue_oldUriWithoutHost_newUriWithoutHost()
    {
        $uri            = new Uri('http:/test_1.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test_2.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('', $testRequestUri->getHost());
    }

    public function testWithUri_preserveHostTrue_oldUriWithHost_newUriWithHost()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('secure.example.com', $testRequestUri->getHost());
    }

    public function testWithUri_preserveHostTrue_oldUriWithHost_newUriWithoutHost()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('secure.example.com', $testRequestUri->getHost());
    }
}
