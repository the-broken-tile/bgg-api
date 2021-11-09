<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameLink
{
    public int $id;
    public string $type;
    /** @var string "boardgamepublisher", "boardgamepublisher", "boardgameartist", "language", etc... */
    public string $value;

    public function __construct(int $id, string $type, string $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }
}