<?php

namespace TheBrokenTile\Test;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GamePoll;
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
     */
    public function testBuild(): void
    {
        $builder = new GameBuilder();
        $response = file_get_contents(__DIR__.'/fixtures/game_no_stats.xml');

        $game = $builder->build($response);
        self::assertInstanceOf(Game::class, $game);
        self::assertSame(822, $game->id);
        self::assertSame('https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__original/img/9LEvU4EbbBrJB36YgWQXeXQYwjo=/0x0/filters:format(jpeg)/pic2337577.jpg', $game->image);
        self::assertSame('https://cf.geekdo-images.com/Z3upN53-fsVPUDimN9SpOA__thumb/img/_C5pWATlaq3uS8u7IlFb0WMi_ak=/fit-in/200x150/filters:strip_icc()/pic2337577.jpg', $game->thumbnail);
        self::assertStringStartsWith('Carcassonne is a tile-placement game in which the players draw and place a tile', $game->description);
        self::assertSame(2000, $game->yearPublished);
        self::assertSame(2, $game->minPlayers);
        self::assertSame(5, $game->maxPlayers);
        self::assertSame(45, $game->playingTime);
        self::assertSame(30, $game->minPlayTime);
        self::assertSame(45, $game->maxPlayTime);
        self::assertSame(7, $game->minAge);

        self::assertCount(17, $game->names);
        $name = current($game->names);
        self::assertInstanceOf(GameName::class, $name);
        self::assertSame(GameName::TYPE_PRIMARY, $name->type);
        self::assertSame(1, $name->sortIndex);
        self::assertSame('Carcassonne', $name->value);

        self::assertCount(243, $game->links);
        $link = current($game->links);
        self::assertInstanceOf(GameLink::class, $link);
        self::assertSame(GameLink::TYPE_CATEGORY, $link->type);
        self::assertSame(1029, $link->id);
        self::assertSame('City Building', $link->value);

        self::assertCount(3, $game->polls);
        $poll = current($game->polls);
        self::assertInstanceOf(GamePoll::class, $poll);
        self::assertSame('suggested_numplayers', $poll->name);
        self::assertSame('User Suggested Number of Players', $poll->title);
        self::assertSame(2153, $poll->totalVotes);

        // @todo rework poll results, they have different structure, current implementation doesn't support both.
        self::assertCount(18, $poll->results);

        // @todo add test with stats.
        self::assertNull($game->stats);
    }
}
