<?php

namespace TheBrokenTile\BoardGameGeekApi\DataTransferObject;

final class User implements DataTransferObjectInterface
{
    public int $id;
    public string $name;
    public string $firstName;
    public string $lastName;
    public string $avatarLink;
    public string $yearRegistered;
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
    public int $marketRating;
    /** @var UserBuddy[] */
    public array $buddies = [];
    /** @var UserGuild[] */
    public array $guilds = [];
    /** @var UserTopItem[] */
    public array $hot = [];
    /** @var UserHotItem[]  */
    public array $top = [];
}