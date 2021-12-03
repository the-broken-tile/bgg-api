<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use Symfony\Contracts\Cache\ItemInterface;

trait SanitizeCacheKeyTrait
{
    private function sanitizeKey(string $key): string
    {
        return str_replace(str_split(ItemInterface::RESERVED_CHARACTERS.' '), [], $key);
    }
}
