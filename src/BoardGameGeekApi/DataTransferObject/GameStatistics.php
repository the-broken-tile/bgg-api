<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameStatistics
{
    public GameRatings $ratings;

    public function __construct()
    {
        $this->ratings = new GameRatings();
    }
}
