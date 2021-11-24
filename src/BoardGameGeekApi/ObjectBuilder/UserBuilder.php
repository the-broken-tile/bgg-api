<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserBuddy;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\User;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserGuild;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserHotItem;
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
        $crawler = (new Crawler($response))->filter(self::USER)->eq(0);

        $user->id = (int) $crawler->attr(self::ID);
        $user->name = $crawler->attr(self::NAME);
        $user->firstName = $crawler->filter(self::USER_FIRST_NAME)->attr(self::VALUE);
        $user->lastName = $crawler->filter(self::USER_LAST_NAME)->attr(self::VALUE);
        $user->avatarLink = $crawler->filter(self::USER_AVATAR_LINK)->attr(self::VALUE);
        $user->yearRegistered = $crawler->filter(self::USER_YEAR_REGISTERED)->attr(self::VALUE);
        $user->lastLogin = $crawler->filter(self::USER_LAST_LOGIN)->attr(self::VALUE);
        $user->stateOrProvince = $crawler->filter(self::USER_STATE_OR_PROVINCE)->attr(self::VALUE);
        $user->country = $crawler->filter(self::USER_COUNTRY)->attr(self::VALUE);
        $user->webAddress = $crawler->filter(self::USER_WEB_ADDRESS)->attr(self::VALUE);
        $user->xBoxAccount = $crawler->filter(self::USER_ACCOUNT_XBOX)->attr(self::VALUE);
        $user->wiiAccount = $crawler->filter(self::USER_ACCOUNT_WII)->attr(self::VALUE);
        $user->psnAccount = $crawler->filter(self::USER_ACCOUNT_PSN)->attr(self::VALUE);
        $user->battleNetAccount = $crawler->filter(self::USER_ACCOUNT_BATTLE_NET)->attr(self::VALUE);
        $user->steamAccount = $crawler->filter(self::USER_ACCOUNT_STEAM)->attr(self::VALUE);
        $user->tradeRating = (int) $crawler->filter(self::USER_TRADE_RATING)->attr(self::VALUE);
        $user->marketRating = (int) $crawler->filter(self::USER_MARKET_RATING)->attr(self::VALUE);

        $this->addBuddies($crawler, $user);
        $this->addGuilds($crawler, $user);
        $this->addTop($crawler, $user);
        $this->addHot($crawler, $user);

        return $user;
    }

    private function addBuddies(Crawler $crawler, User $user): void
    {
        $buddies = $crawler->filter(self::USER_BUDDIES);
        if ($buddies->count() === 0) {
            return;
        }
        /** @var DOMElement $buddy */
        foreach ($buddies->filter(self::USER_BUDDY) as $buddy) {
            $user->buddies[] = new UserBuddy(
                (int) $buddy->getAttribute(self::ID),
                $buddy->getAttribute(self::NAME),
            );
        }
    }

    private function addGuilds(Crawler $crawler, User $user): void
    {
        $guilds = $crawler->filter(self::USER_GUILDS);
        if ($guilds->count() === 0) {
            return;
        }

        /** @var DOMElement $guild */
        foreach ($guilds->filter(self::USER_GUILD) as $guild) {
            $user->guilds[] = new UserGuild(
                (int) $guild->getAttribute(self::ID),
                $guild->getAttribute(self::NAME),
            );
        }
    }

    private function addTop(Crawler $crawler, User $user): void
    {
        $top = $crawler->filter(self::USER_TOP);
        if ($top->count() === 0) {
            return;
        }
        /** @var DOMElement $item */
        foreach ($top->filter(self::ITEM) as $item) {
            $user->top[] = new UserTopItem(
                (int) $item->getAttribute(self::ID),
                (int) $item->getAttribute(self::RANK),
                $item->getAttribute(self::NAME),
                $item->getAttribute(self::VALUE),
            );
        }
    }

    private function addHot(Crawler $crawler, User $user): void
    {
        $hot = $crawler->filter(self::USER_HOT);
        if ($hot->count() === 0) {
            return;
        }

        /** @var DOMElement $item */
        foreach ($hot->filter(self::ITEM) as $item) {
            $user->top[] = new UserHotItem(
                (int) $item->getAttribute(self::ID),
                (int) $item->getAttribute(self::RANK),
                $item->getAttribute(self::NAME),
                $item->getAttribute(self::VALUE),
            );
        }
    }
}