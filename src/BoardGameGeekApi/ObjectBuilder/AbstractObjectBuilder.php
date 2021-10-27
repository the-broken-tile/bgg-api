<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GamePoll;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRatings;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameStatistics;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\PollResult;

abstract class AbstractObjectBuilder implements ObjectBuilderInterface
{
    protected Crawler $crawler;

    protected function getId(): int
    {
        return (int) $this->crawler->attr('id');
    }

    protected function getThumbnail(): string
    {
        return $this->crawler->filter('thumbnail')->text();
    }

    protected function getImage(): string
    {
        return $this->crawler->filter('image')->text();
    }

    /** @return GameName[] */
    protected function getNames(): array
    {
        $names = [];

        /** @var DOMElement $name */
        foreach ($this->crawler->filter('name') as $name) {
            $names[] = new GameName(
                (int) $name->getAttribute('sortindex'),
                $name->getAttribute('type'),
                $name->getAttribute('value'),
            );
        }

        return $names;
    }

    protected function getDescription(): string
    {
        return $this->crawler->filter('description')->text();
    }

    protected function getYearPublished(): int
    {
        return (int) $this->crawler->filter('yearpublished')->attr('value');
    }

    protected function getMinPlayers(): int
    {
        return (int) $this->crawler->filter('minplayers')->attr('value');
    }

    protected function getMaxPlayers(): int
    {
        return (int) $this->crawler->filter('maxplayers')->attr('value');
    }

    /** @return GamePoll[] */
    protected function getPolls(): array
    {
        $polls = [];
        /** @var DOMElement $pollElement */
        foreach ($this->crawler->filter('poll') as $pollElement) {

            $poll = new GamePoll(
                $pollElement->getAttribute('name'),
                $pollElement->getAttribute('title'),
                (int) $pollElement->getAttribute('totalvotes'),
            );
            $pollCrawler = new Crawler($pollElement);
            foreach ($pollCrawler->filter('result') as $resultElement) {
                $poll->results[] = new PollResult(
                    $resultElement->getAttribute('value'),
                    (int) $resultElement->getAttribute('numvotes'),
                );
            }
            $polls[] = $poll;
        }

        return $polls;
    }

    protected function getPlayingTime(): int
    {
        return (int) $this->crawler->filter('playingtime')->attr('value');
    }

    protected function getMinPlayTime(): int
    {
        return (int) $this->crawler->filter('minplaytime')->attr('value');
    }

    protected function getMaxPlayTime(): int
    {
        return (int) $this->crawler->filter('maxplaytime')->attr('value');
    }

    protected function getMinAge(): int
    {
        return (int) $this->crawler->filter('minage')->attr('value');;
    }

    /** @return GameLink[] */
    protected function getLinks(): array
    {
        $links = [];
        /** @var DOMElement $linkElement */
        foreach ($this->crawler->filter('link') as $linkElement) {
            $links[] = new GameLink(
                (int) $linkElement->getAttribute('id'),
                $linkElement->getAttribute('type'),
                $linkElement->getAttribute('value'),
            );
        }

        return $links;
    }

    protected function getStats(): ?GameStatistics
    {
        if ($this->crawler->filter('statistics')->count() === 0) {
            return null;
        }
        $stats = new GameStatistics();
        $ratings = new GameRatings();

        $ratings->usersRated = (int) $this->crawler->filter('statistics ratings usersrated')->attr('value');
        $ratings->average = (float) $this->crawler->filter('statistics ratings average')->attr('value');
        $ratings->bayesAverage = (float) $this->crawler->filter('statistics ratings bayesaverage')->attr('value');
        $ratings->stdDev = (float) $this->crawler->filter('statistics ratings stddev')->attr('value');
        $ratings->median = (float) $this->crawler->filter('statistics ratings median')->attr('value');
        $ratings->owned = (int) $this->crawler->filter('statistics ratings owned')->attr('value');
        $ratings->trading = (int) $this->crawler->filter('statistics ratings trading')->attr('value');
        $ratings->wanting = (int) $this->crawler->filter('statistics ratings wanting')->attr('value');
        $ratings->wishing = (int) $this->crawler->filter('statistics ratings wishing')->attr('value');
        $ratings->numComments = (int) $this->crawler->filter('statistics ratings numcomments')->attr('value');
        $ratings->numWeights = (int) $this->crawler->filter('statistics ratings numweights')->attr('value');
        $ratings->averageWeight = (int) $this->crawler->filter('statistics ratings averageweight')->attr('value');

        $stats->ratings = $ratings;

        return $stats;
    }
}