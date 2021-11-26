<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameStatistics
{
    public GameRatings $ratings;

    public function __construct()
    {
        $this->ratings = new GameRatings();
    }
}
