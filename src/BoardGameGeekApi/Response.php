<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(public DataTransferObjectInterface $data) {}
}
