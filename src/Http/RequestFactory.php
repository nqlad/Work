<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{

    public function createRequest(array $serverConfig): RequestInterface
    {
        $uri                = $this->getUri($serverConfig);
        $method             = $this->getMethod($serverConfig);
        $protocolVersion    = $this->getProtocolVersion($serverConfig);
        $headers            = $this->getHeaders($serverConfig);
        $body               = $this->getBody();
        $request            = new Request($uri,$method,$protocolVersion,$headers,$body);

        return $request;
    }
    private function getHeaders(array $serverConfig)
    {
        $headers = [''=>['']];

        foreach ($serverConfig as $name => $values){

            if(strncmp($name,'HTTP_',5)===0){
                $name       = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $value      = explode(',',$values);
                $headers    += [$name => $value];
            }

        }
        unset($headers['']);

        return $headers;
    }

    private function getUri(array $serverConfig)
    {
        return new Uri($serverConfig['REQUEST_URI']);
    }

    private function getMethod(array $serverConfig)
    {
        return $serverConfig['REQUEST_METHOD'];
    }

    private function getProtocolVersion(array $serverConfig)
    {
        $serverProtocol = explode('/',$serverConfig['SERVER_PROTOCOL']);

        return $serverProtocol[1];
    }

    private function getBody()
    {
        return new StringStream(file_get_contents('php://input'));
    }
}
