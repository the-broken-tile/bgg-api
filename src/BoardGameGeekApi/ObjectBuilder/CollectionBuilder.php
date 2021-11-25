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
    protected string $statsKey = self::STATS;
    protected string $ratingsKey = self::RATING;

    public function supports(RequestInterface $request): bool
    {
        return $request instanceof CollectionRequest;
    }

    /**
     * @return Collection
     */
    public function build(string $response): DataTransferObjectInterface
    {
        $collection = new Collection();
        $crawler = new Crawler($response);
        $collection->totalItems = (int) $crawler->filter(self::ITEMS)->attr(self::TOTAL_ITEMS);
        $pubDate = $crawler->filter(self::ITEMS)->attr(self::PUBLISH_DATE);;
        assert(is_string($pubDate));
        $collection->pubDate = $pubDate;

        $this->addItems($crawler, $collection);

        return $collection;
    }

    private function addItems(Crawler $crawler, Collection $collection): void
    {
        /** @var DOMElement $itemElement */
        foreach ($crawler->filter(sprintf('%s > %s', self::ITEMS, self::ITEM)) as $itemElement) {
            $item = new CollectionItem(
                (int) $itemElement->getAttribute(self::OBJECT_ID),
                $itemElement->getAttribute(self::OBJECT_TYPE),
                $itemElement->getAttribute(self::SUB_TYPE),
                (int) $itemElement->getAttribute(self::COLLECTION_ID),
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
        $yearPublishedElement = $itemCrawler->filter(self::YEAR_PUBLISHED);
        if ($yearPublishedElement->count() === 0) {
            return;
        }
        $item->yearPublished = (int) $yearPublishedElement->text();
    }

    /**
     * @param CollectionItem|CollectionVersion $item
     */
    private function addImage(Crawler $crawler, $item): void
    {
        $image = $crawler->filter(self::IMAGE);
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
        $thumbnail = $crawler->filter(self::THUMBNAIL);
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
        foreach ($crawler->children(self::NAME) as $name) {
            $item->names[] = new GameName(
                (int) $name->getAttribute(self::SORT_INDEX),
                $name->getAttribute(self::TYPE),
                $name->getAttribute(self::VALUE) ?: $name->textContent,
            );
        }
    }

    private function addStatus(Crawler $itemCrawler, CollectionItem $item): void
    {
        $status = $itemCrawler->filter(self::COLLECTION_STATUS);

        $lastModified = $status->attr(self::LAST_MODIFIED);
        assert(is_string($lastModified));
        $wishlistPriority = $status->attr(self::COLLECTION_WISHLIST_PRIORITY);
        $item->status = new CollectionStatus(
            (bool) $status->attr(self::COLLECTION_OWN),
            (bool) $status->attr(self::COLLECTION_PREVIOUSLY_OWN),
            (bool) $status->attr(self::COLLECTION_FOR_TRADE),
            (bool) $status->attr(self::COLLECTION_WANT),
            (bool) $status->attr(self::COLLECTION_WANT_TO_PLAY),
            (bool) $status->attr(self::COLLECTION_WANT_TO_BUY),
            (bool) $status->attr(self::COLLECTION_WISHLIST),
            (bool) $status->attr(self::COLLECTION_PRE_ORDERED),
            $lastModified,
            $wishlistPriority === null ? null : (int) $wishlistPriority,
        );
    }

    private function addNumberOfPlays(Crawler $crawler, CollectionItem $item): void
    {
        $numberOfPlays = $crawler->filter(self::NUMBER_OF_PLAYS);
        if ($numberOfPlays->count() === 0) {
            return;
        }

        $item->numberOfPlays = (int) $numberOfPlays->text();
    }

    private function addComment(Crawler $itemCrawler, CollectionItem $item): void
    {
        $comment = $itemCrawler->filter(self::COMMENT);
        if ($comment->count() === 0) {
            return;
        }
        $item->comment = $comment->text();
    }

    private function addVersion(Crawler $itemCrawler, CollectionItem $item): void
    {
        $version = $itemCrawler->filter(self::VERSION);
        if ($version->count() === 0) {
            return;
        }

        /** @var DOMElement $versionItem */
        foreach ($version->filter(self::ITEM) as $versionItem) {
            $item->version = new CollectionVersion(
                (int) $versionItem->getAttribute(self::ID),
                $versionItem->getAttribute(self::TYPE),
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
        foreach ($crawler->children(self::LINK) as $link) {
            $item->links[] = new GameLink(
                (int) $link->getAttribute(self::ID),
                $link->getAttribute(self::TYPE),
                $link->getAttribute(self::VALUE),
            );
        }
    }
}