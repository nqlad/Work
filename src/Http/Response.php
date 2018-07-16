<?php

namespace App\Http;


use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    private $statusCode;

    private $reasonPhrase;

    private $httpStatusCodeMap = [
        200=>'OK',
        204=>'No Content',

        400=>'Bad Request',
        404=>'Not Found',

        500=>'Internal Server Error'
    ];

    public function __construct(int $status)
    {
        //parent::__construct();

        if(!is_int($status)){
            throw new \InvalidArgumentException('Http status code MUST be Int!');
        }elseif (!array_key_exists($status,$this->httpStatusCodeMap)){
            throw new \InvalidArgumentException('Invalid status code!');
        }

        $this->statusCode = $status;
        $this->reasonPhrase = $this->httpStatusCodeMap[$status];
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        if(!array_key_exists($code,$this->httpStatusCodeMap)){
            throw new \InvalidArgumentException('Invalid status code!');
        }

        $response = clone $this;
        $response->setStatusCode($code);
        if ($reasonPhrase !== ''){
            $response->setReasonPhrase($reasonPhrase);
        }else{
            $response->setReasonPhrase($this->httpStatusCodeMap[$code]);
        }

        return $response;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * @param mixed $reasonPhrase
     */
    public function setReasonPhrase($reasonPhrase): void
    {
        $this->reasonPhrase = $reasonPhrase;
    }
}
