<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class SearchRequest implements RequestInterface, RetryRequestInterface
{
    private string $query;
    private ?string $exact;

    public function __construct(string $query, bool $exact = null)
    {
        $this->query = $query;
        $this->exact = null === $exact ? null : ($exact ? '1' : '0');
    }

    public function getType(): string
    {
        return self::TYPE_SEARCH;
    }

    public function getParams(): array
    {
        return array_filter([
            self::PARAM_QUERY => $this->query,
            self::PARAM_EXACT => $this->exact,
            self::PARAM_TYPE => self::PARAM_BOARD_GAME,
        ]);
    }

    public function getRetryRequest(): ?RequestInterface
    {
        if ('1' === $this->exact) {
            return new RetrySearchRequest($this, [
                self::PARAM_EXACT => '0',
            ]);
        }

        return null;
    }
}
