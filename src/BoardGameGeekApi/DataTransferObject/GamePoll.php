<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GamePoll
{
    public string $name;
    public string $title;
    public int $totalVotes;
    /**
     * @var PollResult[]
     */
    public array $results = [];

    public function __construct(string $name, string $title, int $totalVotes)
    {
        $this->name = $name;
        $this->title = $title;
        $this->totalVotes = $totalVotes;
    }
}
