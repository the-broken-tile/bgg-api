<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class Collection implements DataTransferObjectInterface
{
    public int $totalItems {get => count($this->items); }

    public string $pubDate;

    /** @var CollectionItem[] */
    public array $items = [];
}
