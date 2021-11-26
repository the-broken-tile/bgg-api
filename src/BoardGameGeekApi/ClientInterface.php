<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

interface ClientInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}
