<?php

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class CollectionRequest implements RequestInterface
{
    private string $username;
    private ?int $version = null;
    private ?int $brief = null;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getType(): string
    {
        return self::TYPE_COLLECTION;
    }

    public function getParams(): array
    {
        return [
            self::PARAM_COLLECTION_USERNAME => $this->username,
            self::PARAM_COLLECTION_VERSION => $this->version,
            self::PARAM_COLLECTION_BRIEF => $this->brief,
        ];
    }

    public function version(): self
    {
        $this->version = 1;

        return $this;
    }

    /**
     * Returns more abbreviated results.
     * Only returns name and status
     */
    public function brief(): self
    {
        $this->brief = true;

        return $this;
    }
}