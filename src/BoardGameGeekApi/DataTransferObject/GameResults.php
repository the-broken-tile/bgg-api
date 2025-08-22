<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameResults implements DataTransferObjectInterface
{
    public int $totalItems {get => count($this->items); }

    /** @param DataTransferObject[] $items */
    public function __construct(public array $items = []) {}
}
