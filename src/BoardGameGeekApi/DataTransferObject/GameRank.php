<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final readonly class GameRank
{
    public const string TYPE_SUBTYPE = 'subtype';
    public const string TYPE_FAMILY = 'family';

    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $friendlyName,
        public int $value,
        public float $bayesAverage,
    ) {}
}
