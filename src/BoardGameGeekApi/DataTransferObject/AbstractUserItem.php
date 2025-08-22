<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

abstract readonly class AbstractUserItem
{
    public function __construct(public int $id, public int $rank, public string $name, public string $type) {}
}
