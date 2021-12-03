<?php

namespace TheBrokenTile\Test\BoardGameGeekApi;

use TheBrokenTile\BoardGameGeekApi\CacheTagGenerator;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\Request\CollectionRequest;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\RetrySearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @covers \TheBrokenTile\BoardGameGeekApi\CacheTagGenerator
 */
final class CacheTagGeneratorTest extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testGenerateTags(array $expected, RequestInterface $request): void
    {
        $generator = new CacheTagGenerator();

        self::assertSame($expected, $generator->generateTags($request));
    }

    /**
     * @return array<string, mixed[]>
     */
    public function dataProvider(): array
    {
        $searchRequest = new SearchRequest('Aye, Dark Overlord!', true);
        return [
            'collection request' => [
                ['the_broken_tile.api_type_collection', 'the_broken_tile.username_tazzadar1337'],
                new CollectionRequest('tazzadar1337'),
            ],
            'game request' => [
                ['the_broken_tile.api_type_thing', 'the_broken_tile.id_822'],
                new GameRequest(822),
            ],
            'search request' => [
                ['the_broken_tile.api_type_search', 'the_broken_tile.query_Aye,DarkOverlord!', 'the_broken_tile.exact_1', 'the_broken_tile.type_boardgame'],
                $searchRequest,
            ],
            'retry search request' => [
                ['the_broken_tile.api_type_search', 'the_broken_tile.query_Aye,DarkOverlord!', 'the_broken_tile.exact_0', 'the_broken_tile.type_boardgame'],
                new RetrySearchRequest($searchRequest, ['exact' => '0']),
            ],
            'user request' => [
                ['the_broken_tile.api_type_user', 'the_broken_tile.name_tazzadar1337', 'the_broken_tile.buddies_1', 'the_broken_tile.page_3', 'the_broken_tile.hot_1'],
                (new UserRequest('tazzadar1337'))->buddies()->hot()->page(3),
            ]
        ];
    }
}
