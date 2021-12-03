<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\Request;

use TheBrokenTile\BoardGameGeekApi\RequestInterface;

interface RetryRequestInterface
{
    public function getRetryRequest(): ?RequestInterface;
}
