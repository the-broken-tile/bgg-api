<?php

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

class Response implements ResponseInterface
{
    private object $data;

    public function __construct(DataTransferObjectInterface $data)
    {
        $this->data = $data;
    }

    public function getData(): DataTransferObjectInterface
    {
        return $this->data;
    }
}