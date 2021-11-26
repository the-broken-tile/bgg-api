<?php

declare(strict_types=1);

namespace TheBrokenTile\Test\BoardGameGeekApi\ObjectBuilder;

use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\User;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserBuddy;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserHotItem;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\UserBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\UserBuilder
 *
 * @internal
 */
final class UserBuilderTest extends TestCase
{
    /**
     * @covers ::suppports
     */
    public function testSupports(): void
    {
        $userBuilder = new UserBuilder();
        self::assertTrue($userBuilder->supports(new UserRequest('test')));

        self::assertFalse($userBuilder->supports(new SearchRequest('test')));
    }

    /**
     * @covers ::build
     */
    public function testBuild(): void
    {
        $userBuilder = new UserBuilder();
        $response = file_get_contents(__DIR__.'/fixtures/user.xml');
        assert(is_string($response));

        $user = $userBuilder->build($response);
        self::assertInstanceOf(User::class, $user);
        self::assertSame(844486, $user->id);
        self::assertSame('tazzadar1337', $user->name);
        self::assertSame('Rusi', $user->firstName);
        self::assertSame('Papazov', $user->lastName);
        self::assertSame('https://cf.geekdo-static.com/avatars/avatar_id151036.jpg', $user->avatarLink);
        self::assertSame(2014, $user->yearRegistered);
        self::assertSame('2021-11-24', $user->lastLogin);
        self::assertSame('BULGARIA', $user->stateOrProvince);
        self::assertSame('Bulgaria', $user->country);
        self::assertSame('https://the-broken-tile.github.io/', $user->webAddress);
        self::assertSame('', $user->xBoxAccount);
        self::assertSame('', $user->wiiAccount);
        self::assertSame('', $user->psnAccount);
        self::assertSame('', $user->battleNetAccount);
        self::assertSame('', $user->steamAccount);
        self::assertSame(0, $user->tradeRating);
        self::assertSame(2, $user->marketRating);

        //Buddies.
        self::assertCount(9, $user->buddies);
        $buddy = current($user->buddies);
        assert($buddy instanceof UserBuddy);
        self::assertSame(1201816, $buddy->id);
        self::assertSame('aymaliev', $buddy->name);

        //Guilds.
        self::assertEmpty($user->guilds);

        //Hot.
        self::assertCount(1, $user->hot);
        $hot = current($user->hot);
        self::assertInstanceOf(UserHotItem::class, $hot);

        //Top.
        self::assertEmpty($user->top);
    }
}
