<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class SearchItem
{
    public const TYPE_BOARD_GAME = RequestInterface::PARAM_BOARD_GAME;

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