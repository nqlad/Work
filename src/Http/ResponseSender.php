<?php

namespace App\Http;


use Psr\Http\Message\StreamInterface;

class ResponseSender implements ResponseSenderInterface
{
    const SIZE = 1024;

    public function sendResponse($response): void
    {
//        var_dump($response);
        header('HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() .'');

        $this->sendHeaders($response->getHeaders());
        $this->sendBody($response->getBody());
    }

    private function sendHeaders(array $headers): void
    {
        foreach ($headers as $name => $values) {
            $replace = true;
            foreach ($values as $value) {
                header("$name: $value", $replace);
                $replace = false;
            }
        }
    }

    //todo output
    private function sendBody(StreamInterface $body): void//???
    {
        if ($body->isReadable()) {
            echo $body->getContents();
        }
    }
}
