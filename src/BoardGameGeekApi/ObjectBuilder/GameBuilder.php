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
     * @param string $response
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
        $object->yearPublished = $this->getYearPublished();
        $object->minPlayers = $this->getMinPlayers();
        $object->maxPlayers = $this->getMaxPlayers();
        $object->polls = $this->getPolls();
        $object->playingTime = $this->getPlayingTime();
        $object->minPlayTime = $this->getMinPlayTime();
        $object->maxPlayTime = $this->getMaxPlayTime();
        $object->minAge = $this->getMinAge();
        $object->links = $this->getLinks();
        $object->stats = $this->getStats();

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