<?php

namespace App\Http;


use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{

    public function createRequest(array $serverConfig): RequestInterface
    {
        $uri                = new Uri($serverConfig['REQUEST_URI']);
        $method             = $serverConfig['REQUEST_METHOD'];
        $serverProtocol     = explode('/',$serverConfig['SERVER_PROTOCOL']);
        $protocolVersion    = $serverProtocol[1];
        $headers            = $this->getHeaders($serverConfig);
//        $body               = new StringStream('content');
        $body               = new StringStream(file_get_contents('php://input'));
        $request            = new Request($uri,$method,$protocolVersion,$headers,$body);

        return $request;
    }
    private function getHeaders(array $serverConfig){
        $headers = [''=>['']];

        foreach ($serverConfig as $name => $values){

            if(strncmp($name,'HTTP_',5)===0){
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $value = explode(',',$values);
                $headers += [$name => $value];

            }
        }

        unset($headers['']);

        return $headers;
    }
}
