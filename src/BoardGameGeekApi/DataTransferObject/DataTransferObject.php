<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

abstract class DataTransferObject implements DataTransferObjectInterface
{
    public int $id;
    public string $thumbnail;
    public string $image;
    public array $names = [];
    public string $description;
    public int $yearPublished;
    public int $minPlayers;
    public int $maxPlayers;
    /** @var GamePoll[] */
    public array $polls;
    /** in minutes */
    public int $playingTime;
    /** in minutes */
    public int $minPlayTime;
    /** in minutes */
    public int $maxPlayTime;
    public int $minAge;
    /** @var GameLink[] */
    public array $links = [];
    public ?GameStatistics $stats;
}