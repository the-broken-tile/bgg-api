<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class GameName
{
    public const string TYPE_PRIMARY = 'primary';
    public const string TYPE_ALTERNATE = 'alternate';

    public function __construct(public int $sortIndex, public ?string $type, public string $value) {}
}
