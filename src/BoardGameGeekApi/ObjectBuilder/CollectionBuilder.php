<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

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
    public function build(string $response, RequestInterface $request): DataTransferObjectInterface
    {
        $collection = new Collection();
        $crawler = new Crawler($response);
        $pubDate = $crawler->filter(self::ITEMS)->attr(self::PUBLISH_DATE);

        assert(is_string($pubDate));
        $collection->pubDate = $pubDate;

        $this->addItems($crawler, $collection);

        return $collection;
    }

    private function addItems(Crawler $crawler, Collection $collection): void
    {
        /** @var \DOMElement $itemElement */
        foreach ($crawler->filter(sprintf('%s > %s', self::ITEMS, self::ITEM)) as $itemElement) {
            $item = new CollectionItem(
                objectId: (int) $itemElement->getAttribute(self::OBJECT_ID),
                objectType: $itemElement->getAttribute(self::OBJECT_TYPE),
                subType: $itemElement->getAttribute(self::SUB_TYPE),
                collId: (int) $itemElement->getAttribute(self::COLLECTION_ID),
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
        if (0 === $yearPublishedElement->count()) {
            return;
        }
        $item->yearPublished = (int) $yearPublishedElement->text();
    }

    private function addImage(Crawler $crawler, CollectionItem|CollectionVersion $item): void
    {
        $image = $crawler->filter(self::IMAGE);
        if (0 === $image->count()) {
            return;
        }
        $item->image = $image->text();
    }

    private function addThumbnail(Crawler $crawler, CollectionItem|CollectionVersion $item): void
    {
        $thumbnail = $crawler->filter(self::THUMBNAIL);
        if (0 === $thumbnail->count()) {
            return;
        }
        $item->thumbnail = $thumbnail->text();
    }

    private function addName(Crawler $crawler, CollectionItem|CollectionVersion $item): void
    {
        /** @var \DOMElement $name */
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
            own: (bool) $status->attr(self::COLLECTION_OWN),
            previouslyOwned: (bool) $status->attr(self::COLLECTION_PREVIOUSLY_OWN),
            forTrade: (bool) $status->attr(self::COLLECTION_FOR_TRADE),
            want: (bool) $status->attr(self::COLLECTION_WANT),
            wantToPlay: (bool) $status->attr(self::COLLECTION_WANT_TO_PLAY),
            wantToBuy: (bool) $status->attr(self::COLLECTION_WANT_TO_BUY),
            wishlist: (bool) $status->attr(self::COLLECTION_WISHLIST),
            preOrdered: (bool) $status->attr(self::COLLECTION_PRE_ORDERED),
            lastModified: $lastModified,
            wishlistPriority: null === $wishlistPriority ? null : (int) $wishlistPriority,
        );
    }

    private function addNumberOfPlays(Crawler $crawler, CollectionItem $item): void
    {
        $numberOfPlays = $crawler->filter(self::NUMBER_OF_PLAYS);
        if (0 === $numberOfPlays->count()) {
            return;
        }

        $item->numberOfPlays = (int) $numberOfPlays->text();
    }

    private function addComment(Crawler $itemCrawler, CollectionItem $item): void
    {
        $comment = $itemCrawler->filter(self::COMMENT);
        if (0 === $comment->count()) {
            return;
        }
        $item->comment = $comment->text();
    }

    private function addVersion(Crawler $itemCrawler, CollectionItem $item): void
    {
        $version = $itemCrawler->filter(self::VERSION);
        if (0 === $version->count()) {
            return;
        }

        /** @var \DOMElement $versionItem */
        foreach ($version->filter(self::ITEM) as $versionItem) {
            $item->version = new CollectionVersion(
                id: (int) $versionItem->getAttribute(self::ID),
                type: $versionItem->getAttribute(self::TYPE),
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
        /** @var \DOMElement $link */
        foreach ($crawler->children(self::LINK) as $link) {
            $item->links[] = new GameLink(
                id: (int) $link->getAttribute(self::ID),
                type: $link->getAttribute(self::TYPE),
                value: $link->getAttribute(self::VALUE),
            );
        }
    }
}
