<?php

namespace TheBrokenTile\Test;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GamePoll;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRank;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameStatistics;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\GameBuilder;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;

/**
 * @coversDefaultCLass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\GameBuilder
 */
final class GameBuilderTest extends TestCase
{
    /**
     * @covers ::supports
     */
    public function testSupports(): void
    {
        $builder = new GameBuilder();

        self::assertTrue($builder->supports(new GameRequest(1)));
        self::assertFalse($builder->supports(new SearchRequest('test')));
    }

    /**
     * @covers ::build
     * @dataProvider buildDataProvider
     */
    public function testBuild(
        int             $expectedId,
        string          $expectedImage,
        string          $expectedThumbnail,
        string          $expectedDescriptionStart,
        int             $expectedYearPublished,
        int             $expectedMinPlayers,
        int             $expectedMaxPlayers,
        int             $expectedPlayingTime,
        int             $expectedMinPlayTime,
        int             $expectedMaxPlayTime,
        int             $expectedMinAge,
        int             $expectedNamesCount,
        int             $nameIndex,
        GameName        $expectedName,
        int             $expectedLinksCount,
        int             $linkIndex,
        GameLink        $expectedGameLink,
        int             $expectedPollsCount,
        string          $expectedPollName,
        string          $expectedPollTitle,
        int             $expectedPollVotes,
        int             $expectedPollResultsCount,
        ?GameStatistics $expectedStats,
        string          $fixture
    ): void
    {
        $builder = new GameBuilder();
        $response = file_get_contents(__DIR__ . $fixture);
        assert(is_string($response));

        $game = $builder->build($response);
        self::assertInstanceOf(Game::class, $game);
        self::assertSame($expectedId, $game->id);
        self::assertSame($expectedImage, $game->image);
        self::assertSame($expectedThumbnail, $game->thumbnail);
        self::assertStringStartsWith($expectedDescriptionStart, $game->description);
        self::assertSame($expectedYearPublished, $game->yearPublished);
        self::assertSame($expectedMinPlayers, $game->minPlayers);
        self::assertSame($expectedMaxPlayers, $game->maxPlayers);
        self::assertSame($expectedPlayingTime, $game->playingTime);
        self::assertSame($expectedMinPlayTime, $game->minPlayTime);
        self::assertSame($expectedMaxPlayTime, $game->maxPlayTime);
        self::assertSame($expectedMinAge, $game->minAge);

        self::assertCount($expectedNamesCount, $game->names);

        $name = $game->names[$nameIndex];
        self::assertEquals($expectedName, $name);

        self::assertCount($expectedLinksCount, $game->links);
        $link = $game->links[$linkIndex];
        self::assertEquals($expectedGameLink, $link);

        // @todo rework poll results, they have different structure, current implementation doesn't support both.
        self::assertCount($expectedPollsCount, $game->polls);
        $poll = current($game->polls);
        assert($poll instanceof GamePoll);
        self::assertSame($expectedPollName, $poll->name);
        self::assertSame($expectedPollTitle, $poll->title);
        self::assertSame($expectedPollVotes, $poll->totalVotes);
        self::assertCount($expectedPollResultsCount, $poll->results);

        self::assertEquals($expectedStats, $game->stats);
    }

    /**
     * @return array<string, mixed[]>
     */
    public function buildDataProvider(): array
    {
        $base = [
            'expectedId' => 822,
            'expectedImage' => 'https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__original/img/9LEvU4EbbBrJB36YgWQXeXQYwjo=/0x0/filters:format(jpeg)/pic2337577.jpg',
            'expectedThumbnail' => 'https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__thumb/img/_C5pWATlaq3uS8u7IlFb0WMi_ak=/fit-in/200x150/filters:strip_icc()/pic2337577.jpg',
            'expectedDescriptionStart' => 'Carcassonne is a tile-placement game in which the players draw and place a tile',
            'expectedYearPublished' => 2000,
            'expectedMinPlayers' => 2,
            'expectedMaxPlayers' => 5,
            'expectedPlayingTime' => 45,
            'expectedMinPlayTime' => 30,
            'expectedMaxPlayTime' => 45,
            'expectedMinAge' => 7,
            'expectedNamesCount' => 17,
            'nameIndex' => null,//placeholder
            'expectedName' => null,//placeholder
            'expectedLinksCount' => 243,
            'linkIndex' => null,//placeholder
            'expectedLink' => null,//placeholder
            'expectedPollsCount' => 3,
            'expectedPollName' => 'suggested_numplayers',
            'expectedPollTitle' => 'User Suggested Number of Players',
            'expectedPollVotes' => 2154,
            'expectedPollResultsCount' => 18,
        ];

        return [
            'stats' => array_merge($base, [
                'expectedStats' => $this->buildStats(
                    107363,
                    7.41855,
                    158733,
                    1696,
                    577,
                    7250,
                    19100,
                    7657,
                    1.9071,
                    7.30909,
                    1.30574,
                    0.0,
                    [
                        new GameRank(
                            1,
                            'boardgame',
                            GameRank::TYPE_SUBTYPE,
                            'Board Game Rank',
                            185,
                            7.30909,
                        ),
                        new GameRank(
                            5499,
                            'familygames',
                            GameRank::TYPE_FAMILY,
                            'Family Game Rank',
                            40,
                            7.30109,
                        ),
                    ]
                ),
                'nameIndex' => 1,
                'expectedName' => new GameName(1, GameName::TYPE_ALTERNATE, 'Carcassonne Jubilee Edition'),
                'linkIndex' => 0,
                'expectedLink' => new GameLink(1029, GameLink::TYPE_CATEGORY, 'City Building'),
                'fixture' => '/fixtures/game.xml',
            ]),
            'no stats' => array_merge($base, [
                'expectedStats' => null,
                'nameIndex' => 0,
                'expectedName' => new GameName(1, GameName::TYPE_PRIMARY, 'Carcassonne'),
                'linkIndex' => 203,
                'expectedLink' => new GameLink(398, GameLink::TYPE_DESIGNER, 'Klaus-Jürgen Wrede'),
                'fixture' => '/fixtures/game_no_stats.xml',
            ]),
        ];
    }

    /**
     * @param GameRank[] $ranks
     */
    private function buildStats(
        int   $usersRated,
        float $average,
        int   $owned,
        int   $trading,
        int   $wanting,
        int   $wishing,
        int   $numComments,
        int   $numWeights,
        float $averageWeight,
        float $bayesAverage,
        float $stdDev,
        float $median,
        array $ranks
    ): GameStatistics
    {
        $stats = new GameStatistics();
        $stats->ratings->usersRated = $usersRated;
        $stats->ratings->average = $average;
        $stats->ratings->owned = $owned;
        $stats->ratings->trading = $trading;
        $stats->ratings->wanting = $wanting;
        $stats->ratings->wishing = $wishing;
        $stats->ratings->numComments = $numComments;
        $stats->ratings->numWeights = $numWeights;
        $stats->ratings->averageWeight = $averageWeight;
        $stats->ratings->bayesAverage = $bayesAverage;
        $stats->ratings->stdDev = $stdDev;
        $stats->ratings->median = $median;
        $stats->ratings->ranks = $ranks;

        return $stats;
    }
}
