<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameLink
{
    public int $id;
    public string $type;
    public string $value;

    public function __construct(int $id, string $type, string $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }
}