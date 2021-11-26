<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

interface UrlGeneratorInterface
{
    public function generate(RequestInterface $request): string;
}
