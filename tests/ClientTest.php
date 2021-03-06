<?php

declare(strict_types=1);

namespace TheBrokenTile\Test\BoardGameGeekApi;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\CacheTagGeneratorInterface;
use TheBrokenTile\BoardGameGeekApi\Client;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;
use TheBrokenTile\BoardGameGeekApi\UrlGeneratorInterface;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\Client
 *
 * @internal
 */
final class ClientTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @covers ::request
     */
    public function testRequest(): void
    {
        $httpClient = $this->prophesize(HttpClientInterface::class);
        $cache = $this->prophesize(TagAwareCacheInterface::class);
        $objectBuilder = $this->prophesize(ObjectBuilderManagerInterface::class);

        $stringResponse = 'response';

        $cache->get(Argument::type('string'), Argument::type('callable'))
            ->willReturn($stringResponse)
        ;

        $request = $this->prophesize(RequestInterface::class);
        $request->getType()->willReturn('type');
        $request->getParams()->willReturn([]);
        $request = $request->reveal();

        $thing = $this->prophesize(DataTransferObjectInterface::class);
        $thing->getTotalItems()->willReturn(1);
        $thing = $thing->reveal();

        $objectBuilder->build($request, $stringResponse)
            ->shouldBeCalledOnce()
            ->willReturn($thing)
        ;

        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->generate($request)->willReturn('boardgamegeek.api');

        $cacheTagGenerator = $this->prophesize(CacheTagGeneratorInterface::class);

        $client = new Client(
            $objectBuilder->reveal(),
            $httpClient->reveal(),
            $cache->reveal(),
            $urlGenerator->reveal(),
            $cacheTagGenerator->reveal(),
        );

        self::assertSame($thing, $client->request($request)->getData());
    }
}
