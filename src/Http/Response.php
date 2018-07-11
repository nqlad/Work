<?php

namespace App\Http;


class Response
{
    private $status;

    private $headers;

    private $body;

    private $version;

    public function __construct($body)
    {
        $status     = 200;
        $version    = '1.1';

        $this->status   = $status;
        $this->body     = $body;
        $this->version  = $version;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getVersion()
    {
        return $this->version;
    }


}