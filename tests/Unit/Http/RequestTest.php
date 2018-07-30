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

    public function testGetRequestTarget_getRequestTargetUriString_getRequestStringReturned()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/test/query.php?kingkong=toto', $request->getRequestTarget());
    }

    public function testGetRequestTarget_getRequestTargetUriWithoutRequestTargetString_getRequestTargetSlashReturned()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/', $request->getRequestTarget());
    }

    public function testWithRequestTarget_withRequestTargetString_RequestObjectWithNewRequestTargetReturned()
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withRequestTarget('/test/query.php?kingkong=toto');

        $this->assertEquals('/test/query.php?kingkong=toto', $testRequest->getRequestTarget());
    }

    public function testGetMethod_getMethodString_getMethodStringReturned()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithMethod_InvalidHttpMethod_InvalidArgumentExceptionReturned()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $request->withMethod('TEST');
    }

    public function testWithMethod_withMethodString_RequestObjectWithNewMethodStringReturned()
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withMethod('POST');

        $this->assertEquals('POST', $testRequest->getMethod());
    }

    public function testGetUri_getUriString_getUriStringReturned()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('http://login:pass@secure.example.com:443', $request->getUri());
    }

    public function testWithUri_withUriOldUriHaveHostNewUriWithoutHost_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }

    public function testWithUri_withUriOldUriHaveHostNewUriWithNewHost_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http://secure.example.com:443/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://test.com/test.php', $testRequestUri->__toString());
    }

    public function testWithUri_withUriOldUriWithoutHostNewUriWithHostPreserveHostTrue_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http:/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://test.com/test.php', $testRequestUri->__toString());
    }

    public function testWithUri_withUriOldUriWithoutHostNewUriWithoutHostPreserveHostTrue_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http:/test_1.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test_2.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http:/test_2.php', $testRequestUri->__toString());
    }

    public function testWithUri_withUriOldUriWithHostNewUriWithHostPreserveHostTrue_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }

    public function testWithUri_withUriOldUriWithHostNewUriWithoutHostPreserveHostTrue_RequestObjectWithNewUri()
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }
}
