<?php

namespace App\Tests\Unit\Http;

use App\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /** @test */
    public function testWithQuery_withQueryNewQueryString_UriObjectWithNewQuery(): void
    {
        $uri        = new Uri('http://secure.example.com/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withQuery('testQuery');
        $checkUri   = new Uri('http://secure.example.com/test/query.php?testQuery#doc3');

        $this->assertEquals($checkUri->getQuery(), $testUri->getQuery());
    }

    /** @test */
    public function testWithUserInfo_withUserInfoWithNewLoginWithoutPass_UriObjectWithNewLoginWithoutPass(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withUserInfo('testLogin');
        $checkUri   = new Uri('http://testLogin@secure.example.com:443/test/query.php');

        $this->assertEquals($checkUri->getUserInfo(), $testUri->getUserInfo());
    }

    /** @test */
    public function testWithUserInfo_withUserInfoWithNewLoginWithNewPass_UriObjectWithNewLoginWithNewPass(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withUserInfo('testLogin', 'testPassword');
        $checkUri   = new Uri('http://testLogin:testPassword@secure.example.com:443/test/query.php');

        $this->assertEquals($checkUri->getUserInfo(), $testUri->getUserInfo());
    }

    /** @test */
    public function testWithPort_withNewPortInt_UriObjectWithNewPort(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withPort(555);
        $checkUri   = new Uri('http://login:pass@secure.example.com:555/test/query.php?kingkong=toto#doc3');

        $this->assertEquals($checkUri->getPort(), $testUri->getPort());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testWithPort_portEqualsZero_InvalidArgumentException(): void
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withPort(0);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testWithPort_biggerThanNeed_InvalidArgumentException(): void
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withPort(80000);
    }

    /** @test */
    public function testWithScheme_withNewSchemeString_UriObjectWithNewScheme(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withScheme('https');
        $checkUri   = new Uri('https://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        $this->assertEquals($checkUri->getScheme(), $testUri->getScheme());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testWithScheme_withIncorrectScheme_InvalidArgumentException(): void
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withScheme('test');
    }

    /** @test */
    public function testWithPath_withNewPath_UriObjectWithNewPath(): void
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withPath('/testPath/test.php');
        $checkUri   = new Uri('http://login:pass@secure.example.com:443/testPath/test.php');

        $this->assertEquals($checkUri->getPath(), $testUri->getPath());
    }

    /** @test */
    public function testWithFragment_withNewFragment_UriObjectWithNewFragment(): void
    {
        $uri        = new Uri('http://secure.example.com/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withFragment('test');
        $checkUri   = new Uri('http://secure.example.com/test/query.php?kingkong=toto#test');

        $this->assertEquals($checkUri->getFragment(), $testUri->getFragment());
    }
}
