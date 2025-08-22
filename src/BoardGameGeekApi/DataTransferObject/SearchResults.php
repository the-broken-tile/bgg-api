<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class SearchResults implements DataTransferObjectInterface
{
    public int $totalItems {get => count($this->items); }

    /** @param SearchItem[] $items */
    public function __construct(public array $items = []) {}
}
