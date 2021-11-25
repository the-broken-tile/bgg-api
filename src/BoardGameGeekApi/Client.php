<?php

namespace TheBrokenTile\BoardGameGeekApi;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;

final class Client implements ClientInterface
{
    private const METHOD = 'GET';
    private const CACHE_VERSION = 1;

    private HttpClientInterface $client;
    private CacheInterface $cache;
    private ObjectBuilderManagerInterface $builder;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        ObjectBuilderManagerInterface $gameBuilder,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->builder = $gameBuilder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->urlGenerator->generate($request);

        $response = $this->cache->get($this->buildCacheKey($request), function (/*ItemInterface $item*/) use ($url): string {
            return $this->client->request(self::METHOD, $url)->getContent();
        });

        $thing = $this->builder->build($request, $response);

        return new Response($thing);
    }

    private function buildCacheKey(RequestInterface $request): string
    {
        $key = sprintf(
            '%s|%s|%d',
            $request->getType(),
            join('|', array_values($request->getParams())),
            self::CACHE_VERSION,
        );

        //Sanitize.
        $chars = str_split( ItemInterface::RESERVED_CHARACTERS.' ');

        return str_replace($chars, [], $key);
    }
}