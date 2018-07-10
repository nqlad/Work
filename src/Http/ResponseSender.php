<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 10.07.18
 * Time: 17:15
 */

namespace App\Http;


class ResponseSender implements ResponseSenderInterface
{
    public function sendResponse($response): void
    {
        // TODO: Implement sendResponse() method.
        echo $response;
    }
}