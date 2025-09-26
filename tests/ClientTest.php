<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\Runtime\PropertyHook;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\CacheTagGeneratorInterface;
use TheBrokenTile\BoardGameGeekApi\Client;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\Exception\ExceptionInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;
use TheBrokenTile\BoardGameGeekApi\UrlGeneratorInterface;

/**
 * @internal
 */
#[CoversClass(Client::class)]
final class ClientTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testRequest(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $cache = $this->createMock(TagAwareCacheInterface::class);
        $objectBuilder = $this->createMock(ObjectBuilderManagerInterface::class);

        $stringResponse = '::response::';

        $cache->expects(self::once())
            ->method('get')
            ->with(self::isString(), self::isCallable())
            ->willReturn($stringResponse)
        ;

        $request = $this->createMock(RequestInterface::class);
        $request->method('getType')
            ->willReturn('::type::')
        ;
        $request->method('getParams')
            ->willReturn([])
        ;

        $thing = $this->createMock(DataTransferObjectInterface::class);
        $thing->method(PropertyHook::get('totalItems'))
            ->willReturn(1)
        ;

        $objectBuilder->expects(self::once())
            ->method('build')
            ->willReturn($thing)
        ;

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')
            ->with($request)
            ->willReturn('::boardgamegeek.api::')
        ;

        $cacheTagGenerator = $this->createMock(CacheTagGeneratorInterface::class);

        $client = new Client(
            $objectBuilder,
            $httpClient,
            $cache,
            $urlGenerator,
            $cacheTagGenerator,
        );

        self::assertSame($thing, $client->request($request)->data);
    }
}
