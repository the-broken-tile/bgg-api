<?php

namespace TheBrokenTile\BoardGameGeekApi;

final class UrlGenerator implements UrlGeneratorInterface
{
    private const URL = 'https://api.geekdo.com/xmlapi2';

    private string $baseUrl;

    public function __construct(string $baseUrl = self::URL)
    {
        $this->baseUrl = $baseUrl;
    }

    public function generate(RequestInterface $request): string
    {
        return sprintf(
            '%s/%s?%s',
            $this->baseUrl,
            $request->getType(),
            http_build_query($request->getParams()),
        );
    }
}
