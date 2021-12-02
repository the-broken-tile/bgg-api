<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

interface DataTransferObjectInterface
{
    public function getTotalItems(): int;
}
