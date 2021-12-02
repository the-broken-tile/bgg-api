<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class GameRequest implements RequestInterface
{
    private string $id;
    private ?string $stats = null;

    public function __construct(int ...$ids)
    {
        $this->id = implode(self::PARAM_VALUE_SEPARATOR, $ids);
    }

    public function getType(): string
    {
        return self::TYPE_THING;
    }

    public function getParams(): array
    {
        return array_filter([
            self::PARAM_ID => $this->id,
            self::PARAM_STATS => $this->stats,
        ]);
    }

    public function stats(): self
    {
        $this->stats = '1';

        return $this;
    }
}
