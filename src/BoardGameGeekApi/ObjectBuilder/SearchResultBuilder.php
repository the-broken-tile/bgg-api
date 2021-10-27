<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\SearchResults;

final class SearchResultBuilder extends AbstractObjectBuilder
{
    public function supports(string $response): bool
    {
        return str_contains($response, '<items total="');
    }

    public function build(string $response): SearchResults
    {
        $crawler = new Crawler($response);
        $items = $crawler->filter('items')->eq(0);
        $results = new SearchResults();
        $results->total = (int) $items->attr('total');
        $results->items = $this->getItems($crawler);

        return $results;
    }

    /** @return SearchItem[] */
    private function getItems(Crawler $crawler): array
    {
        $items = [];
        /** @var DOMElement $itemElement */
        foreach ($crawler->filter('item') as $itemElement) {
            $itemCrawler = new Crawler($itemElement);
            $name = $itemCrawler->filter('name')->eq(0);
            $yearPublished = $itemCrawler->filter('yearpublished');

            $items[] = new SearchItem(
                (int) $itemElement->getAttribute('id'),
                $itemElement->getAttribute('type'),
                new GameName(
                    1,
                    $name->attr('type'),
                    $name->attr('value'),
                ),
                $yearPublished->count() ? (int) $yearPublished->attr('value'): null,
            );
        }

        return $items;
    }
}