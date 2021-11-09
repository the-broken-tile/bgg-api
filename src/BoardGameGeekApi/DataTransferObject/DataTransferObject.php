<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

abstract class DataTransferObject implements DataTransferObjectInterface
{
    use NameTrait;
    use LinksTrait;

    public int $id;
    public string $thumbnail;
    public string $image;
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
    public ?GameStatistics $stats = null;
}