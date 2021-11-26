<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Collection;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionStatus;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionVersion;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRank;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameStatistics;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\CollectionBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\CollectionRequest;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\CollectionBuilder
 */
final class CollectionBuilderTest extends TestCase
{
    /**
     * @covers ::supports
     */
    public function testSupports(): void
    {
        $collectionBuilder = new CollectionBuilder();

        self::assertTrue($collectionBuilder->supports(new CollectionRequest('username')));

        self::assertFalse($collectionBuilder->supports(new UserRequest('username')));
    }

    /**
     * @covers ::build
     * @param GameName[] $itemNames
     * @dataProvider buildDataProvider
     */
    public function testBuild(
        int                $itemIndex,
        string             $fixture,
        string             $pubDate,
        int                $totalItems,
        string             $itemType,
        int                $itemId,
        string             $itemSubType,
        int                $itemCollectionId,
        ?string            $itemImage,
        ?string            $itemThumbnail,
        array              $itemNames,
        CollectionStatus   $itemStatus,
        ?int               $itemYearPublished,
        ?int               $itemNumberOfPlays,
        ?string            $itemComment,
        ?CollectionVersion $itemVersion,
        ?GameStatistics    $itemStats
    ): void {
        $collectionBuilder = new CollectionBuilder();
        $response = file_get_contents(__DIR__ . $fixture);
        assert(is_string($response));

        $collection = $collectionBuilder->build($response);
        self::assertInstanceOf(Collection::class, $collection);

        self::assertSame($totalItems, $collection->totalItems);
        self::assertCount($totalItems, $collection->items);
        self::assertSame($pubDate, $collection->pubDate);

        //Items
        $item = $collection->items[$itemIndex];
        self::assertInstanceOf(CollectionItem::class, $item);
        self::assertSame($itemType, $item->objectType);
        self::assertSame($itemId, $item->objectId);
        self::assertSame($itemSubType, $item->subType);
        self::assertSame($itemCollectionId, $item->collId);
        self::assertSame($itemImage, $item->image);
        self::assertSame($itemThumbnail, $item->thumbnail);
        self::assertEquals($itemNames, $item->names);

        //Status
        self::assertEquals($itemStatus, $item->status);
        self::assertSame($itemYearPublished, $item->yearPublished);
        self::assertSame($itemNumberOfPlays, $item->numberOfPlays);
        self::assertSame($itemComment, $item->comment);
        self::assertEquals($itemVersion, $item->version);
        self::assertEquals($itemStats, $item->stats);
    }

