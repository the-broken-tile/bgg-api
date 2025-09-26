<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class CollectionVersion
{
    use ImageTrait;
    use LinksTrait;
    use NameTrait;

    public const string TYPE_VERSION = 'boardgameversion';

    public int $yearPublished;
    public string $productCode;
    public float $width;
    public float $length;
    public float $depth;
    public float $weight;

    public function __construct(public int $id, public string $type) {}
}
