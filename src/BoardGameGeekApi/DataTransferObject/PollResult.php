<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class PollResult
{
    public function __construct(public string $value, public int $numVotes) {}
}
