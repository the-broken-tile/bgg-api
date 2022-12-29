<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\Request\RetryRequestInterface;

final class Client implements ClientInterface
{
    use SanitizeCacheKeyTrait;

    private const METHOD = 'GET';

    private ObjectBuilderManagerInterface $objectBuilder;
    private HttpClientInterface $client;
    private CacheInterface $cache;
    private UrlGeneratorInterface $urlGenerator;
    private CacheTagGeneratorInterface $cacheTagGenerator;

    public function __construct(
        ObjectBuilderManagerInterface $objectBuilder,
        HttpClientInterface $client = null,
        CacheInterface $cache = null,
        UrlGeneratorInterface $urlGenerator = null,
        CacheTagGeneratorInterface $cacheTagGenerator = null
    ) {
        $this->objectBuilder = $objectBuilder;
        $this->client = $client ?? new CurlHttpClient();
        $this->cache = $cache ?? new FilesystemTagAwareAdapter();
        $this->urlGenerator = $urlGenerator ?? new UrlGenerator();
        $this->cacheTagGenerator = $cacheTagGenerator ?? new CacheTagGenerator();
    }

    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->urlGenerator->generate($request);

        $response = $this->cache->get($this->buildCacheKey($request), function (ItemInterface $item) use ($url, $request): string {
            if ($this->cache instanceof TagAwareAdapterInterface) {
                $item->tag($this->cacheTagGenerator->generateTags($request));
            }

            return $this->client->request(self::METHOD, $url)->getContent();
        });
        \assert(\is_string($response));

        $thing = $this->objectBuilder->build($request, $response);
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
