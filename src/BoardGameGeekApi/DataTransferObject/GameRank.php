<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameRank
{
    public const TYPE_SUBTYPE = 'subtype';
    public const TYPE_FAMILY = 'family';

    public int $id;
    public string $name;
    public string $type;
    public string $friendlyName;
    public int $value;
    public float $bayesAverage;

    public function __construct(int $id, string $name, string $type, string $friendlyName, int $value, float $bayesAverage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->friendlyName = $friendlyName;
        $this->value = $value;
        $this->bayesAverage = $bayesAverage;
    }
}