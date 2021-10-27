<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class SearchResults implements DataTransferObjectInterface
{
    public int $total;
    public array $items;
}