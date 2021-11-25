<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameName
{
    public const TYPE_PRIMARY = 'primary';
    public const TYPE_ALTERNATE = 'alternate';

    public int $sortIndex;
    public ?string $type = null;
    public string $value;

    public function __construct(int $sortIndex, ?string $type, string $value)
    {
        $this->sortIndex = $sortIndex;
        $this->type = $type;
        $this->value = $value;
    }
}