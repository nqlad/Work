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
     *
     * @param \string[][] $headers
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
     */
    public function withProtocolVersion($version): MessageInterface
    {
        $message = clone $this;
        $message->setProtocolVersion($version);

        return $message;
    }

    private function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader($name): array
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
     * @param string          $name
     * @param string|string[] $value
     */
    public function withHeader($name, $value): MessageInterface
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

    public function hasHeader($name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeaderLine($name): string
    {
        if (!$this->hasHeader($name)) {
            $line = '';
        } else {
            $line = implode(', ', $this->getHeader($name));
        }

        return $line;
    }

    /**
     * @param string          $name
     * @param string|string[] $value
     */
    public function withAddedHeader($name, $value): MessageInterface
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
     */
    public function withoutHeader($name): MessageInterface
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

    public function getBody(): StreamInterface
    {
        return $this->body;
    }
    
    public function withBody(StreamInterface $body): MessageInterface
    {
        $message = clone $this;
        $message->setBody($body);

        return $message;
    }
}
