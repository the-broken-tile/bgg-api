<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GamePoll;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRank;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRatings;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameResults;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameStatistics;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\PollResult;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\GameBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @internal
 */
#[CoversClass(GameBuilder::class)]
final class GameBuilderTest extends TestCase
{
    private GameBuilder $builder;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new GameBuilder();
    }

    public function testSupports(): void
    {
        self::assertTrue($this->builder->supports(new GameRequest(1)));
        self::assertFalse($this->builder->supports(new SearchRequest('::test::')));
    }

    /**
     * @throws Exception
     */
    public function BuildWithStats(): void
    {
        /** @var string $response */
        $response = file_get_contents(__DIR__.'/fixtures/game.xml');

        $results = $this->builder->build($response, $this->createMock(RequestInterface::class));

        self::assertEquals(
            new GameResults([
                $this->mockCarcassonneGame(
                    stats: $this->mockStats(),
                    names: $this->mockCarcassoneNames(),
                    links: $this->mockCarcassonneLinks(),
                    polls: $this->mockPolls(),
                )]),
            $results,
        );
    }

    /**
     * @throws Exception
     */
    public function testBuildWithoutStats(): void
    {
        /** @var string $response */
        $response = file_get_contents(__DIR__.'/fixtures/game_no_stats.xml');

        $results = $this->builder->build($response, $this->createMock(RequestInterface::class));

        $game = $this->mockCarcassonneGame(null, $this->mockCarcassoneNames(), $this->mockCarcassonneLinks(), $this->mockPolls());

        self::assertEquals(new GameResults([$game]), $results);
    }

    /**
     * @throws Exception
     */
    public function testBuildMultiGames(): void
    {
        /** @var string $response */
        $response = file_get_contents(__DIR__.'/fixtures/multi_games.xml');

        $results = $this->builder->build($response, $this->createMock(RequestInterface::class));

        self::assertEquals(
            new GameResults([
                $this->mockCarcassonneGame(
                    stats: null,
                    names: $this->mockCarcassoneNames(),
                    links: $this->mockCarcassonneLinks(),
                    polls: $this->mockPolls(),
                ),
                $this->mockPandaMoniumGame(),
            ]),
            $results,
        );
    }

    private function mockStats(): GameStatistics
    {
        $ratings = new GameRatings();
        $ratings->ranks = [
            new GameRank(
                id: 1,
                name: 'boardgame',
                type: GameRank::TYPE_SUBTYPE,
                friendlyName: 'Board Game Rank',
                value: 185,
                bayesAverage: 7.30909,
            ),
            new GameRank(
                id: 5499,
                name: 'familygames',
                type: GameRank::TYPE_FAMILY,
                friendlyName: 'Family Game Rank',
                value: 40,
                bayesAverage: 7.30109,
            ),
        ];
        $ratings->owned = 158733;
        $ratings->trading = 1696;
        $ratings->wanting = 577;
        $ratings->wishing = 7250;
        $ratings->numComments = 19100;
        $ratings->numWeights = 7657;
        $ratings->averageWeight = 1.9071;
        $ratings->usersRated = 107363;
        $ratings->average = 7.41855;
        $ratings->bayesAverage = 7.30909;
        $ratings->stdDev = 1.30574;
        $ratings->median = 0.0;

        return new GameStatistics($ratings);
    }

    /**
     * @return GameName[]
     */
    private function mockCarcassoneNames(): array
    {
        return [
            new GameName(1, GameName::TYPE_PRIMARY, 'Carcassonne'),
            new GameName(1, GameName::TYPE_ALTERNATE, 'Каркасон'),
        ];
    }

    /**
     * @return GameLink[]
     */
    private function mockCarcassonneLinks(): array
    {
        return [
            new GameLink(id: 1029, type: GameLink::TYPE_CATEGORY, value: 'City Building'),
            new GameLink(id: 1035, type: GameLink::TYPE_CATEGORY, value: 'Medieval'),
        ];
    }

    /**
     * @return GamePoll[]
     */
    private function mockPolls(): array
    {
        return [
            new GamePoll(
                name: 'suggested_numplayers',
                title: 'User Suggested Number of Players',
                totalVotes: 2154,
                results: [
                    new PollResult(value: 'Best', numVotes: 6), // 1, missing prop to fill this in
                    new PollResult(value: 'Recommended', numVotes: 60), // 1
                    new PollResult(value: 'Not Recommended', numVotes: 1294), // 1
                    new PollResult(value: 'Best', numVotes: 1141), // 2
                    new PollResult(value: 'Recommended', numVotes: 767), // 2
                    new PollResult(value: 'Not Recommended', numVotes: 104), // 2
                ],
            ),
            new GamePoll(
                name: 'suggested_playerage',
                title: 'User Suggested Player Age',
                totalVotes: 284,
                results: [
                    new PollResult(value: '2', numVotes: 2),
                    new PollResult(value: '8', numVotes: 282),
                    new PollResult(value: '21 and up', numVotes: 0),
                ],
            ),
            new GamePoll(
                name: 'language_dependence',
                title: 'Language Dependence',
                totalVotes: 462,
                results: [
                    new PollResult(value: 'No necessary in-game text', numVotes: 461),
                    new PollResult(value: 'Unplayable in another language', numVotes: 1),
                ],
            ),
        ];
    }

    /**
     * @param GameName[] $names
     * @param GameLink[] $links
     * @param GamePoll[] $polls
     */
    private function mockCarcassonneGame(?GameStatistics $stats, array $names, array $links, array $polls): Game
    {
        $game = new Game();
        $game->image = 'https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__original/img/9LEvU4EbbBrJB36YgWQXeXQYwjo=/0x0/filters:format(jpeg)/pic2337577.jpg';
        $game->thumbnail = 'https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__thumb/img/_C5pWATlaq3uS8u7IlFb0WMi_ak=/fit-in/200x150/filters:strip_icc()/pic2337577.jpg';
        $game->id = 822;
        $game->description = 'Carcassonne is a tile-placement game in which the players draw and place a tile with a piece of southern French landscape on it.';
        $game->yearPublished = 2000;
        $game->minPlayers = 2;
        $game->maxPlayers = 5;
        $game->playingTime = 45;
        $game->minPlayTime = 30;
        $game->maxPlayTime = 45;
        $game->minAge = 7;

        $game->stats = $stats;
        $game->names = $names;
        $game->links = $links;
        $game->polls = $polls;

        return $game;
    }

    private function mockPandaMoniumGame(): Game
    {
        $game = new Game();
        $game->image = 'https://cf.geekdo-images.com/x4ls1E4Y7KMlCnPeRTbIew__original/img/WKuYbaFCdePj84tCTdypteyBHic=/0x0/filters:format(jpeg)/pic3031803.jpg';
        $game->thumbnail = 'https://cf.geekdo-images.com/x4ls1E4Y7KMlCnPeRTbIew__thumb/img/QUA66MlXNclGjOLqNbfGsKlrHm8=/fit-in/200x150/filters:strip_icc()/pic3031803.jpg';
        $game->id = 999;
        $game->description = 'Got quick reflexes and a good memory? Test your skills in this high-energy card game of musical mayhem.';
        $game->yearPublished = 1994;
        $game->minPlayers = 3;
        $game->maxPlayers = 6;
        $game->playingTime = 20;
        $game->minPlayTime = 20;
        $game->maxPlayTime = 20;
        $game->minAge = 6;
        $game->stats = null;

        $game->polls = [
            new GamePoll(
                name: 'suggested_numplayers',
                title: 'User Suggested Number of Players',
                totalVotes: 2,
                results: [
                    new PollResult(value: 'Recommended', numVotes: 1), // 3
                    new PollResult(value: 'Best', numVotes: 1), // 6
                ],
            ),
        ];
        $game->links = [
            new GameLink(1032, type: GameLink::TYPE_CATEGORY, value: 'Action / Dexterity'),
            new GameLink(1002, type: GameLink::TYPE_CATEGORY, value: 'Card Game'),
        ];

        $game->names = [
            new GameName(
                sortIndex: 1,
                type: GameName::TYPE_PRIMARY,
                value: 'Panda Monium',
            ),
            new GameName(
                sortIndex: 1,
                type: GameName::TYPE_ALTERNATE,
                value: 'Concerto Grosso',
            ),
            new GameName(
                sortIndex: 1,
                type: 'alternate',
                value: 'Little Amadeus: Concerto Grosso',
            ),
            new GameName(
                sortIndex: 1,
                type: 'alternate',
                value: '숲 속의 음악대',
            ),
        ];

        return $game;
    }
}
