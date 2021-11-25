<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameLink
{
    public const TYPE_VERSION = 'boardgameversion';
    public const TYPE_PUBLISHER = 'boardgamepublisher';
    public const TYPE_ARTIST = 'boardgameartist';
    public const TYPE_LANGUAGE = 'language';
    public const TYPE_CATEGORY = 'boardgamecategory';

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