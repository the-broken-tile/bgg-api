<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class PollResult
{
    public string $value;
    public int $numVotes;

    public function __construct(string $value, int $numVotes)
    {
        $this->value = $value;
        $this->numVotes = $numVotes;
    }
}