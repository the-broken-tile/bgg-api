<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final readonly class RetrySearchRequest implements RequestInterface
{
    /** @param array<string, string> $overwrites */
    public function __construct(private RequestInterface $request, private array $overwrites) {}

    public function getType(): string
    {
        return $this->request->getType();
    }

    public function getParams(): array
    {
        return array_merge($this->request->getParams(), $this->overwrites);
    }
}
