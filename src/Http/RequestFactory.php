<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 10.07.18
 * Time: 12:40
 */

namespace App\Http;


class RequestFactory implements RequestFactoryInterface
{
    public function createRequest()
    {
        $url = $this->getUri();
        $method = $this->getMethod();

        return "test";
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function getUri()
    {
        $url = $_SERVER['REQUEST_URI'];
        $url    = ltrim($url, '/');
        $urls = explode('/',$url);
        return $urls;
    }
}