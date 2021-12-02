<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameResults implements DataTransferObjectInterface
{
    public int $total = 0;
    /** @var DataTransferObject[] */
    public array $items = [];
}
