<?php

namespace App\Http;


interface RequestFactoryInterface
{
    public function getMethod();

    public function getUri();

    public function createRequest();
}