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
        $this->protocolVersion  = $protocolVersion;
        $this->body             = $body;

        foreach ($headers as $name => $values) {
            if (!is_string($name)) {
                throw new \InvalidArgumentException('Name of header MUST be string!');
            }
            if (!is_array($values)) {
                throw new \InvalidArgumentException('Values of header MUST be array!');
            }
            foreach ($values as $value) {
                if (!is_string($value)) {
                    throw new \InvalidArgumentException('Each value of header MUST be string!');
                }
            }
        }

        $this->headers = $headers;
    }


    private function setProtocolVersion(string $protocolVersion): void
    {
        $this->protocolVersion = $protocolVersion;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return Message|MessageInterface
     */
    public function withProtocolVersion($version)
    {
        $message = clone $this;
        $message->setProtocolVersion($version);

        return $message;
    }

    /**
     * @param \string[][] $headers
     */
    private function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return \string[][]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return string[]
     */
    public function getHeader($name)
    {
        $headerValues = [];

        foreach ($this->getHeaders() as $headerName => $values) {
            if ($headerName === $name) {
                $headerValues = $values;
            }
        }

        return $headerValues;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return Message|MessageInterface
     */
    public function withHeader($name, $value)
    {
        $message = clone $this;
        $headers = $this->getHeaders();

        if (is_string($value)) {
            foreach ($headers as $headerName => $values) {
                if ($headerName === $name) {
                    unset($headers[$headerName]);
                    $headers += [$headerName => [$value]];
                }
            }
        } elseif (is_array($value)) {
            foreach ($headers as $headerName => $values) {
                if ($headerName === $name) {
                    unset($headers[$headerName]);
                    $headers += [$headerName => $value];
                }
            }
        }

        $message->setHeaders($headers);

        return $message;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name)
    {
        if (!$this->hasHeader($name)) {
            $line = '';
        } else {
            $line = implode(', ', $this->getHeader($name));
        }

        return $line;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return Message|MessageInterface
     */
    public function withAddedHeader($name, $value)
    {
        $message = clone $this;
        $headers = $this->getHeaders();

        if (is_string($value)) {
            $headers += [$name => [$value]];
        } elseif (is_array($value)) {
            $headers += [$name => $value];
        }

        $message->setHeaders($headers);

        return $message;
    }

    /**
     * @param string $name
     * @return Message|MessageInterface
     */
    public function withoutHeader($name)
    {
        $message = clone $this;
        $headers = $this->getHeaders();

        foreach ($headers as $nameHeader => $values) {
            if ($nameHeader === $name) {
                unset($headers[$nameHeader]);
            }
        }

        $message->setHeaders($headers);

        return $message;
    }

    private function setBody(StreamInterface $body): void
    {
        $this->body = $body;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return Message|MessageInterface
     */
    public function withBody(StreamInterface $body)
    {
        $message = clone $this;
        $message->setBody($body);

        return $message;
    }
}
