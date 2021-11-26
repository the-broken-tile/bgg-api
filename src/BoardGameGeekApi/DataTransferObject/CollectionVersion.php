<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class CollectionVersion
{
    use ImageTrait;
    use LinksTrait;
    use NameTrait;

    public const TYPE_VERSION = 'boardgameversion';

    public string $type;
    public int $id;
    public int $yearPublished;
    public string $productCode;
    public float $width;
    public float $length;
    public float $depth;
    public float $weight;

    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
    }
}
