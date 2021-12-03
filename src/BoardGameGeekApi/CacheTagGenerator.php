<?php

namespace TheBrokenTile\BoardGameGeekApi;

final class CacheTagGenerator implements CacheTagGeneratorInterface
{
    use SanitizeCacheKeyTrait;

    public function generateTags(RequestInterface $request): array
    {
        $tags = [
            sprintf('%s.%s%s', CacheTagGeneratorInterface::CACHE_TAG_PREFIX, self::TYPE_PREFIX, $request->getType()),
        ];
        foreach ($request->getParams() as $k => $v) {
            $tags[] = $this->sanitizeKey(sprintf('%s.%s_%s', CacheTagGeneratorInterface::CACHE_TAG_PREFIX, $k, $v));
        }

        return $tags;
    }
}