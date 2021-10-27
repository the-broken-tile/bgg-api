<?php

namespace TheBrokenTile\BoardGameGeekApi;

interface RequestInterface
{
    public const TYPE_THING = 'thing';
    public const TYPE_SEARCH = 'search';

    public const PARAM_ID = 'id';
    public const PARAM_STATS = 'stats';
    public const PARAM_QUERY = 'query';
    public const PARAM_EXACT = 'exact';

    public function getType(): string;

    /** @return array<string, mixed> */
    public function getParams(): array;
}