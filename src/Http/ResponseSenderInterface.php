<?php

namespace App\Http;


interface ResponseSenderInterface
{
    public function sendResponse($response): void;
}