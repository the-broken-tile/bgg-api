<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

interface ObjectBuilderInterface
{
    public function supports(RequestInterface $request): bool;

    public function build(string $response): DataTransferObjectInterface;
}