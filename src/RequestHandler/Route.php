<?php

namespace App\RequestHandler;


class Route
{
    /** @var string|null */
    private $resourceId;

    /** @var string */
    private $resourceName;

    /** @var string */
    private $method;

    public function __construct(?string $resourceId, string $resourceName, string $method)
    {
        $this->resourceId = $resourceId;
        $this->resourceName = $resourceName;
        $this->method = $method;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
