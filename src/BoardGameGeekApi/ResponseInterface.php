<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

interface ResponseInterface
{
    public function getData(): DataTransferObjectInterface;
}
