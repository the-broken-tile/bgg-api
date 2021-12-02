<?php

declare(strict_types=1);

namespace TheBrokenTile\Test;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\ObjectBuilderInterface;
use TheBrokenTile\BoardGameGeekApi\ObjectBuilder\RetryExactSearchBuilder;
use TheBrokenTile\BoardGameGeekApi\Request\RetrySearchRequest;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

/**
 * @coversDefaultClass \TheBrokenTile\BoardGameGeekApi\ObjectBuilder\RetryExactSearchBuilder
 *
 * @internal
 */
final class RetryExactSearchBuilderTest extends TestCase
{
    use ProphecyTrait;

    private const RESPONSE = 'response';

    /**
     * @covers ::supports
     */
    public function testSupports(): void
    {
        $builder = new RetryExactSearchBuilder($this->prophesize(ObjectBuilderInterface::class)->reveal());

        self::assertTrue($builder->supports(new RetrySearchRequest($this->prophesize(RequestInterface::class)->reveal(), [])));

        self::assertFalse($builder->supports(new SearchRequest('search')));
    }

    /**
     * @covers ::build
     * @dataProvider dataProvider
     */
    public function testBuild(string $query, SearchResults $searchResults, SearchResults $expectedResults): void
    {
        $searchBuilder = $this->prophesize(ObjectBuilderInterface::class);
        $searchBuilder->build(self::RESPONSE, Argument::type(RequestInterface::class))
            ->willReturn($searchResults)
        ;
        $searchBuilder = $searchBuilder->reveal();

        $request = $this->prophesize(RequestInterface::class);
        $request->getParams()->willReturn([
            RequestInterface::PARAM_QUERY => $query,
        ]);
        $request = $request->reveal();

        $builder = new RetryExactSearchBuilder($searchBuilder);

        self::assertEquals($expectedResults, $builder->build(self::RESPONSE, $request));
    }

    /**
     * @return array<string, mixed[]>
     */
    public function dataProvider(): array
    {
        $searchItem = new SearchItem(1, SearchItem::TYPE_BOARD_GAME, new GameName(1, GameName::TYPE_PRIMARY, 'test name'), 2022);
        $secondItem = new SearchItem(2, SearchItem::TYPE_BOARD_GAME, new GameName(2, GameName::TYPE_ALTERNATE, 'test'), 2022);

        return [
            'first result is exact' => [
                'query' => 'test name',
                'searchResults' => $this->buildSearchResults([
                    $searchItem,
                    $secondItem,
                ]),
                'expectedResult' => $this->buildSearchResults([$searchItem]),
            ],
            'second result is exact' => [
                'query' => 'test name',
                'searchResults' => $this->buildSearchResults([
                    $secondItem,
                    $searchItem,
                ]),
                'expectedResult' => $this->buildSearchResults([$searchItem]),
            ],
        ];
    }

    /**
     * @param SearchItem[] $items
     */
    private function buildSearchResults(array $items): SearchResults
    {
        $results = new SearchResults();
        $results->items = $items;
        $results->total = \count($items);

        return $results;
    }
}
