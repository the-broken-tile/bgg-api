<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameRatings
{
    /**
     * Will be set for game wil stats=1
     * Will be set for collection with stats=1 and brief=0.
     *
     * @var GameRank[]
     */
    public array $ranks = [];

    /**
     * Will be set for collection with stats=1
     * Won't be set for collection with stats=1 and brief=1.
     */
    public ?int $usersRated;

    /**
     * Will be set for collection with stats=1
     * Will be set for collection with stats=1 and brief=1.
     */
    public float $average;

    /**
     * Will be set for collection with stats=1
     * Will be set for collection with stats=1 and brief=1.
     */
    public float $bayesAverage;

    /**
     * Will be set for collection with stats=1
     * Won't be set for collection with stats=1 and brief=1.
     */
    public ?float $stdDev;

    /**
     * Will be set for collection with stats=1
     * Won't be set for collection with stats=1 and brief=1.
     */
    public ?float $median;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $owned = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $trading = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $wanting = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $wishing = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $numComments = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?int $numWeights = null;

    /**
     * Won't be set for collection with stats=1.
     */
    public ?float $averageWeight = null;
}
