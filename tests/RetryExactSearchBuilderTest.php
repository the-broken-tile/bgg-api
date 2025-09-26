<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\RetryExactSearchBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\RetrySearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @internal
 */
#[CoversClass(RetryExactSearchBuilder::class)]
final class RetryExactSearchBuilderTest extends TestCase
{
    private const string RESPONSE = '::response::';

    /**
     * @throws Exception
     */
    public function testSupports(): void
    {
        $builder = new RetryExactSearchBuilder($this->createMock(ObjectBuilderInterface::class));

        self::assertTrue($builder->supports(new RetrySearchRequest($this->createMock(RequestInterface::class), [])));

        self::assertFalse($builder->supports(new SearchRequest('search')));
    }

    /**
     * @throws Exception
     */
    #[DataProvider('provideBuildCases')]
    public function testBuild(string $query, SearchResults $searchResults, SearchResults $expectedResults): void
    {
        $searchBuilder = $this->createMock(ObjectBuilderInterface::class);
        $searchBuilder
            ->method('build')
            ->with(self::RESPONSE, self::isInstanceOf(RequestInterface::class))
            ->willReturn($searchResults)
        ;

        $request = $this->createMock(RequestInterface::class);
        $request
            ->method('getParams')
            ->willReturn([RequestInterface::PARAM_QUERY => $query])
        ;

        $builder = new RetryExactSearchBuilder($searchBuilder);

        self::assertEquals($expectedResults, $builder->build(self::RESPONSE, $request));
    }

    /**
     * @return array<string, array{
     *     query: string,
     *     searchResults: SearchResults,
     *     expectedResults: SearchResults,
     * }>
     */
    public static function provideBuildCases(): iterable
    {
        $searchItem = new SearchItem(
            id: 1,
            type: SearchItem::TYPE_BOARD_GAME,
            name: new GameName(
                sortIndex: 1,
                type: GameName::TYPE_PRIMARY,
                value: '::test-name::',
            ),
            yearPublished: 2022,
        );
        $secondItem = new SearchItem(
            id: 2,
            type: SearchItem::TYPE_BOARD_GAME,
            name: new GameName(
                sortIndex: 2,
                type: GameName::TYPE_ALTERNATE,
                value: '::test::',
            ),
            yearPublished: 2022,
        );

        return [
            'first result is exact' => [
                'query' => '::test-name::',
                'searchResults' => new SearchResults([
                    $searchItem,
                    $secondItem,
                ]),
                'expectedResults' => new SearchResults([$searchItem]),
            ],
            'second result is exact' => [
                'query' => '::test-name::',
                'searchResults' => new SearchResults([
                    $secondItem,
                    $searchItem,
                ]),
                'expectedResults' => new SearchResults([$searchItem]),
            ],
        ];
    }
}
