<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class CollectionVersion
{
    public const TYPE_VERSION = 'boardgameversion';

    use ImageTrait;
    use NameTrait;
    use LinksTrait;

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