<?php

namespace App\Http;


interface RequestHandlerInterface
{
    public function handlerRequest($request);
}