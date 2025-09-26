<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class GamePoll
{
    /**
     * @param PollResult[] $results
     */
    public function __construct(
        public string $name,
        public string $title,
        public int $totalVotes,
        public array $results,
    ) {}
}
