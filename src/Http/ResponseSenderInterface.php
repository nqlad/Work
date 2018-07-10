<?php
/**
 * Created by PhpStorm.
 * User: rdavletshin
 * Date: 10.07.18
 * Time: 12:35
 */

namespace App\Http;


interface ResponseSenderInterface
{
    public function sendResponse($response): void;
}