<?php

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

interface ResponseInterface
{
    public function getData(): DataTransferObjectInterface;
}