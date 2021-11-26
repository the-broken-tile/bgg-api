<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObject;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Expansion;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\Game;
use TheBrokenTile\BoardGameGeekApi\Request\GameRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class GameBuilder extends AbstractObjectBuilder
{
    public function supports(RequestInterface $request): bool
    {
        return $request instanceof GameRequest;
    }

    /**
     * @return Expansion|Game
     */
    public function build(string $response): DataTransferObject
    {
        $this->crawler = (new Crawler($response))->filter(self::ITEM)->eq(0);

        $object = $this->createObject($response);

        $object->id = $this->getId();
        $object->thumbnail = $this->getThumbnail();
        $object->image = $this->getImage();
        $object->names = $this->getNames();
        $object->description = $this->getDescription();
        $object->yearPublished = $this->getIntAttribute($this->crawler, self::YEAR_PUBLISHED);
        $object->minPlayers = $this->getIntAttribute($this->crawler, self::MIN_PLAYERS);
        $object->maxPlayers = $this->getIntAttribute($this->crawler, self::MAX_PLAYERS);
        $object->polls = $this->getPolls();
        $object->playingTime = $this->getIntAttribute($this->crawler, self::PLAYING_TIME);
        $object->minPlayTime = $this->getIntAttribute($this->crawler, self::MIN_PLAY_TIME);
        $object->maxPlayTime = $this->getIntAttribute($this->crawler, self::MAX_PLAY_TIME);
        $object->minAge = $this->getIntAttribute($this->crawler, self::MIN_AGE);
        $object->links = $this->getLinks();
        $object->stats = $this->getStats($this->crawler);

        return $object;
    }

    /**
     * @return Expansion|Game
     */
    private function createObject(string $response): DataTransferObject
    {
        if (str_contains($response, '<item type="boardgame"')) {
            return new Game();
        }

        return new Expansion();
    }
}
