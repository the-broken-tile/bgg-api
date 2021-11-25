<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;
use TheBrokenTile\BoardGameGeekApi\Request\SearchRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class SearchResultBuilder extends AbstractObjectBuilder
{
    public function supports(RequestInterface $request): bool
    {
        return $request instanceof SearchRequest;
    }

    public function build(string $response): SearchResults
    {
        $crawler = new Crawler($response);
        $items = $crawler->filter(self::ITEMS)->eq(0);
        $results = new SearchResults();
        $results->total = (int) $items->attr(self::TOTAL);
        $results->items = $this->getItems($crawler);

        return $results;
    }

    /** @return SearchItem[] */
    private function getItems(Crawler $crawler): array
    {
        $items = [];
        /** @var DOMElement $itemElement */
        foreach ($crawler->filter(self::ITEM) as $itemElement) {
            $itemCrawler = new Crawler($itemElement);
            $name = $itemCrawler->filter(self::NAME)->eq(0);
            $yearPublished = $itemCrawler->filter(self::YEAR_PUBLISHED);
            $value = $name->attr(self::VALUE);
            assert(is_string($value));

            $items[] = new SearchItem(
                (int) $itemElement->getAttribute(self::ID),
                $itemElement->getAttribute(self::TYPE),
                new GameName(
                    1,
                    $name->attr(self::TYPE),
                    $value,
                ),
                $yearPublished->count() ? (int) $yearPublished->attr(self::VALUE): null,
            );
        }

        return $items;
    }
}