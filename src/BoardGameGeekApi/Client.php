<?php

namespace TheBrokenTile\BoardGameGeekApi;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;

final class Client implements ClientInterface
{
    private const METHOD = 'GET';
    private const TYPE_PREFIX = 'api_type_';
    public const CACHE_TAG_PREFIX = 'the_broken_tile';

    private HttpClientInterface $client;
    private TagAwareCacheInterface $cache;
    private ObjectBuilderManagerInterface $builder;
    private UrlGeneratorInterface $urlGenerator;
    private string $cacheTagPrefix;

    public function __construct(
        HttpClientInterface $client,
        TagAwareCacheInterface $cache,
        ObjectBuilderManagerInterface $gameBuilder,
        UrlGeneratorInterface $urlGenerator,
        string $cacheTagPrefix = self::CACHE_TAG_PREFIX
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->builder = $gameBuilder;
        $this->urlGenerator = $urlGenerator;
        $this->cacheTagPrefix = $cacheTagPrefix;
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->urlGenerator->generate($request);

        $response = $this->cache->get($this->buildCacheKey($request), function (ItemInterface $item) use ($url, $request): string {
            $item->tag($this->buildTags($request));

            return $this->client->request(self::METHOD, $url)->getContent();
        });
        assert(is_string($response));

        $thing = $this->builder->build($request, $response);

        return new Response($thing);
    }

    private function buildCacheKey(RequestInterface $request): string
    {
        $key = sprintf(
            '%s|%s',
            $request->getType(),
            join('|', array_values($request->getParams())),
        );

        return $this->sanitizeKey($key);
    }

    /**
     * @return string[]
     */
    private function buildTags(RequestInterface $request): array
    {
        $tags = [
            sprintf('%s.%s%s',$this->cacheTagPrefix, self::TYPE_PREFIX, $request->getType()),
        ];
        foreach ($request->getParams() as $k => $v) {
            $tags[] = $this->sanitizeKey(sprintf('%s.%s_%s', $this->cacheTagPrefix, $k, $v));
        }

        return $tags;
    }

    private function sanitizeKey(string $key): string
    {
        return str_replace(str_split( ItemInterface::RESERVED_CHARACTERS.' '), [], $key);
    }
}