<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\User;
use TheBrokenTile\BoardGameGeekApi\DataTransferObject\UserBuddy;
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

    /**
     * @throws InvalidResponseException
     *
     * @return User
     */
    public function build(string $response, RequestInterface $request): DataTransferObjectInterface
    {
        $user = new User();
        $crawler = (new Crawler($response))->filter(self::USER)->eq(0);

        $id = $crawler->attr(self::ID);
        if (!is_numeric($id)) {
            throw new InvalidResponseException('"id" should be an integer');
        }
        $user->id = (int) $id;
        $name = $crawler->attr(self::NAME);
        if (!\is_string($name)) {
            throw new InvalidResponseException('"name" should be a string');
        }
        $user->name = $name;
        $user->firstName = $this->getStringValue($crawler, self::USER_FIRST_NAME);
        $user->lastName = $this->getStringValue($crawler, self::USER_LAST_NAME);
        $user->avatarLink = $this->getSTringValue($crawler, self::USER_AVATAR_LINK);
        $user->yearRegistered = $this->getIntValue($crawler, self::USER_YEAR_REGISTERED);
        $user->lastLogin = $this->getStringValue($crawler, self::USER_LAST_LOGIN);
        $user->stateOrProvince = $this->getStringValue($crawler, self::USER_STATE_OR_PROVINCE);
        $user->country = $this->getStringValue($crawler, self::USER_COUNTRY);
        $user->webAddress = $this->getStringValue($crawler, self::USER_WEB_ADDRESS);
        $user->xBoxAccount = $this->getStringValue($crawler, self::USER_ACCOUNT_XBOX);
        $user->wiiAccount = $this->getStringValue($crawler, self::USER_ACCOUNT_WII);
        $user->psnAccount = $this->getStringValue($crawler, self::USER_ACCOUNT_PSN);
        $user->battleNetAccount = $this->getStringValue($crawler, self::USER_ACCOUNT_BATTLE_NET);
        $user->steamAccount = $this->getStringValue($crawler, self::USER_ACCOUNT_STEAM);
        $user->tradeRating = $this->getIntValue($crawler, self::USER_TRADE_RATING);
        $user->marketRating = $this->getIntValue($crawler, self::USER_MARKET_RATING);

        $this->addBuddies($crawler, $user);
        $this->addGuilds($crawler, $user);
        $this->addTop($crawler, $user);
        $this->addHot($crawler, $user);

        return $user;
    }

    private function addBuddies(Crawler $crawler, User $user): void
    {
        $buddies = $crawler->filter(self::USER_BUDDIES);
        if (0 === $buddies->count()) {
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
        if (0 === $guilds->count()) {
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
        if (0 === $top->count()) {
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
        if (0 === $hot->count()) {
            return;
        }

        /** @var DOMElement $item */
        foreach ($hot->filter(self::ITEM) as $item) {
            $user->hot[] = new UserHotItem(
                (int) $item->getAttribute(self::ID),
                (int) $item->getAttribute(self::RANK),
                $item->getAttribute(self::NAME),
                $item->getAttribute(self::VALUE),
            );
        }
    }

    /**
     * @throws InvalidResponseException
     */
    private function getStringValue(Crawler $crawler, string $selector): string
    {
        $value = $crawler->filter($selector)->attr(self::VALUE);
        if (null === $value) {
            throw new InvalidResponseException(sprintf('"%s" is required', $selector));
        }

        return $value;
    }

    /**
     * @throws InvalidResponseException
     */
    private function getIntValue(Crawler $crawler, string $selector): ?int
    {
        $element = $crawler->filter($selector);
        if (0 === $element->count()) {
            return null;
        }

        $value = $element->attr(self::VALUE);
        if (null === $value) {
            throw new InvalidResponseException(sprintf('"%s" is required', $selector));
        }

        if (!is_numeric($value)) {
            throw new InvalidResponseException(sprintf('"%s" should be numeric', $selector));
        }

        return (int) $value;
    }
}
