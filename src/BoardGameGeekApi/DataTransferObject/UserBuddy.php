<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class UserBuddy
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}