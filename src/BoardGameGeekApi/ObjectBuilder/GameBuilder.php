<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObject;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Expansion;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameResults;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class GameBuilder extends AbstractObjectBuilder
{
    public function supports(RequestInterface $request): bool
    {
        return $request instanceof GameRequest;
    }

    /**
     * @return GameResults
     */
    public function build(string $response): DataTransferObjectInterface
    {
        $results = new GameResults();
        $crawler = new Crawler($response);

        /** @var DOMElement $item */
        foreach ($crawler->filter(self::ITEM) as $item) {
            $itemCrawler = new Crawler($item);
            $results->items[] = $this->buildItem($itemCrawler);
            ++$results->total;
        }

        return $results;
    }

    private function buildItem(Crawler $crawler): DataTransferObject
    {
        $object = $this->createObject($crawler);

        $object->id = $this->getId($crawler);
        $object->thumbnail = $this->getThumbnail($crawler);
        $object->image = $this->getImage($crawler);
        $object->names = $this->getNames($crawler);
        $object->description = $this->getDescription($crawler);
        $object->yearPublished = $this->getIntAttribute($crawler, self::YEAR_PUBLISHED);
        $object->minPlayers = $this->getIntAttribute($crawler, self::MIN_PLAYERS);
        $object->maxPlayers = $this->getIntAttribute($crawler, self::MAX_PLAYERS);
        $object->polls = $this->getPolls($crawler);
        $object->playingTime = $this->getIntAttribute($crawler, self::PLAYING_TIME);
        $object->minPlayTime = $this->getIntAttribute($crawler, self::MIN_PLAY_TIME);
        $object->maxPlayTime = $this->getIntAttribute($crawler, self::MAX_PLAY_TIME);
        $object->minAge = $this->getIntAttribute($crawler, self::MIN_AGE);
        $object->links = $this->getLinks($crawler);
        $object->stats = $this->getStats($crawler);

        return $object;
    }

    private function createObject(Crawler $crawler): DataTransferObject
    {
        if ('boardgame' === $crawler->attr(self::TYPE)) {
            return new Game();
        }

        return new Expansion();
    }
}
