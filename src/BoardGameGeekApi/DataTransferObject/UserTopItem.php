<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class UserTopItem
{
    public int $id;
    public int $rank;
    public string $type;
    public string $name;

    public function __construct(int $id, int $rank, string $name, string $type)
    {
        $this->id = $id;
        $this->rank = $rank;
        $this->name = $name;
        $this->type = $type;
    }
}