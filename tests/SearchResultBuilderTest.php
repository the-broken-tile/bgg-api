<?php

namespace TheBrokenTile\Test;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\SearchResultBuilder;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\SearchResultBuilder
 */
final class SearchResultBuilderTest extends TestCase
{
    /**
     * @covers ::supports
     */
    public function testSupports(): void
    {
        $builder = new SearchResultBuilder();
        self::assertTrue($builder->supports(new SearchRequest('Dixit')));
        self::assertFalse($builder->supports(new GameRequest(1)));
    }

    /**
     * @covers ::build
     * @dataProvider supportsDataProvider
     */
    public function testBuild(
        string   $fixture,
        int      $expectedTotal,
        int      $expectedItemId,
        string   $expectedItemType,
        GameName $expectedItemName,
        int      $expectedItemYearPublished
    ): void
    {
        $response = file_get_contents(__DIR__ . $fixture);
        $builder = new SearchResultBuilder();

        $searchResults = $builder->build($response);
        self::assertSame($expectedTotal, $searchResults->total);
        self::assertCount($expectedTotal, $searchResults->items);

        $item = current($searchResults->items);
        self::assertInstanceOf(SearchItem::class, $item);
        self::assertSame($expectedItemId, $item->id);
        self::assertSame($expectedItemType, $item->type);
        self::assertEquals($expectedItemName, $item->name);
        self::assertSame($expectedItemYearPublished, $item->yearPublished);
    }

    /**
     * @return array<string, mixed[]>
     */
    public function supportsDataProvider(): array
    {
        return [
            'exact' => [
                'fixture' => '/fixtures/search_exact.xml',
                'expectedTotal' => 1,
                'expectedItemId' => 39856,
                'expectedItemType' => SearchItem::TYPE_BOARD_GAME,
                'expectedItemName' => new GameName(1, GameName::TYPE_PRIMARY, 'Dixit'),
                'expectedItemYearPublished' => 2008,
            ],
            'not exact' => [
                'fixture' => '/fixtures/search.xml',
                'expectedTotal' => 81,
                'expectedItemId' => 39856,
                'expectedItemType' => SearchItem::TYPE_BOARD_GAME,
                'expectedItemName' => new GameName(1, GameName::TYPE_PRIMARY, 'Dixit'),
                'expectedItemYearPublished' => 2008,
            ],
        ];
    }
}
