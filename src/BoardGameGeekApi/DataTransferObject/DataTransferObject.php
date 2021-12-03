<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

abstract class DataTransferObject implements DataTransferObjectInterface
{
    use ImageTrait;
    use LinksTrait;
    use NameTrait;

    public int $id;
    public string $description;
    public ?int $yearPublished;
    public ?int $minPlayers;
    public ?int $maxPlayers;
    /** @var GamePoll[] */
    public array $polls;
    /** in minutes */
    public ?int $playingTime;
    /** in minutes */
    public ?int $minPlayTime;
    /** in minutes */
    public ?int $maxPlayTime;
    public ?int $minAge;
    public ?GameStatistics $stats;

    public function getTotalItems(): int
    {
        return 1;
    }
}
