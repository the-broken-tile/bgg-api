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
    protected string $statsKey = 'statistics';
    protected string $ratingsKey = 'ratings';
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

    protected function getStats(Crawler $crawler): ?GameStatistics
    {
        $statsCrawler = $crawler->filter($this->statsKey);
        if ($statsCrawler->count() === 0) {
            return null;
        }
        $stats = new GameStatistics();
        $ratings = new GameRatings();
        $ratingsCrawler = $statsCrawler->filter($this->ratingsKey);

        //These two should always  be set
        $ratings->average = (float) $ratingsCrawler->filter('average')->attr('value');
        $ratings->bayesAverage = (float) $ratingsCrawler->filter('bayesaverage')->attr('value');

        //These three are set for collection with stats=1 and game (with stats=1, currently always on)
        $ratings->usersRated = $this->getIntAttribute($ratingsCrawler,'usersrated');
        $ratings->stdDev = $this->getFloatAttribute($ratingsCrawler,'stddev');
        $ratings->median = $this->getFloatAttribute($ratingsCrawler,'median');

        //There rest are only set for game (with stats=1, currently always for game)
        $ratings->owned = $this->getIntAttribute($ratingsCrawler, 'owned');
        $ratings->trading = $this->getIntAttribute($ratingsCrawler, 'trading');
        $ratings->wanting = $this->getIntAttribute($ratingsCrawler, 'wanting');
        $ratings->wishing = $this->getIntAttribute($ratingsCrawler, 'wishing');
        $ratings->numComments = $this->getIntAttribute($ratingsCrawler, 'numcomments');
        $ratings->numWeights = $this->getIntAttribute($ratingsCrawler, 'numweights');
        $ratings->averageWeight = $this->getIntAttribute($ratingsCrawler, 'averageweight');

        $stats->ratings = $ratings;

        return $stats;
    }

    protected function getIntAttribute(Crawler $crawler, string $selector): ?int
    {
        $subCrawler = $crawler->filter($selector);
        if ($subCrawler->count() === 0) {
            return null;
        }
        return (int) $subCrawler->attr('value');
    }

    private function getFloatAttribute(Crawler $crawler, string $selector): ?float
    {
        $subCrawler = $crawler->filter($selector);
        if ($subCrawler->count() === 0) {
            return null;
        }
        return (float) $subCrawler->attr('value');
    }
}