<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameRatings
{
    public int $usersRated;
    public float $average;
    public float $bayesAverage;
    public float $stdDev;
    public float $median;
    public int $owned;
    public int $trading;
    public int $wanting;
    public int $wishing;
    public int $numComments;
    public int $numWeights;
    public float $averageWeight;
}