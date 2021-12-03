<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class CollectionRequest implements RequestInterface
{
    private string $username;
    private ?string $version = null;
    private ?string $brief = null;
    private ?string $stats = null;
    /** @var array<string, string> */
    private array $filters = [];

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
        return array_filter(array_merge([
            self::PARAM_COLLECTION_USERNAME => $this->username,
            self::PARAM_COLLECTION_VERSION => $this->version,
            self::PARAM_COLLECTION_BRIEF => $this->brief,
            self::PARAM_STATS => $this->stats,
        ], $this->filters));
    }

    public function version(): self
    {
        $this->version = '1';

        return $this;
    }

    /**
     * Returns more abbreviated results.
     * Only returns name and status.
     */
    public function brief(): self
    {
        $this->brief = '1';

        return $this;
    }

    public function stats(): self
    {
        $this->stats = '1';

        return $this;
    }

    /**
     * @param bool|int|string $value
     */
    public function filter(string $name, $value): self
    {
        if (\is_bool($value)) {
            $this->filters[$name] = $value ? '1' : '0';

            return $this;
        }
        if (\is_int($value)) {
            $this->filters[$name] = (string) $value;

            return $this;
        }
        $this->filters[$name] = $value;

        return $this;
    }
}
