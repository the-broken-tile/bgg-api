<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class UserGuild
{
    public function __construct(public int $id, public string $name) {}
}
