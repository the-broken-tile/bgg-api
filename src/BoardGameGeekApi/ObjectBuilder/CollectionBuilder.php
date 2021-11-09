<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Collection;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionItem;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionStatus;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\CollectionVersion;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\Request\CollectionRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class CollectionBuilder extends AbstractObjectBuilder
{
    protected string $statsKey = 'stats';
    protected string $ratingsKey = 'rating';

    public function supports(RequestInterface $request): bool
    {
        return $request instanceof CollectionRequest;
    }

    public function build(string $response): DataTransferObjectInterface
    {
        $collection = new Collection();
        $crawler = new Crawler($response);
        $collection->totalItems = (int) $crawler->filter('items')->attr('totalitems');
        $collection->pubDate = $crawler->filter('items')->attr('pubdate');

        $this->addItems($crawler, $collection);

        return $collection;
    }

    private function addItems(Crawler $crawler, Collection $collection): void
    {
        /** @var DOMElement $itemElement */
        foreach ($crawler->filter('items > item') as $itemElement) {
            $item = new CollectionItem(
                (int) $itemElement->getAttribute('objectid'),
                $itemElement->getAttribute('objecttype'),
                $itemElement->getAttribute('subtype'),
                (int) $itemElement->getAttribute('collid'),
            );
            $itemCrawler = new Crawler($itemElement);

            $this->addName($itemCrawler, $item);

            $this->addYearPublished($itemCrawler, $item);
            $this->addImage($itemCrawler, $item);
            $this->addThumbnail($itemCrawler, $item);
            $this->addStatus($itemCrawler, $item);
            $this->addNumberOfPlays($itemCrawler, $item);
            $this->addComment($itemCrawler, $item);
            $this->addVersion($itemCrawler, $item);
            $item->stats = $this->getStats($itemCrawler);

            $collection->items[] = $item;
        }
    }

    private function addYearPublished(Crawler $itemCrawler, CollectionItem $item): void
    {
        $yearPublishedElement = $itemCrawler->filter('yearpublished');
        if ($yearPublishedElement->count() === 0) {
            return;
        }
        $item->yearPublished = $yearPublishedElement->text();
    }

    /**
     * @param CollectionItem|CollectionVersion $item
     */
    private function addImage(Crawler $crawler, $item): void
    {
        $image = $crawler->filter('image');
        if ($image->count() === 0) {
            return;
        }
        $item->image = $image->text();
    }

    /**
     * @param Crawler $crawler
     * @param CollectionItem|CollectionVersion $item
     */
    private function addThumbnail(Crawler $crawler, $item): void
    {
        $thumbnail = $crawler->filter('thumbnail');
        if ($thumbnail->count() === 0) {
            return;
        }
        $item->thumbnail = $thumbnail->text();
    }

    /**
     * @param Crawler $crawler
     * @param CollectionItem|CollectionVersion $item
     */
    private function addName(Crawler $crawler, $item): void
    {
        /** @var DOMElement $name */
        foreach ($crawler->children('name') as $name) {
            $item->names[] = new GameName(
                (int) $name->getAttribute('sortindex'),
                $name->getAttribute('type'),
                $name->getAttribute('value') ?: $name->textContent,
            );
        }
    }

    private function addStatus(Crawler $itemCrawler, CollectionItem $item): void
    {
        $status = $itemCrawler->filter('status');

        $item->status = new CollectionStatus(
            $status->attr('own'),
            $status->attr('prevowned'),
            $status->attr('fortrade'),
            $status->attr('want'),
            $status->attr('wanttoplay'),
            $status->attr('wanttobuy'),
            $status->attr('wishlist'),
            $status->attr('preordered'),
            $status->attr('lastmodified'),
            $status->attr('wishlistpriority'),
        );
    }

    private function addNumberOfPlays(Crawler $crawler, CollectionItem $item): void
    {
        $numberOfPlays = $crawler->filter('numplays');
        if ($numberOfPlays->count() === 0) {
            return;
        }

        $item->numberOfPlays = (int) $numberOfPlays->text();
    }

    private function addComment(Crawler $itemCrawler, CollectionItem $item): void
    {
        $comment = $itemCrawler->filter('comment');
        if ($comment->count() === 0) {
            return;
        }
        $item->comment = $comment->text();
    }

    private function addVersion(Crawler $itemCrawler, CollectionItem $item): void
    {
        $version = $itemCrawler->filter('version');
        if ($version->count() === 0) {
            return;
        }

        /** @var DOMElement $versionItem */
        foreach ($version->filter('item') as $versionItem) {
            $item->version = new CollectionVersion(
                (int) $versionItem->getAttribute('id'),
                $versionItem->getAttribute('type'),
            );
            $versionCrawler = new Crawler($versionItem);
            $this->addImage($versionCrawler, $item->version);
            $this->addThumbnail($versionCrawler, $item->version);
            $this->addName($versionCrawler, $item->version);
            $this->addLinks($versionCrawler, $item->version);
        }
    }

    private function addLinks(Crawler $crawler, CollectionVersion $item): void
    {
        /** @var DOMElement $link */
        foreach ($crawler->children('link') as $link) {
            $item->links[] = new GameLink(
                (int) $link->getAttribute('id'),
                $link->getAttribute('type'),
                $link->getAttribute('value'),
            );
        }
    }
}