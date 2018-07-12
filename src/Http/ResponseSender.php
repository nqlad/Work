<?php

namespace App\Http;


class ResponseSender implements ResponseSenderInterface
{
    public function sendResponse($response): void
    {
        echo json_encode($response->getBody());
    }
}