    /**
     * @return array<string, mixed[]>
     */
    public function buildDataProvider(): array
    {
        $base = [
            'itemIndex' => 0,
            'fixture' => null,//placeholder
            'pubDate' => null,//placeholder
            'totalItems' => 480,
            'itemType' => 'thing',
            'itemId' => null,//placeholder
            'itemSubType' => 'boardgame',
            'itemCollectionId' => null,//placeholder
            'itemImage' => null,
            'itemThumbnail' => null,
            'itemNames' => null,//placeholder
            'itemStatus' => null,//placeholder
            'itemYearPublished' => null,
            'itemNumberOfPlays' => null,
            'itemComment' => null,
            'itemVersion' => null,
            'itemStats' => null,
        ];

        $threeWishesRanks = [new GameRank(1, 'boardgame', GameRank::TYPE_SUBTYPE, 'Board Game Rank', 18548, 5.49373)];

        return [
            'brief' => array_merge($base, [
                'fixture' => '/fixtures/collection_brief.xml',
                'pubDate' => 'Tue, 09 Nov 2021 13:53:29 +0000',
                'itemId' => 198836,
                'itemCollectionId' => 46499506,
                'itemNames' => [new GameName(1, null, '3 Wishes')],
                'itemStatus' => new CollectionStatus(
                    true,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    '2018-03-02 05:30:09',
                    null
                ),
            ]),
            'collection' => array_merge($base, [
                'fixture' => '/fixtures/collection.xml',
                'pubDate' => 'Tue, 09 Nov 2021 12:01:46 +0000',
                'itemId' => 198836,
                'itemCollectionId' => 46499506,
                'itemImage' => 'https://cf.geekdo-images.com/cLo9vChf7MbXI4buDQ2JCg__original/img/9kTwYwU94LuzzqnirGWpDtGToEo=/0x0/filters:format(png)/pic2993325.png',
                'itemThumbnail' => 'https://cf.geekdo-images.com/cLo9vChf7MbXI4buDQ2JCg__thumb/img/OW6tryx9J4Vc0ZQBlc-li2JwhAU=/fit-in/200x150/filters:strip_icc()/pic2993325.png',
                'itemNames' => [new GameName(1, null, '3 Wishes')],
                'itemStatus' => new CollectionStatus(
                    true,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    '2018-03-02 05:30:09',
                    null
                ),
                'itemYearPublished' => 2016,
                'itemNumberOfPlays' => 1,
                'itemComment' => "Spiel'17",
            ]),
            'stats' => array_merge($base, [
                'fixture' => '/fixtures/collection_stats.xml',
                'pubDate' => 'Tue, 09 Nov 2021 14:06:53 +0000',
                'itemId' => 198836,
                'itemCollectionId' => 46499506,
                'itemImage' => 'https://cf.geekdo-images.com/cLo9vChf7MbXI4buDQ2JCg__original/img/9kTwYwU94LuzzqnirGWpDtGToEo=/0x0/filters:format(png)/pic2993325.png',
                'itemThumbnail' => 'https://cf.geekdo-images.com/cLo9vChf7MbXI4buDQ2JCg__thumb/img/OW6tryx9J4Vc0ZQBlc-li2JwhAU=/fit-in/200x150/filters:strip_icc()/pic2993325.png',
                'itemNames' => [new GameName(1, null, '3 Wishes')],
                'itemStatus' => new CollectionStatus(
                    true,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    '2018-03-02 05:30:09',
                    null
                ),
                'itemYearPublished' => 2016,
                'itemNumberOfPlays' => 1,
                'itemComment' => "Spiel'17",
                'itemStats' => $this->buildStatistics(
                    1024,
                    5.51024,
                    5.49373,
                    1.48839,
                    0,
                    $threeWishesRanks,
                ),
            ]),
            'brief_stats' => array_merge($base, [
                'itemIndex' => 0,
                'fixture' => '/fixtures/collection_brief_stats.xml',
                'pubDate' => 'Tue, 09 Nov 2021 14:06:41 +0000',
                'itemId' => 198836,
                'itemCollectionId' => 46499506,
                'itemNames' => [new GameName(1, null, '3 Wishes')],
                'itemStatus' => new CollectionStatus(
                    true,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    '2018-03-02 05:30:09',
                    null
                ),
                'itemStats' => $this->buildStatistics(
                    null,
                    5.51024,
                    5.49373,
                    null,
                    null,
                    []
                ),
            ]),
            'version' => array_merge($base, [
                'itemIndex' => 2,
                'fixture' => '/fixtures/collection_version.xml',
                'pubDate' => 'Tue, 09 Nov 2021 12:20:12 +0000',
                'itemId' => 145209,
                'itemCollectionId' => 27875328,
                'itemImage' => 'https://cf.geekdo-images.com/EjLDriIcasQlJQUHqRfOtQ__original/img/rJMMssiJCDOo_eElyDSybnrv_Zc=/0x0/filters:format(jpeg)/pic1721930.jpg',
                'itemThumbnail' => 'https://cf.geekdo-images.com/EjLDriIcasQlJQUHqRfOtQ__thumb/img/Rh6MxWEW0dc4O7EYOQEYdbCxLUU=/fit-in/200x150/filters:strip_icc()/pic1721930.jpg',
                'itemNames' => [new GameName(1, null, '404: Law Not Found')],
                'itemStatus' => new CollectionStatus(
                    true,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    false,
                    '2015-12-31 03:25:41',
                    null
                ),
                'itemYearPublished' => 2015,
                'itemNumberOfPlays' => 0,
                'itemVersion' => $this->createVersion(
                    214187,
                    CollectionVersion::TYPE_VERSION,
                    'https://cf.geekdo-images.com/EjLDriIcasQlJQUHqRfOtQ__original/img/rJMMssiJCDOo_eElyDSybnrv_Zc=/0x0/filters:format(jpeg)/pic1721930.jpg',
                    'https://cf.geekdo-images.com/EjLDriIcasQlJQUHqRfOtQ__thumb/img/Rh6MxWEW0dc4O7EYOQEYdbCxLUU=/fit-in/200x150/filters:strip_icc()/pic1721930.jpg',
                    [
                        new GameLink(145209, GameLink::TYPE_VERSION, '404: Law Not Found'),
                        new GameLink(23299, GameLink::TYPE_PUBLISHER, '3DTotal Games'),
                        new GameLink(69356, GameLink::TYPE_ARTIST, 'Ludwin Schouten'),
                        new GameLink(2184, GameLink::TYPE_LANGUAGE, 'English'),
                    ],
                    [new GameName(1, GameName::TYPE_PRIMARY, 'English first edition')]
                ),
            ]),
        ];
    }

    /**
     * @param GameRank[] $ranks
     */
    private function buildStatistics(
        ?int   $usersRated,
        float  $average,
        float  $bayesAverage,
        ?float $stdDev,
        ?float $median,
        array $ranks
    ): GameStatistics {
        $stats = new GameStatistics();
        $stats->ratings->usersRated = $usersRated;
        $stats->ratings->average = $average;
        $stats->ratings->bayesAverage = $bayesAverage;
        $stats->ratings->stdDev = $stdDev;
        $stats->ratings->median = $median;
        $stats->ratings->ranks = $ranks;

        return $stats;
    }

    /**
     * @param GameLink[] $links
     * @param GameName[] $names
     */
    private function createVersion(int $id, string $type, string $image, string $thumbnail, array $links, array $names): CollectionVersion
    {
        $version = new CollectionVersion($id, $type);
        $version->image = $image;
        $version->thumbnail = $thumbnail;
        $version->links = $links;
        $version->names = $names;

        return $version;
    }
}
