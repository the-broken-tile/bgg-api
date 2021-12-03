<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class RetrySearchRequest implements RequestInterface
{
    private RequestInterface $request;
    /** @var array<string, string> */
    private array $overwrites;

    /** @param array<string, string> $overwrites */
    public function __construct(RequestInterface $request, array $overwrites)
    {
        $this->request = $request;
        $this->overwrites = $overwrites;
    }

    public function getType(): string
    {
        return $this->request->getType();
    }

    public function getParams(): array
    {
        return array_merge($this->request->getParams(), $this->overwrites);
    }
}
