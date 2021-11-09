<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserBuddy;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\User;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserGuild;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserTopItem;
use TheBrokenTile\BoardGameGeekApi\Request\UserRequest;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

final class UserBuilder implements ObjectBuilderInterface
{

    public function supports(RequestInterface $request): bool
    {
        return $request instanceof UserRequest;
    }

    public function build(string $response): DataTransferObjectInterface
    {
        $user = new User();
        $crawler = (new Crawler($response))->filter('user')->eq(0);

        $user->id = (int) $crawler->attr('id');
        $user->name = $crawler->attr('name');
        $user->firstName = $crawler->filter('firstname')->attr('value');
        $user->lastName = $crawler->filter('lastname')->attr('value');
        $user->avatarLink = $crawler->filter('avatarlink')->attr('value');
        $user->yearRegistered = $crawler->filter('yearregistered')->attr('value');
        $user->lastLogin = $crawler->filter('lastlogin')->attr('value');
        $user->stateOrProvince = $crawler->filter('stateorprovince')->attr('value');
        $user->country = $crawler->filter('country')->attr('value');
        $user->webAddress = $crawler->filter('webaddress')->attr('value');
        $user->xBoxAccount = $crawler->filter('xboxaccount')->attr('value');
        $user->wiiAccount = $crawler->filter('wiiaccount')->attr('value');
        $user->psnAccount = $crawler->filter('psnaccount')->attr('value');
        $user->battleNetAccount = $crawler->filter('battlenetaccount')->attr('value');
        $user->steamAccount = $crawler->filter('steamaccount')->attr('value');
        $user->tradeRating = (int) $crawler->filter('traderating')->attr('value');
        $user->marketRating = (int) $crawler->filter('marketrating')->attr('value');

        $this->addBuddies($crawler, $user);
        $this->addGuilds($crawler, $user);
        $this->addTop($crawler, $user);
        $this->addHot($crawler, $user);

        return $user;
    }

    private function addBuddies(Crawler $crawler, User $user): void
    {
        if ($crawler->filter('buddies')->count() === 0) {
            return;
        }
        /** @var DOMElement $buddy */
        foreach ($crawler->filter('buddies')->filter('buddy') as $buddy) {
            $user->buddies[] = new UserBuddy(
                (int) $buddy->getAttribute('id'),
                $buddy->getAttribute('name'),
            );
        }
    }

    private function addGuilds(Crawler $crawler, User $user): void
    {
        if ($crawler->filter('guilds')->count() === 0) {
            return;
        }

        /** @var DOMElement $guild */
        foreach ($crawler->filter('guilds')->filter('guild') as $guild) {
            $user->guilds[] = new UserGuild(
                (int) $guild->getAttribute('id'),
                $guild->getAttribute('name'),
            );
        }
    }

    private function addTop(Crawler $crawler, User $user): void
    {
        if ($crawler->filter('top')->count() === 0) {
            return;
        }
        /** @var DOMElement $item */
        foreach ($crawler->filter('top')->filter('item') as $item) {
            $user->top[] = new UserTopItem(
                (int) $item->getAttribute('id'),
                (int) $item->getAttribute('rank'),
                $item->getAttribute('name'),
                $item->getAttribute('type'),
            );
        }
    }

    private function addHot(Crawler $crawler, User $user): void
    {
        //@todo when I find a real example of the structure.
    }
}