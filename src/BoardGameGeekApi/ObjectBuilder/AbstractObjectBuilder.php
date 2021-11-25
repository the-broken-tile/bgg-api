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
    protected string $statsKey = self::STATISTICS;
    protected string $ratingsKey = self::RATINGS;
    protected Crawler $crawler;

    protected function getId(): int
    {
        return (int) $this->crawler->attr(self::ID);
    }

    protected function getThumbnail(): string
    {
        return $this->crawler->filter(self::THUMBNAIL)->text();
    }

    protected function getImage(): string
    {
        return $this->crawler->filter(self::IMAGE)->text();
    }

    /** @return GameName[] */
    protected function getNames(): array
    {
        $names = [];

        /** @var DOMElement $name */
        foreach ($this->crawler->filter(self::NAME) as $name) {
            $names[] = new GameName(
                (int) $name->getAttribute(self::SORT_INDEX),
                $name->getAttribute(self::TYPE),
                $name->getAttribute(self::VALUE),
            );
        }

        return $names;
    }

    protected function getDescription(): string
    {
        return $this->crawler->filter(self::DESCRIPTION)->text();
    }

    /** @return GamePoll[] */
    protected function getPolls(): array
    {
        $polls = [];
        /** @var DOMElement $pollElement */
        foreach ($this->crawler->filter(self::POLL) as $pollElement) {

            $poll = new GamePoll(
                $pollElement->getAttribute(self::NAME),
                $pollElement->getAttribute(self::TITLE),
                (int) $pollElement->getAttribute(self::TOTAL_VOTES),
            );
            $pollCrawler = new Crawler($pollElement);
            foreach ($pollCrawler->filter(self::RESULT) as $resultElement) {
                $poll->results[] = new PollResult(
                    $resultElement->getAttribute(self::VALUE),
                    (int) $resultElement->getAttribute(self::NUMBER_OF_VOTES),
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
        foreach ($this->crawler->filter(self::LINK) as $linkElement) {
            $links[] = new GameLink(
                (int) $linkElement->getAttribute(self::ID),
                $linkElement->getAttribute(self::TYPE),
                $linkElement->getAttribute(self::VALUE),
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
        $ratingsCrawler = $statsCrawler->filter($this->ratingsKey);

        //These two should always be set.
        $stats->ratings->average = (float) $ratingsCrawler->filter(self::AVERAGE)->attr(self::VALUE);
        $stats->ratings->bayesAverage = (float) $ratingsCrawler->filter(self::BAYESIAN_AVERAGE)->attr(self::VALUE);

        //These three are set for collection and game with stats=1.
        $stats->ratings->usersRated = $this->getIntAttribute($ratingsCrawler,self::USERS_RATED);
        $stats->ratings->stdDev = $this->getFloatAttribute($ratingsCrawler,self::STANDARD_DEVIATION);
        $stats->ratings->median = $this->getFloatAttribute($ratingsCrawler,self::MEDIAN);

        //There rest are only set for game with stats=1.
        $stats->ratings->owned = $this->getIntAttribute($ratingsCrawler, self::OWNED);
        $stats->ratings->trading = $this->getIntAttribute($ratingsCrawler, self::TRADING);
        $stats->ratings->wanting = $this->getIntAttribute($ratingsCrawler, self::WANTING);
        $stats->ratings->wishing = $this->getIntAttribute($ratingsCrawler, self::WISHING);
        $stats->ratings->numComments = $this->getIntAttribute($ratingsCrawler, self::NUMBER_OF_COMMENTS);
        $stats->ratings->numWeights = $this->getIntAttribute($ratingsCrawler, self::NUMBER_OF_WEIGHTS);
        $stats->ratings->averageWeight = $this->getFloatAttribute($ratingsCrawler, self::AVERAGE_WEIGHT);

        return $stats;
    }

    protected function getIntAttribute(Crawler $crawler, string $selector): ?int
    {
        $subCrawler = $crawler->filter($selector);
        if ($subCrawler->count() === 0) {
            return null;
        }
        return (int) $subCrawler->attr(self::VALUE);
    }

    private function getFloatAttribute(Crawler $crawler, string $selector): ?float
    {
        $subCrawler = $crawler->filter($selector);
        if ($subCrawler->count() === 0) {
            return null;
        }
        return (float) $subCrawler->attr(self::VALUE);
    }
}