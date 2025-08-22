<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameLink;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameName;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GamePoll;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRank;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameRatings;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\GameStatistics;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\PollResult;

abstract class AbstractObjectBuilder implements ObjectBuilderInterface
{
    protected string $statsKey = self::STATISTICS;
    protected string $ratingsKey = self::RATINGS;

    protected function getId(Crawler $crawler): int
    {
        return (int) $crawler->attr(self::ID);
    }

    protected function getThumbnail(Crawler $crawler): string
    {
        return $crawler->filter(self::THUMBNAIL)->text();
    }

    protected function getImage(Crawler $crawler): string
    {
        return $crawler->filter(self::IMAGE)->text();
    }

    /** @return GameName[] */
    protected function getNames(Crawler $crawler): array
    {
        $names = [];

        /** @var \DOMElement $name */
        foreach ($crawler->filter(self::NAME) as $name) {
            $names[] = new GameName(
                (int) $name->getAttribute(self::SORT_INDEX),
                $name->getAttribute(self::TYPE),
                $name->getAttribute(self::VALUE),
            );
        }

        return $names;
    }

    protected function getDescription(Crawler $crawler): string
    {
        return $crawler->filter(self::DESCRIPTION)->text();
    }

    /** @return GamePoll[] */
    protected function getPolls(Crawler $crawler): array
    {
        $polls = [];

        /** @var \DOMElement $pollElement */
        foreach ($crawler->filter(self::POLL) as $pollElement) {
            $results = [];
            $pollCrawler = new Crawler($pollElement);
            foreach ($pollCrawler->filter(self::RESULT) as $resultElement) {
                assert($resultElement instanceof \DOMElement);
                $results[] = new PollResult(
                    value: $resultElement->getAttribute(self::VALUE),
                    numVotes: (int) $resultElement->getAttribute(self::NUMBER_OF_VOTES),
                );
            }

            $poll = new GamePoll(
                name: $pollElement->getAttribute(self::NAME),
                title: $pollElement->getAttribute(self::TITLE),
                totalVotes: (int) $pollElement->getAttribute(self::TOTAL_VOTES),
                results: $results,
            );
            $polls[] = $poll;
        }

        return $polls;
    }

    /** @return GameLink[] */
    protected function getLinks(Crawler $crawler): array
    {
        $links = [];

        /** @var \DOMElement $linkElement */
        foreach ($crawler->filter(self::LINK) as $linkElement) {
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
        if (0 === $statsCrawler->count()) {
            return null;
        }
        $stats = new GameStatistics(new GameRatings());
        $ratingsCrawler = $statsCrawler->filter($this->ratingsKey);

        // These two should always be set.
        $stats->ratings->average = (float) $ratingsCrawler->filter(self::AVERAGE)->attr(self::VALUE);
        $stats->ratings->bayesAverage = (float) $ratingsCrawler->filter(self::BAYESIAN_AVERAGE)->attr(self::VALUE);

        // These three are set for collection and game with stats=1.
        $stats->ratings->usersRated = $this->getIntAttribute($ratingsCrawler, self::USERS_RATED);
        $stats->ratings->stdDev = $this->getFloatAttribute($ratingsCrawler, self::STANDARD_DEVIATION);
        $stats->ratings->median = $this->getFloatAttribute($ratingsCrawler, self::MEDIAN);

        // There rest are only set for game with stats=1.
        $stats->ratings->owned = $this->getIntAttribute($ratingsCrawler, self::OWNED);
        $stats->ratings->trading = $this->getIntAttribute($ratingsCrawler, self::TRADING);
        $stats->ratings->wanting = $this->getIntAttribute($ratingsCrawler, self::WANTING);
        $stats->ratings->wishing = $this->getIntAttribute($ratingsCrawler, self::WISHING);
        $stats->ratings->numComments = $this->getIntAttribute($ratingsCrawler, self::NUMBER_OF_COMMENTS);
        $stats->ratings->numWeights = $this->getIntAttribute($ratingsCrawler, self::NUMBER_OF_WEIGHTS);
        $stats->ratings->averageWeight = $this->getFloatAttribute($ratingsCrawler, self::AVERAGE_WEIGHT);
        $this->addRanks($stats->ratings, $ratingsCrawler);

        return $stats;
    }

    protected function getIntAttribute(Crawler $crawler, string $selector): ?int
    {
        $subCrawler = $crawler->filter($selector);
        if (0 === $subCrawler->count()) {
            return null;
        }

        return (int) $subCrawler->attr(self::VALUE);
    }

    private function getFloatAttribute(Crawler $crawler, string $selector): ?float
    {
        $subCrawler = $crawler->filter($selector);
        if (0 === $subCrawler->count()) {
            return null;
        }

        return (float) $subCrawler->attr(self::VALUE);
    }

    private function addRanks(GameRatings $ratings, Crawler $ratingsCrawler): void
    {
        $ranks = $ratingsCrawler->filter(self::RANKS);
        if (0 === $ranks->count()) {
            return;
        }

        /** @var \DOMElement $rank */
        foreach ($ranks->filter(self::RANK) as $rank) {
            $ratings->ranks[] = new GameRank(
                id: (int) $rank->getAttribute(self::ID),
                name: $rank->getAttribute(self::RANK_NAME),
                type: $rank->getAttribute(self::RANK_TYPE),
                friendlyName: $rank->getAttribute(self::RANK_FRIENDLY_NAME),
                value: (int) $rank->getAttribute(self::VALUE),
                bayesAverage: (float) $rank->getAttribute(self::RANK_BAYESIAN_AVERAGE),
            );
        }
    }
}
