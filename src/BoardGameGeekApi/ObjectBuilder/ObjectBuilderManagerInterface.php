<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

interface ObjectBuilderManagerInterface
{
    public function build(RequestInterface $request, string $response): DataTransferObjectInterface;
}