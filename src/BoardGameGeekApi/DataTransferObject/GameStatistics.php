<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class GameStatistics
{
    public function __construct(public GameRatings $ratings) {}
}
