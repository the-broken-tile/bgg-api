<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

final class Response implements ResponseInterface
{
    private DataTransferObjectInterface $data;

    public function __construct(DataTransferObjectInterface $data)
    {
        $this->data = $data;
    }

    public function getData(): DataTransferObjectInterface
    {
        return $this->data;
    }
}
