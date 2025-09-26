<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\Exception\ExceptionInterface;

interface ClientInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function request(RequestInterface $request): ResponseInterface;
}
