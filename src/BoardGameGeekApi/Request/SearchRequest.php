<?php

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class SearchRequest implements RequestInterface
{
    private string $query;
    private ?bool $exact;

    public function __construct(string $query, bool $exact = null)
    {
        $this->query = $query;
        $this->exact = $exact;
    }

    public function getType(): string
    {
        return self::TYPE_SEARCH;
    }

    public function getParams(): array
    {
        return [
            self::PARAM_QUERY => $this->query,
            self::PARAM_EXACT => $this->exact,
            self::PARAM_TYPE => self::PARAM_BOARD_GAME,
        ];
    }
}