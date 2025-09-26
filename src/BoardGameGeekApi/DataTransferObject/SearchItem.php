<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final readonly class SearchItem
{
    public const string TYPE_BOARD_GAME = RequestInterface::PARAM_BOARD_GAME;

    public function __construct(
        public int $id,
        public string $type,
        public GameName $name,
        public ?int $yearPublished,
    ) {}
}
