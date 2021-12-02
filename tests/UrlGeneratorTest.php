<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\Request\CollectionRequest;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;
use TheBrokenTile\BoardGameGeekApi\UrlGenerator;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\UrlGenerator
 *
 * @internal
 */
final class UrlGeneratorTest extends TestCase
{
    /**
     * @covers ::generate
     */
    public function testGenerateCustomBaseUrl(): void
    {
        $urlGenerator = new UrlGenerator('google.com');

        self::assertSame('google.com/thing?id=5', $urlGenerator->generate(new GameRequest(5)));
    }

    /**
     * @covers ::generate
     * @dataProvider dataProvider
     */
    public function testGenerate(string $expected, RequestInterface $request): void
    {
        $urlGenerator = new UrlGenerator();

        self::assertSame($expected, $urlGenerator->generate($request));
    }

    /**
     * @return array<string, mixed[]>
     */
    public function dataProvider(): array
    {
        return [
            'game' => [
                'https://api.geekdo.com/xmlapi2/thing?id=1',
                new GameRequest(1),
            ],
            'collection' => [
                'https://api.geekdo.com/xmlapi2/collection?username=tazzadar1337',
                new CollectionRequest('tazzadar1337'),
            ],
            'search' => [
                'https://api.geekdo.com/xmlapi2/search?query=Carcassonne&type=boardgame',
                new SearchRequest('Carcassonne'),
            ],
            'exact search' => [
                'https://api.geekdo.com/xmlapi2/search?query=Carcassonne&exact=1&type=boardgame',
                new SearchRequest('Carcassonne', true),
            ],
            'user' => [
                'https://api.geekdo.com/xmlapi2/user?name=tazzadar1337&page=1',
                new UserRequest('tazzadar1337'),
            ],
            'trim semicolons' => [
                'https://api.geekdo.com/xmlapi2/search?query=Doomtown+Reloaded&exact=1&type=boardgame',
                new SearchRequest('Doomtown: Reloaded', true),
            ],
        ];
    }
}
