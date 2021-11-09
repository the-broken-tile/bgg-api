<?php

namespace TheBrokenTile\BoardGameGeekApi;

interface RequestInterface
{
    public const TYPE_THING = 'thing';
    public const TYPE_SEARCH = 'search';
    public const TYPE_USER = 'user';

    public const PARAM_PAGE = 'page';
    public const PARAM_ID = 'id';
    public const PARAM_STATS = 'stats';
    public const PARAM_QUERY = 'query';
    public const PARAM_EXACT = 'exact';

    public const PARAM_USER_NAME = 'name';
    public const PARAM_USER_BUDDIES = 'buddies';
    public const PARAM_USER_GUILDS = 'guilds';
    public const PARAM_USER_TOP = 'top';
    public const PARAM_USER_HOT = 'hot';

    public function getType(): string;

    /** @return array<string, mixed> */
    public function getParams(): array;
}