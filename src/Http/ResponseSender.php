<?php

namespace App\Http;


use Psr\Http\Message\StreamInterface;

class ResponseSender implements ResponseSenderInterface
{
    const SIZE = 1024;

    /*
     * just for check http message
     */
    //todo output
    public function sendResponse($response): void
    {
        header(sprintf('HTTP/%s %d %s',$response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()));
    //echo 'HTTP/' . $response->getProtocolVersion() . ' ' .  $response->getStatusCode() . ' ' . $response->getReasonPhrase() . '<br/>';

        $this->sendHeaders($response->getHeaders());
        $this->sendBody($response->getBody());
    }

    //todo output
    private function sendHeaders(array $headers): void
    {
        foreach ($headers as $name => $values) {
            $replace = true;
            foreach ($values as $value) {
                header("$name: $value", $replace);
                $replace = false;
            }
        }

//        foreach ($headers as $name => $values) {
//            foreach ($values as $value) {
//                echo $name . ': ' . $value;
//            }
//            echo  '<br/>';
//        }
    }

    //todo output
    private function sendBody(StreamInterface $body): void
    {
        if ($body->isReadable()) {
            echo $body->read(self::SIZE);
            flush();
        }
    }
}
