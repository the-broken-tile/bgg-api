<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameName
{
    public int $sortIndex;
    /**
     * @var string "primary" or "alternate"
     */
    public string $type;
    public string $value;

    public function __construct(int $sortIndex, string $type, string $value)
    {
        $this->sortIndex = $sortIndex;
        $this->type = $type;
        $this->value = $value;
    }
}