<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\SearchResultBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @internal
 */
#[CoversClass(SearchResultBuilder::class)]
final class SearchResultBuilderTest extends TestCase
{
    public function testSupports(): void
    {
        $builder = new SearchResultBuilder();
        self::assertTrue($builder->supports(new SearchRequest('::Dixit::')));
        self::assertFalse($builder->supports(new GameRequest(1)));
    }

    /**
     * @throws Exception
     */
    #[DataProvider('provideBuildCases')]
    public function testBuild(
        string $fixture,
        int $expectedTotal,
        int $expectedItemId,
        string $expectedItemType,
        GameName $expectedItemName,
        int $expectedItemYearPublished,
    ): void {
        /** @var string $response */
        $response = file_get_contents(__DIR__.$fixture);
        $builder = new SearchResultBuilder();

        $searchResults = $builder->build($response, $this->createMock(RequestInterface::class));
        self::assertCount($expectedTotal, $searchResults->items);

        /** @var SearchItem $item */
        $item = current($searchResults->items);
        self::assertSame($expectedItemId, $item->id);
        self::assertSame($expectedItemType, $item->type);
        self::assertEquals($expectedItemName, $item->name);
        self::assertSame($expectedItemYearPublished, $item->yearPublished);
    }

    /**
     * @return array<string, mixed[]>
     */
    public static function provideBuildCases(): iterable
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
