<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class GameLink
{
    public const string TYPE_VERSION = 'boardgameversion';
    public const string TYPE_PUBLISHER = 'boardgamepublisher';
    public const string TYPE_ARTIST = 'boardgameartist';
    public const string TYPE_LANGUAGE = 'language';
    public const string TYPE_CATEGORY = 'boardgamecategory';
    public const string TYPE_DESIGNER = 'boardgamedesigner';

    public function __construct(public int $id, public string $type, public string $value) {}
}
