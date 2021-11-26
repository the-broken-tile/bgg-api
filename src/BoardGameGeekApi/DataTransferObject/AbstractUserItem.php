<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

abstract class AbstractUserItem
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
