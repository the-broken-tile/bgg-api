<?php

namespace TheBrokenTile\BoardGameGeekApi;

interface UrlGeneratorInterface
{
    public function generate(RequestInterface $request): string;
}