<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;

interface ObjectBuilderInterface
{
    public function supports(string $response): bool;

    public function build(string $response): DataTransferObjectInterface;
}