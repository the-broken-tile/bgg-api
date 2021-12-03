<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

interface CacheTagGeneratorInterface
{
    public const TYPE_PREFIX = 'api_type_';
    public const CACHE_TAG_PREFIX = 'the_broken_tile';

    /** @return string[] */
    public function generateTags(RequestInterface $request): array;
}
