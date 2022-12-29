<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class User implements DataTransferObjectInterface
{
    public int $id;
    public string $name;
    public string $firstName;
    public string $lastName;
    public string $avatarLink;
    public int $yearRegistered;
    public string $lastLogin;
    public string $stateOrProvince;
    public string $country;
    public string $webAddress;
    public string $xBoxAccount;
    public string $wiiAccount;
    public string $psnAccount;
    public string $battleNetAccount;
    public string $steamAccount;
    public int $tradeRating;
    public ?int $marketRating = null;
    /** @var UserBuddy[] */
    public array $buddies = [];
    /** @var UserGuild[] */
    public array $guilds = [];
    /** @var UserHotItem[] */
    public array $hot = [];
    /** @var UserTopItem[] */
    public array $top = [];

    public function getTotalItems(): int
    {
        return 1;
    }
}
