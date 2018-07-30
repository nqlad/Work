<?php

namespace App\Tests\Unit\Http;

use App\Http\Request;
use App\Http\StringStream;
use App\Http\Uri;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testCreateRequestObject_IncorrectHttpMethod_InvalidArgumentExceptionThrown(): void
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        new Request($uri, 'TEST', '1.0', [], new StringStream(''));
    }

    /** @test */
    public function testGetRequestTarget_getRequestTargetUriString_getRequestStringReturned(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/test/query.php?kingkong=toto', $request->getRequestTarget());
    }

    /** @test */
    public function testGetRequestTarget_getRequestTargetUriWithoutRequestTargetString_getRequestTargetSlashReturned(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('/', $request->getRequestTarget());
    }

    /** @test */
    public function testWithRequestTarget_withRequestTargetString_RequestObjectWithNewRequestTargetReturned(): void
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withRequestTarget('/test/query.php?kingkong=toto');

        $this->assertEquals('/test/query.php?kingkong=toto', $testRequest->getRequestTarget());
    }

    /** @test */
    public function testGetMethod_getMethodString_getMethodStringReturned(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('GET', $request->getMethod());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testWithMethod_InvalidHttpMethod_InvalidArgumentExceptionReturned(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $request->withMethod('TEST');
    }

    /** @test */
    public function testWithMethod_withMethodString_RequestObjectWithNewMethodStringReturned(): void
    {
        $uri            = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testRequest    = $request->withMethod('POST');

        $this->assertEquals('POST', $testRequest->getMethod());
    }

    /** @test */
    public function testGetUri_getUriString_getUriStringReturned(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443');
        $request    = new Request($uri, 'GET', '1.0', [], new StringStream(''));

        $this->assertEquals('http://login:pass@secure.example.com:443', $request->getUri());
    }

    /** @test */
    public function testWithUri_withUriOldUriHaveHostNewUriWithoutHost_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }

    /** @test */
    public function testWithUri_withUriOldUriHaveHostNewUriWithNewHost_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http://secure.example.com:443/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://test.com/test.php', $testRequestUri->__toString());
    }

    /** @test */
    public function testWithUri_withUriOldUriWithoutHostNewUriWithHostPreserveHostTrue_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http:/test.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://test.com/test.php', $testRequestUri->__toString());
    }

    /** @test */
    public function testWithUri_withUriOldUriWithoutHostNewUriWithoutHostPreserveHostTrue_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http:/test_1.php');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test_2.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http:/test_2.php', $testRequestUri->__toString());
    }

    /** @test */
    public function testWithUri_withUriOldUriWithHostNewUriWithHostPreserveHostTrue_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http://test.com/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }

    /** @test */
    public function testWithUri_withUriOldUriWithHostNewUriWithoutHostPreserveHostTrue_RequestObjectWithNewUri(): void
    {
        $uri            = new Uri('http://secure.example.com:443');
        $request        = new Request($uri, 'GET', '1.0', [], new StringStream(''));
        $testUri        = new Uri('http:/test.php');
        $testRequest    = $request->withUri($testUri, true);
        $testRequestUri = $testRequest->getUri();

        $this->assertEquals('http://secure.example.com/test.php', $testRequestUri->__toString());
    }
}
