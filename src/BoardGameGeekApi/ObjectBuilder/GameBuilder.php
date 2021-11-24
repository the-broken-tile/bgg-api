<?php

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
     * @return Game|Expansion
     */
    public function build(string $response): DataTransferObject
    {
        $this->crawler = (new Crawler($response))->filter('item')->eq(0);

        $object = $this->createObject($response);

        $object->id = $this->getId();
        $object->thumbnail = $this->getThumbnail();
        $object->image = $this->getImage();
        $object->names = $this->getNames();
        $object->description = $this->getDescription();
        $object->yearPublished = $this->getIntAttribute($this->crawler, 'yearpublished');
        $object->minPlayers = $this->getIntAttribute($this->crawler, 'minplayers');
        $object->maxPlayers = $this->getIntAttribute($this->crawler, 'maxplayers');
        $object->polls = $this->getPolls();
        $object->playingTime = $this->getIntAttribute($this->crawler, 'playingtime');
        $object->minPlayTime = $this->getIntAttribute($this->crawler, 'minplaytime');
        $object->maxPlayTime = $this->getIntAttribute($this->crawler, 'maxplaytime');
        $object->minAge = $this->getIntAttribute($this->crawler, 'minage');
        $object->links = $this->getLinks();
        $object->stats = $this->getStats($this->crawler);

        return $object;
    }

    /**
     * @return Game|Expansion
     */
    private function createObject(string $response): DataTransferObject
    {
        if (str_contains($response, '<item type="boardgame"')) {
            return new Game();
        }

        return new Expansion();
    }

}