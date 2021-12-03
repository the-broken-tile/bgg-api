<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\Request\RetryRequestInterface;

final class Client implements ClientInterface
{
    use SanitizeCacheKeyTrait;

    private const METHOD = 'GET';

    private HttpClientInterface $client;
    private TagAwareCacheInterface $cache;
    private ObjectBuilderManagerInterface $builder;
    private UrlGeneratorInterface $urlGenerator;
    private CacheTagGeneratorInterface $cacheTagGenerator;

    public function __construct(
        HttpClientInterface $client,
        TagAwareCacheInterface $cache,
        ObjectBuilderManagerInterface $gameBuilder,
        UrlGeneratorInterface $urlGenerator,
        CacheTagGeneratorInterface $cacheTagGenerator
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->builder = $gameBuilder;
        $this->urlGenerator = $urlGenerator;
        $this->cacheTagGenerator = $cacheTagGenerator;
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->urlGenerator->generate($request);

        $response = $this->cache->get($this->buildCacheKey($request), function (ItemInterface $item) use ($url, $request): string {
            $item->tag($this->cacheTagGenerator->generateTags($request));

            return $this->client->request(self::METHOD, $url)->getContent();
        });
        \assert(\is_string($response));

        $thing = $this->builder->build($request, $response);
        if (0 === $thing->getTotalItems() && $request instanceof RetryRequestInterface && $retryRequest = $request->getRetryRequest()) {
            return $this->request($retryRequest);
        }

        return new Response($thing);
    }

    private function buildCacheKey(RequestInterface $request): string
    {
        $key = sprintf(
            '%s|%s',
            $request->getType(),
            implode('|', array_values($request->getParams())),
        );

        return $this->sanitizeKey($key);
    }
}
