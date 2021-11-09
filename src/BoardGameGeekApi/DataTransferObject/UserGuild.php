<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class UserGuild
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}