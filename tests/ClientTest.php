<?php

namespace TheBrokenTile\Test\BoardGameGeekApi;

use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheBrokenTile\BoardGameGeekApi\Client;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderManagerInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;
use TheBrokenTile\BoardGameGeekApi\UrlGeneratorInterface;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\Client
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
        $cache = $this->prophesize(CacheInterface::class);
        $objectBuilder = $this->prophesize(ObjectBuilderManagerInterface::class);

        $stringResponse = 'response';

        $cache->get(Argument::type('string'), Argument::type('callable'))
            ->willReturn($stringResponse);

        $request = $this->prophesize(RequestInterface::class);
        $request->getType()->willReturn('type');
        $request->getParams()->willReturn([]);
        $request = $request->reveal();

        $thing = $this->prophesize(DataTransferObjectInterface::class)->reveal();

        $objectBuilder->build($request, $stringResponse)
            ->shouldBeCalledOnce()
            ->willReturn($thing);

        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator->generate($request)->willReturn('boardgamegeek.api');

        $client = new Client(
            $httpClient->reveal(),
            $cache->reveal(),
            $objectBuilder->reveal(),
            $urlGenerator->reveal(),
        );

        self::assertSame($thing, $client->request($request)->getData());
    }
}
