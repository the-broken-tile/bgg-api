<?php

namespace TheBrokenTile\BoardGameGeekApi;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilder;

final class Client implements ClientInterface
{
    private const METHOD = 'GET';
    private const CACHE_VERSION = 1;
    private const URL = 'https://api.geekdo.com/xmlapi2/%s?%s';

    private HttpClientInterface $client;
    private CacheInterface $cache;
    private ObjectBuilder $builder;

    public function __construct(HttpClientInterface $client, CacheInterface $cache, ObjectBuilder $gameBuilder)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->builder = $gameBuilder;
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->generateUrl($request);
        $response = $this->cache->get($this->buildCacheKey($request), function (/*ItemInterface $item*/) use ($url): string {
            return $this->client->request(self::METHOD, $url)->getContent();
        });
        $thing = $this->builder->build($response);

        return new Response($thing);
    }

    private function generateUrl(RequestInterface $request): string
    {
        return sprintf(self::URL, $request->getType(), http_build_query($request->getParams()));
    }

    private function buildCacheKey(RequestInterface $request): string
    {
        return sprintf(
            '%s|%s|%d',
            $request->getType(),
            join('|', array_values($request->getParams())),
            self::CACHE_VERSION,
        );
    }
}