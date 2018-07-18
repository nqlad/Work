<?php

namespace App\Tests\Unit\Http;

use App\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    public function testWithQuery_withQueryNewQueryString_UriObjectWithNewQuery()
    {
        $uri        = new Uri('http://secure.example.com/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withQuery('testQuery');
        $checkUri   = new Uri('http://secure.example.com/test/query.php?testQuery#doc3');

        $this->assertEquals($checkUri->getQuery(), $testUri->getQuery());
    }

    public function testWithUserInfo_withUserInfoWithNewLoginWithoutPass_UriObjectWithNewLoginWithoutPass()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withUserInfo('testLogin');
        $checkUri   = new Uri('http://testLogin@secure.example.com:443/test/query.php');

        $this->assertEquals($checkUri->getUserInfo(), $testUri->getUserInfo());
    }

    public function testWithUserInfo_withUserInfoWithNewLoginWithNewPass_UriObjectWithNewLoginWithNewPass()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withUserInfo('testLogin', 'testPassword');
        $checkUri   = new Uri('http://testLogin:testPassword@secure.example.com:443/test/query.php');

        $this->assertEquals($checkUri->getUserInfo(), $testUri->getUserInfo());
    }

    public function testWithPort_withNewPortInt_UriObjectWithNewPort()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withPort(555);
        $checkUri   = new Uri('http://login:pass@secure.example.com:555/test/query.php?kingkong=toto#doc3');

        $this->assertEquals($checkUri->getPort(), $testUri->getPort());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithPort_portEqualsZero_InvalidArgumentException()
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withPort(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithPort_biggerThanNeed_InvalidArgumentException()
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withPort(80000);
    }

    public function testWithScheme_withNewSchemeString_UriObjectWithNewScheme()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withScheme('https');
        $checkUri   = new Uri('https://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');

        $this->assertEquals($checkUri->getScheme(), $testUri->getScheme());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithScheme_withIncorrectScheme_InvalidArgumentException()
    {
        $uri = new Uri('http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3');
        $uri->withScheme('test');
    }

    public function testWithPath_withNewPath_UriObjectWithNewPath()
    {
        $uri        = new Uri('http://login:pass@secure.example.com:443/test/query.php');
        $testUri    = $uri->withPath('/testPath/test.php');
        $checkUri   = new Uri('http://login:pass@secure.example.com:443/testPath/test.php');

        $this->assertEquals($checkUri->getPath(), $testUri->getPath());
    }

    public function testWithFragment_withNewFragment_UriObjectWithNewFragment()
    {
        $uri        = new Uri('http://secure.example.com/test/query.php?kingkong=toto#doc3');
        $testUri    = $uri->withFragment('test');
        $checkUri   = new Uri('http://secure.example.com/test/query.php?kingkong=toto#test');

        $this->assertEquals($checkUri->getFragment(), $testUri->getFragment());
    }
}
