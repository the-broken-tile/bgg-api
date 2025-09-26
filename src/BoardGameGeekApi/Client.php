<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi;

use Psr\Cache\InvalidArgumentException as CacheInvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\Exception\InvalidArgumentException;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\Request\RetryRequestInterface;

final readonly class Client implements ClientInterface
{
    use SanitizeCacheKeyTrait;

    private const string METHOD = 'GET';

    public function __construct(
        private ObjectBuilderManagerInterface $objectBuilder,
        private HttpClientInterface $client,
        private TagAwareCacheInterface $cache,
        private UrlGeneratorInterface $urlGenerator,
        private CacheTagGeneratorInterface $cacheTagGenerator,
    ) {}

    public function request(RequestInterface $request): ResponseInterface
    {
        $url = $this->urlGenerator->generate($request);

        try {
            $response = $this->cache->get($this->buildCacheKey($request), function (ItemInterface $item) use ($url, $request): string {
                $item->tag($this->cacheTagGenerator->generateTags($request));

                return $this->client->request(self::METHOD, $url)->getContent();
            });
        } catch (CacheInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }

        if (!is_string($response)) {
            throw new InvalidArgumentException();
        }

        $thing = $this->objectBuilder->build($request, $response);
        if (0 === $thing->totalItems && $request instanceof RetryRequestInterface && $retryRequest = $request->getRetryRequest()) {
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
