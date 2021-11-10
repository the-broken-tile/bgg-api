<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class SearchResults implements DataTransferObjectInterface
{
    public int $total;
    /** @var SearchItem[]  */
    public array $items;
}