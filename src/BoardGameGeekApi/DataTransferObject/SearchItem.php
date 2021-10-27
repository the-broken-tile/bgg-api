<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class SearchItem
{
    public int $id;
    public string $type;
    public GameName $name;
    public ?int $yearPublished;

    public function __construct(int $id, string $type, GameName $name, ?int $yearPublished)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->yearPublished = $yearPublished;
    }
}