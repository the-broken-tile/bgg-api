<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class SearchResults implements DataTransferObjectInterface
{
    public int $total;
    /** @var SearchItem[] */
    public array $items = [];

    public function getTotalItems(): int
    {
        return $this->total;
    }
}
