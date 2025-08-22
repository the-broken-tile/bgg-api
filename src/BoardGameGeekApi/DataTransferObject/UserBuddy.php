<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class UserBuddy
{
    public function __construct(public int $id, public string $name) {}
}
