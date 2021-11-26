<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class Collection implements DataTransferObjectInterface
{
    public int $totalItems;
    public string $pubDate;
    /** @var CollectionItem[] */
    public array $items = [];
}
