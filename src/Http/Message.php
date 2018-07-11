<?php

namespace App\Http;


use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    /** @var string */
    private $protocolVersion;

    /** @var string[][] */
    private $headers;

    /** @var StreamInterface */
    private $body;

    /**
     * Message constructor.
     * @param string $protocolVersion
     * @param \string[][] $headers
     * @param StreamInterface $body
     */
    public function __construct(string $protocolVersion, array $headers, StreamInterface $body)
    {
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
        $this->body = $body;
    }


    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version)
    {
        $message = clone $this;
        $message->setProtocolVersion($version);

        return $message;
    }

    public function getHeaders()
    {
        $result=[];
        foreach ($this->headers as $header){
            $headers = explode(':',$header);
            $name = $headers[0];
            $values = explode(',',ltrim($headers[1]));
            $result += [$name => $values];
        }
        return $result;
    }

    public function hasHeader($name): bool
    {
        $headers=[];
        foreach ($this->getHeaders() as $nameHeader => $values) {
                     $header =  $nameHeader . ": " . implode(",", $values);
                     array_push($headers,$header);
        }

        if (in_array($name,$headers)){
            return true;
        }else {
            return false;
        }
    }

    public function getHeader($name)
    {
        $header=[];
        foreach ($this->getHeaders() as $nameHeader => $values) {
            if ($nameHeader === $name){
                $header = $values;
            }
        }
        return $header;
    }


    public function getHeaderLine($name)
    {
        return implode(',',$this->getHeader($name));
    }


    public function withHeader($name, $value)
    {
  //todo
    }

    public function withAddedHeader($name, $value)
    {
        $message = clone $this;
        $values = $value;
        if (gettype($value) == "string"){
            $values = [$value];
        }
        $header = $name . ": " . implode(",", $values);
        array_push($this->headers,$header);

        return $message;
    }

    public function withoutHeader($name)
    {
        $message = clone $this;
        $header = '';
        foreach ($this->getHeaders() as $nameHeader => $values) {
            if($nameHeader == $name){
                $header =  $nameHeader . ": " . implode(",", $values);
            }
        }
        for ($i = 0; $i < count($this->headers); $i++){
            if($this->headers[$i] == $header){
                unset($this->headers[$i]);
            }
        }

        return $message;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        $message = clone $this;
        $message->setBody($body);

        return $message;
    }

    /**
     * @param string $protocolVersion
     */
    public function setProtocolVersion(string $protocolVersion): void
    {
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * @param StreamInterface $body
     */
    public function setBody(StreamInterface $body): void
    {
        $this->body = $body;
    }

}