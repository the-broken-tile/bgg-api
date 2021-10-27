<?php

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class GameRequest implements RequestInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return self::TYPE_THING;
    }

    public function getParams(): array
    {
        return [
            self::PARAM_ID => $this->id,
            self::PARAM_STATS => 1,
        ];
    }
}